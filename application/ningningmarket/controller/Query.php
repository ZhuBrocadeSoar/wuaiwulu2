<?php
namespace app\ningningmarket\controller;

use think\Db;

class Query{
    public function item($id){
        $row = Db::name('items')
            ->where('id', $id)
            ->select();
        $row[0]["name"] = base64_decode($row[0]["name"]);
        $row[0]["price"] = base64_decode($row[0]["price"]);
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
}

?>