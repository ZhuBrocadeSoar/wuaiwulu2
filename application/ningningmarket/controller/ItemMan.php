<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class ItemMan{
    private function base64FieldsConv(&$oneRow){
        $oneRow["name"] = base64_decode($oneRow["name"]);
        $oneRow["price"] = base64_decode($oneRow["price"]);
    }

    private function getHash(&$hash){
        if(Request::instance()->has('hash')){
            $hash = Request::instance()->param('hash');
        }else{
            $hash = NULL;
        }
    }

    public function items(){
        // 权限检查
        $hash = NULL;
        ItemMan::getHash($hash);
        if(Auth::isUser($hash)){
            $rows = Db::name('items')
                ->select();
            foreach($rows as &$val){
                ItemMan::base64FieldsConv($val);
            }
            $retval = array("state" => "success", "items" => $rows);
        }else{
            $retval = array("state" => "error", "errorMsg" => "No Authrity");
        }
        // dump($rows);
        return json_encode($retval, JSON_FORCE_OBJECT);
    }

    public function item(){
        // 权限检查
        $hash = NULL;
        ItemMan::getHash($hash);
        if(Auth::isUser($hash)){
            // 获取参数
            if(Request::instance()->has('id')){
                $id = Request::instance()->param('id');
                $row = Db::name('items')
                    ->where('id', $id)
                    ->select();
            }else{
                $id = NULL;
            }
            if($id == NULL){
                $retval = array("state" => "error", "errorMsg" => "Param error");
            }else if($row == NULL){
                $retval = array("state" => "error", "errorMsg" => "No such item");
            }else{
                ItemMan::base64FieldsConv($row[0]);
                // dump($row);
                $retval = array("state" => "success", "item" => $row[0]);
            }
        }else{
            $retval = array("state" => "error", "errorMsg" => "No Authrity");
        }
        return json_encode($retval, JSON_FORCE_OBJECT);
    }

    public function registerItem(){
        // 权限检查
        $hash = NULL;
        ItemMan::getHash($hash);
        if(Auth::isAdmin($hash)){
            // 获取参数
            $request = Request::instance();
            if($request->has('name') && $request->has('price') && $request->has('stock')){
                $name = Request::instance()->param('name');
                $price = Request::instance()->param('price');
                $stock = Request::instance()->param('stock');
                $datas = array('name' => base64_encode($name), 
                    'price' => base64_encode($price), 
                    'stock' => $stock,
                    'register_time_stamp' => date('Y-m-d H:i:s', time())
                );
                $counts = Db::name('items')->insert($datas);
            }else{
                $datas = NULL;
            }
            // dump($counts);
            if($datas == NULL){
                $retval = array("state" => "error", "errorMsg" => "Param error");
            }else if($counts > 0){
                $retval = array("state" => "success", "rows" => $counts);
            }else{
                $retval = array("state" => "error", "errorMsg" => "Something is wrong");
            }
        }else{
            $retval = array("state" => "error", "errorMsg" => "No Authrity");
        }
        return json_encode($retval);
    }

    public function removeItem(){
        // 权限检查
        $hash = NULL;
        ItemMan::getHash($hash);
        if(Auth::isAdmin($hash)){
            // 获取参数
            if(Request::instance()->has('id')){
                $id = Request::instance()->param('id');
                $counts = Db::name('items')
                    ->where('id', $id)
                    ->delete();
            }else{
                $id = NULL;
            }
            // dump($counts);
            if($id == NULL){
                $retval = array("state" => "error", "errorMsg" => "Param error");
            }else if($counts > 0){
                $retval = array("state" => "success", "rows" => $counts);
            }else{
                $retval = array("state" => "error", "errorMsg" => "Something is wrong");
            }
        }else{
            $retval = array("state" => "error", "errorMsg" => "No Authrity");
        }
        return json_encode($retval);
    }
}

?>
