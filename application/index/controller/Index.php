<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index extends \think\controller{
    public function index(){
        $this->assign('title', '吾爱吾庐');
        $this->fetch('index');
    }
}
