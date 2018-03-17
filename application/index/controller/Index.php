<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index extends \think\Controller{
    public function index(){
        $this->assign('title', '吾爱吾庐');
        return $this->fetch('index');
    }
}
