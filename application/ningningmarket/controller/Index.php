<?php
namespace app\ningningmarket\controller;

use think\Db;

class Index{
    public function index($helloMsg = 'there is ningningmarket'){
        $retval = json_encode(array("helloMsg" => $helloMsg, "timeStamp" => time()));
        return $retval;
    }
}

?>
