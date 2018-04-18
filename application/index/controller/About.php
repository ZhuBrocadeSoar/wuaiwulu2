<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class About extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-传送门',
        ]);
        return $this->fetch('about');
    }

    public function wangming(){
        return $this->fetch('wangming');
    }
}

?>
