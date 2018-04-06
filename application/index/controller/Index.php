<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use app\index\model\Home;

class Index extends \think\Controller{
    public function index(){
        $home = Home::get(Home::max('id'));
        $this->assign([
            'title' => '',
            'tagline' => $home->tagline,
            'short_art_1_title' => $home->art1_title,
            'short_art_1_content' => $home->art1_content,
            'short_art_2_title' => $home->art2_title,
            'short_art_2_content' => $home->art2_content,
            'short_art_3_title' => $home->art3_title,
            'short_art_3_content' => $home->art3_content,
            'short_art_4_title' => $home->art4_title,
            'short_art_4_content' => $home->art4_content,
        ]);
        return $this->fetch('index');
    }
}
