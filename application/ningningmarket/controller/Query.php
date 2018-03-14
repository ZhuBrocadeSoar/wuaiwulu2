<?php
namespace app\ningningmarket\controller;

use think\Db;

class Query{
    private function base64FieldsConv(&$oneRow){
        $oneRow["name"] = base64_decode($oneRow["name"]);
        $oneRow["price"] = base64_decode($oneRow["price"]);
    }

    public function items(){
        $rows = Db::name('items')
            ->select();
        foreach($rows as $val){
            Query::base64FieldsConv($val);
        }
        dump($rows);
    }

    public function item($id){
        $row = Db::name('items')
            ->where('id', $id)
            ->select();
        Query::base64FieldsConv($row[0]);
        dump($row);
    }

    public function registerItem($name, $price, $stock){
        $datas = array('name' => base64_encode($name), 
            'price' => base64_encode($price), 
            'stock' => $stock,
            'register_time_stamp' => date('Y-m-d H:i:s', time())
        );
        $counts = Db::name('items')->insert($datas);
        dump($counts);
    }

    public function removeItem($id){
        $counts = Db::name('items')
            ->where('id', $id)
            ->delete();
        dump($counts);
    }
}

?>
