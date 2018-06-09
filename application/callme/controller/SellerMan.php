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
use app\callme\model\Register;

class SellerMan extends \think\Controller{
    public function register(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 无限制调用
            // $register = new Register;
            $ownerTell = Request::instance()->param('owner_tell');
            $ownerName = Request::instance()->param('owner_name');
            $sellerName = Request::instance()->param('seller_name');
            $diningHallId = Request::instance()->param('dining_hall_id');
            $register = Register::get([
                "owner_tell" => $ownerTell,
            ]);
            if($register == NULL){
                // 手机号未被占用
                $register = new Register;
                $register->data([
                    "owner_tell" => $ownerTell,
                    "owner_name" => base64_encode($ownerName),
                    "seller_name" => base64_encode($sellerName),
                    "openid" => $session->openid,
                    "dining_hall_id" => $diningHallId,
                ]);
                $register->isUpdate(false)->save();
                return json_encode([
                    "errmsg" => "ok",
                ]);
            }else{
                // 手机号已经被占用
                return json_encode([
                    "errmsg" => "Tell Number Taked Yet",
                ]);
            }
        }
    }

    public function registerList(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 平台管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['platform', 'develop']))
                return Auth::permissionDenided();
            if(!($pass_state_in = Request::instance()->has('pass_state'))){
                $registers = Register::all();
            }else if($pass_state_in == 'submited'){
                $registers = Register::all(function($query){
                    $query
                        ->where('pass_state', 'submited');
                });
            }
            $registerPackage = [];
            foreach($registers as $register){
                $registerPackage[] = [
                    "register_id" => $register->id,
                    "create_time" => $register->create_time,
                    "update_time" => $register->update_time,
                    "owner_tell" => $register->owner_tell,
                    "owner_name" => $register->owner_name,
                    "seller_name" => $register->seller_name,
                    "staff_list" => $register->staff_list,
                    "pass_state" => $register->pass_state,
                ];
            }
            return json_encode([
                "register_list" => $registerPackage,
            ]);
        }
    }

    public function registerConfirm(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 平台管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['platform', 'develop']))
                return Auth::permissionDenided();
            $register = Register::get(Request::instance()->param('register_id'));
            $seller = new Seller;
            $seller->data([
                "openid" => $register->openid,
                "name" => base64_encode($register->seller_name),
                "dining_hall_id" => $register->dining_hall_id,
            ]);
            $seller->isUpdate(false)->save();
            $diningHall = DiningHall::get($register->dining_hall_id);
            $diningHallSellerListOld = $diningHall->seller_list;
            if($diningHallSellerListOld[0] == 0){
                unset($diningHallSellerListOld[0]);
                $diningHallSellerListOld = array_values($diningHallSellerListOld);
            }
            $diningHallSellerListOld[] = $seller->id;
            $diningHall->seller_list = implode(',', $diningHallSellerListOld);
            $diningHall->isUpdate(true)->save();
            $register->seller_id = $seller->id;
            $register->pass_state = 'confirmed';
            $register->isUpdate(true)->save();
            return json_encode([
                "errmsg" => "ok",
            ]);
        }
    }

    public function debugDiningHallAdd(){
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
            $diningHall = new DiningHall;
            $diningHall->name = '朱锦腾的测试食堂';
            $diningHall->isUpdate(false)->save();
        }
    }

}

?>
