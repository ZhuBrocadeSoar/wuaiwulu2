<?php
namespace app\ningningmarket\controller;

use think\Db;

class Query{
    public function item($id){
        $row = Db::table('items')
            ->where('id', $id)
            ->select();
        dump($row);
    }

    public function registerItem($name, $price, $stock){
        $datas = array('name' => $name, 
            'price' => $price, 
            'stock' => $stock,
            'register_time_stamp' => time()
        );
        $counts = Db::name('items')->insert($datas);
        dump($counts);
    }
}

?>
