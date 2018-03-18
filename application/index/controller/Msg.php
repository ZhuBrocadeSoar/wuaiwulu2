<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Msg extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-即时消息',
        ]);
        return $this->fetch('msg');
    }
}

?>
