<?php
namespace app\ningningmarket\controller;

use think\Db;
use app\ningningmarket\model\Items;

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

    public function item($id){
        // 权限检查
        $row = Db::name('items')
            ->where('id', $id)
            ->select();
        ItemMan::base64FieldsConv($row[0]);
        // dump($row);
        $retval = json_encode($row, JSON_FORCE_OBJECT);
        return $retval;
    }

    public function registerItem($name, $price, $stock){
        // 权限检查
        $datas = array('name' => base64_encode($name), 
            'price' => base64_encode($price), 
            'stock' => $stock,
            'register_time_stamp' => date('Y-m-d H:i:s', time())
        );
        $counts = Db::name('items')->insert($datas);
        // dump($counts);
        if($counts > 0){
            $retval = array("state" => "success", "rows" => $counts);
        }else{
            $retval = array("state" => "error", "errorMsg" => "Something is wrong");
        }
        return json_encode($retval);
    }

    public function removeItem($id){
        // 权限检查
        $counts = Db::name('items')
            ->where('id', $id)
            ->delete();
        // dump($counts);
        if($counts > 0){
            $retval = array("state" => "success", "rows" => $counts);
        }else{
            $retval = array("state" => "error", "errorMsg" => "Something is wrong");
        }
        return json_encode($retval);
    }
}

?>
