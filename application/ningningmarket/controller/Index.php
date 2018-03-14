<?php
namespace app\ningningmarket\controller;

use think\Db;

class Index{
    public function index(){
        $retval = json_encode(array("function" => "index", "data" => "this is a default function", "timeStamp" => time()));
        return $retval;
    }

    public function hello($msg = 'hello'){
        $retval = json_encode(array("function" => "hello", "data" => array("state" => "this is your hello msg", "msg" => $msg), "timeStamp" => time()));
        return $retval;
    }
}

?>
