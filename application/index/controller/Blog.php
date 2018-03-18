<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Blog extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-博客',
            'blog_title' => '博客',
            'blog_subtitle' => '原创、转载、分享|随笔、日志、文稿',
            'subhead_1' => '置顶',
            'subhead_2' => '时间轴',
        ]);
        return $this->fetch('blog');
    }

    public function art($date, $title){
        $this->assign([
            'title' => '-博客-' . $title,
        ]);
        return $this->fetch('blog');
    }
}

?>
