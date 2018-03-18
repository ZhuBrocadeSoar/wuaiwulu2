<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Blog extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-博客',
        ]);
        return $this->fetch('blog');
    }
}

?>
