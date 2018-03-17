<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index
{
    public function index(){
        $view = new View();
        $view->assign('title', '吾爱吾庐');
        $view->fetch('index');
    }
}
