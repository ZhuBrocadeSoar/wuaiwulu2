<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class ItemMan{
    private function base64FieldsConv(&$oneRow){
        $oneRow["name"] = base64_decode($oneRow["name"]);
        $oneRow["price"] = base64_decode($oneRow["price"]);
    }

    public function items(){
        // 权限检查
        $rows = Db::name('items')
            ->select();
        foreach($rows as &$val){
            ItemMan::base64FieldsConv($val);
        }
        // dump($rows);
        $retval = json_encode($rows, JSON_FORCE_OBJECT);
        return $retval;
    }

    public function item(){
        // 权限检查
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
        return json_encode($retval, JSON_FORCE_OBJECT);
    }

    public function registerItem(){
        // 权限检查
        // 获取参数
        if(Request::instance()->has('name', 'price', 'stock')){
            $name = Request::instance()->param('name');
            // $price = Request::instance()->param('price');
            // $stock = Request::instance()->param('stock');
            $price = 'test';
            $stock = 100;
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
        return json_encode($retval);
    }

    public function removeItem(){
        // 权限检查
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
        return json_encode($retval);
    }
}

?>
