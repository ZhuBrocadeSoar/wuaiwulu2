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
use app\callme\model\User;

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
            $staticUrl = "https://brocadesoar.cn/callme/";
            $pictureUrl = $staticUrl . "picture/";
            $history = Order::all(function($query){
                $query
                    ->where('openid', Session::get(["session_id" => Request::instance()->param('session_id')])->openid)
                    ->where('pay_state', 'done')
                    ->order('id', 'desc')
                    ->limit(5);
            });
            $historyPackage = [];
            foreach($history as $val){
                $product = Product::get($val->product_id);
                $historyPackage[] = [
                    "product_name" => $product->name,
                    "product_id" => $product->id,
                    "product_img_url" => $pictureUrl . "$product->pic_id",
                ];
            }
            $min = 1;
            $max = Order::max('id');
            $orderIdArray = [];
            for($i = $min; $i <= $max; $i++){
                if(Order::get($i)->pay_state == 'done') $orderIdArray[] = $i;
            }
            $orderIdArrayRandomIndex = array_rand($orderIdArray, 6);
            $randomPackage = [];
            foreach($orderIdArrayRandomIndex as $val){
                $order = Order::get($orderIdArray[$val]);
                $product = Product::get($order->product_id);
                $user = User::get(["openid" => $order->openid]);
                $randomPackage[] = [
                    "user_img_url" => $pictureUrl . "$user->pic_id",
                    "product_name" => $product->name,
                    "product_id" => $product->id,
                    "product_img_url" => $pictureUrl . "$product->pic_id",
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

    public function debug_picture_add(){
        $pic = new Picture;
        $picConn = curl_init();
        curl_setopt($picConn, CURLOPT_URL, "http://on-img.com/chart_image/5afbd4c6e4b0026862677068.png?_=1526540715672");
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

}

?>
