<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '吾爱吾庐',
            'blog_selected' => 'pure-menu-selected',
            'bbs_selected' => '',
            'msg_selected' => '',
            'about_selected' => '',
        ]);
        return $this->fetch('index');
    }
}
