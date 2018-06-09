<?php
namespace app\callme\controller;

use app\callme\controller\WxApi;
use think\Request;
use app\callme\model\Session;
use app\callme\model\Seller;
use app\callme\model\Picture;
use app\callme\model\Ads;
use app\callme\model\Order;
use app\callme\model\Product;
use app\callme\model\ProductHistory;
use app\callme\model\User;
use app\callme\model\DiningHall;

class WxSession extends \think\Controller{
    public function index(){
        // WxSession 管理
        if(false){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            $jsCode = Request::instance()->param('code');
            $sessionArr = WxApi::login($jsCode);
            $aNewSession = Session::get([
                'session_key' => $sessionArr['session_key'],
                'openid' => $sessionArr['openid'],
            ]);
            if($aNewSession == NULL){
                $aNewSession = new Session;
                $aNewSession->session_key = $sessionArr['session_key'];
                $aNewSession->openid = $sessionArr['openid'];
                $aNewSession->session_id = sha1($sessionArr['session_key'] . date(DATE_ATOM, time()) );
                $aNewSession->isUpdate(false)->save();
            }
            $aSeller = Seller::get([
                "openid" => $aNewSession->openid,
            ]);
            if($aSeller == NULL){
                // 卖家列表没有该id
                $is_seller = 'false';
            }else{
                $is_seller = 'true';
            }
            return json_encode([
                "session_id" => $aNewSession->session_id,
                "is_seller" => $is_seller,
                "debug_openid" => $sessionArr['openid'],
            ]);
        }
    }

    public function debug_msg(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 开发者可调用
            if(!Auth::checkPass($session->openid, ['develop']))
                return Auth::permissionDenided();
            $data = [
                "keyword1" => [
                    "value" => "word1",
                    "color" => "#173177",
                ],
                "keyword2" => [
                    "value" => "word2",
                    "color" => "#173177",
                ],
                "keyword3" => [
                    "value" => "word3",
                    "color" => "#173177",
                ],
                "keyword4" => [
                    "value" => "word4",
                    "color" => "#173177",
                ],
                "keyword5" => [
                    "value" => "word5",
                    "color" => "#173177",
                ],
            ];
            $openid = $session->openid;
            $session_key = $session->session_key;
            $session_id = $session->session_id;
            $formId = Request::instance()->param('formId');
            $state = WxApi::msgSend(
                $openid,
                $formId,
                $data
            );
            return json_encode([
                "debug_state" => $state,
                "debug_openid" => $openid,
                "debug_session_key" => $session_key,
                "session_id" => $session_id,
            ]);
        }
    }

    public function content(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 无调用限制
            $staticUrl = "https://brocadesoar.cn/callme/";
            $pictureUrl = $staticUrl . "wx_session/picture/";
            $history = Order::all(function($query){
                $query
                    ->where('openid', Session::get(["session_id" => Request::instance()->param('session_id')])->openid)
                    ->where('pay_state', 'done')
                    ->where('offer_state', 'done')
                    ->order('id', 'desc')
                    ->limit(5);
            });
            $historyPackage = [];
            foreach($history as $orderInHistory){
                $foodList = [];
                foreach($orderInHistory->product_id as $product_id){
                    $product = ProductHistory::get($product_id);
                    $foodList[] = [
                        "product_name" => $product->name,
                        "product_id" => $product->id,
                        "product_img_url" => $pictureUrl . "$product->pic_id",

                    ];
                }
                $historyPackage[] = [
                    "food_list" => $foodList,
                    "food_quantity" => $orderInHistory->product_qu,
                ];
            }
            $min = 1;
            $max = Order::max('id');
            $orderIdArray = [];
            for($i = $min; $i <= $max; $i++){
                if(Order::get($i)->pay_state == 'done') $orderIdArray[] = $i;
            }
            if(count($orderIdArray) == 0){
                $orderIdArrayRandomIndex = [];
            }else{
                $orderIdArrayRandomIndex = array_rand($orderIdArray, 6);
            }
            $randomPackage = [];
            foreach($orderIdArrayRandomIndex as $val){
                $order = Order::get($orderIdArray[$val]);
                $foodList = [];
                foreach($order->product_id as $product_id){
                    $product = ProductHistory::get($product_id);
                    $foodList[] = [
                        "product_name" => $product->name,
                        "product_id" => $product->id,
                        "product_img_url" => $pictureUrl . "$product->pic_id",
                    ];
                }
                $user = User::get(["openid" => $order->openid]);
                $randomPackage[] = [
                    "user_img_url" => $pictureUrl . "$user->pic_id",
                    "food_list" => $foodList,
                    "food_quantity" => $orderInHistory->product_qu,
                ];
            }
            // dump($historyPackage);
            // debug
            $retval = [
                "ads_img_url" => [
                    $pictureUrl . Ads::get(1)->image_id,
                    $pictureUrl . Ads::get(2)->image_id,
                    $pictureUrl . Ads::get(3)->image_id,
                ],
                "history" => $historyPackage,
                "random" => $randomPackage,
            ];
            return json_encode($retval);
        }
    }

    public function history(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 无调用限制
            $staticUrl = "https://brocadesoar.cn/callme/";
            $pictureUrl = $staticUrl . "wx_session/picture";
            $historyDone = Order::all(function($query){
                $query
                    ->where('openid', Session::get(["session_id" => Request::instance()->param('session_id')])->openid)
                    ->where('pay_state', 'done')
                    ->where('offer_state', 'done')
                    ->order('id', 'desc');
            });
            $historyDonePackage = [];
            foreach($historyDone as $orderInHistoryDone){
                $foodList = [];
                foreach($orderInHistoryDone->product_id as $product_id){
                    $product = ProductHistory::get($product_id);
                    $foodList[] = [
                        "create_time" => $product->create_time,
                        "seller_name" => Seller::get($product->seller_id)->name,
                        "product_name" => $product->name,
                        "product_id" => $product->id,
                        "product_img_url" => $pictureUrl . "$product->pic_id",
                        "product_total_fee" => $product->total_fee,
                    ];
                }
                $historyDonePackage[] = [
                    "food_list" => $foodList,
                    "food_quantity" => $orderInHistoryDone->product_qu,
                ];
            }
            $historyWait = Order::all(function($query){
                $query
                    ->where('openid', Session::get(["session_id" => Request::instance()->param('session_id')])->openid)
                    ->where('pay_state', 'done')
                    ->where('offer_state', 'wait')
                    ->order('id', 'desc');
            });
            $historyWaitPackage = [];
            foreach($historyWait as $orderInHistoryWait){
                $foodList = [];
                foreach($orderInHistoryWait->product_id as $product_id){
                    $product = ProductHistory::get($product_id);
                    $foodList[] = [
                        "create_time" => $product->create_time,
                        "seller_name" => Seller::get($product->seller_id)->name,
                        "product_name" => $product->name,
                        "product_id" => $product->id,
                        "product_img_url" => $pictureUrl . "$product->pic_id",
                        "product_total_fee" => $product->total_fee,
                    ];
                }
                $historyWaitPackage[] = [
                    "food_list" => $foodList,
                    "food_quantity" => $orderInHistoryWait->product_qu,
                ];
            }
            $retval = [
                "history_done" => $historyDonePackage,
                "history_wait" => $historyWaitPackage,
            ];
            return json_encode($retval);
        }
    }

    public function shop(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 无调用限制
            if(Request::instance()->param('seller_id')){
                $seller = Seller::get(Request::instance()->param('seller_id'));
            }else{
                $seller = Seller::get(["openid" => $session->openid]);
            }
            if($seller == NULL){
                return json_encode([
                    "errmsg" => "No That Seller",
                ]);
            }else{
                // 搜集返回该商家店面信息
                $labels = [];
                $seller_name = $seller->name;
                $seller_img_url = "https://brocadesoar.cn/callme/wx_session/picture/" . $seller->pic_id;
                $product_list = $seller->product_list;
                if($product_list[0] == 0){
                    unset($product_list[0]);
                    $product_list = array_values($product_list);
                }
                $products = [];
                foreach($product_list as $val){
                    $product = Product::get($val);
                    $products[] = [
                        "product_id" => $val,
                        "name" => $product->name,
                        "total_fee" => $product->total_fee,
                        "inventory" => $product->inventory,
                        "img_url" => "https://brocadesoar.cn/callme/wx_session/picture/" . $product->pic_id,
                        "label" => $product->label,
                    ];
                    $labels[] = $product->label;
                }
                $labels = array_values(array_unique($labels));
                return json_encode([
                    "labels" => $labels,
                    "seller_name" => $seller_name,
                    "seller_img_url" => $seller_img_url,
                    "seller_menu_list" => $products,
                ]);
            }
        }
    }

    public function diningHall(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 无调用限制
            $hall = DiningHall::get(Request::instance()->param('dining_hall_id'));
            return json_encode([
                "name" => $hall->name,
                "seller_list" => $hall->seller_list,
            ]);
        }
    }

    public function picture($pic_id){
        $pic = Picture::get($pic_id);
        if($pic == NULL){
            return json_encode([
                "errmsg" => "No Such Picture",
            ]);
        }
        else{
            return response()
                ->data($pic->image_str)
                ->header([
                    'Content-Type' => 'image/png;charset=utf-8',
                ]);
        }
    }

    /*
    public function debug_picture_add(){
        $pic = new Picture;
        $picConn = curl_init();
        $url = "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1526576243509&di=a34ed2e935370f58a71711e48901b162&imgtype=0&src=http%3A%2F%2Fimgsrc.baidu.com%2Fimgad%2Fpic%2Fitem%2Ff7246b600c3387447d2db0ff5b0fd9f9d62aa04d.jpg";
        $url = "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1526576243506&di=559e2fdafe65e9ed13686db46b793631&imgtype=0&src=http%3A%2F%2Fimgsrc.baidu.com%2Fimage%2Fc0%253Dpixel_huitu%252C0%252C0%252C294%252C40%2Fsign%3D76d2668bda58ccbf0fb1bd7a70a0d952%2F4610b912c8fcc3ce06f1060a9945d688d43f20d2.jpg";
        $url = "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1526576243505&di=381c125030660ce11ac1e95f01eb60ac&imgtype=0&src=http%3A%2F%2Fimg.pconline.com.cn%2Fimages%2Fupload%2Fupc%2Ftx%2Fitbbs%2F1707%2F06%2Fc35%2F52048301_1499354066454_mthumb.jpg";
        curl_setopt($picConn, CURLOPT_URL, $url);
        curl_setopt($picConn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($picConn, CURLOPT_HEADER, false);
        $picStrSource = curl_exec($picConn);
        $pic->image_str = $picStrSource;
        curl_close($picConn);
        $pic->isUpdate(false)->save();
        return json_encode([
            "debug_pic_id" => $pic->id,
            "debug_len_source" => strlen($picStrSource),
            "debug_len_base64" => strlen(base64_encode($picStrSource)),
        ]);
    }
     */

}

?>
