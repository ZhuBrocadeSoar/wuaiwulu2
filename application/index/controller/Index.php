<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '',
            'tagline' => '仰望星空：<br />创新源于模仿，成功源于实践。',
        ]);
        return $this->fetch('index');
    }
}
