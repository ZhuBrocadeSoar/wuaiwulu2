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
            'blog_subtitle' => '原创、转载、分享  |  随笔、日志、文稿',
            'subhead_1' => '置顶',
            'post_title_1_1' => '[日志]-吾爱吾庐网站开发日志',
            'post_date_1_1' => 'Sun Mar 18 13:27:05 GMT 2018',
            'post_description_1_1' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
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
