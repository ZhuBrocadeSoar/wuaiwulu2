<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '',
        ]);
        return $this->fetch('index');
    }
}
