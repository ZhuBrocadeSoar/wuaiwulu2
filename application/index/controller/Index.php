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
            'short_art_1_title' => '写在首页',
            'short_art_1_content' => '
            早在2016年本科二年级的时候就做过这样的梦，我要建立一个自己的博客网站，也正是那一年，多亏参与了创客空间协会，勇敢地接触了VPS，从那时起，我有了自己的服务器，开始频繁地做起梦来。2017年二、三年级，朋友拉我做小程序，我鼓起勇气说，我试试，我用PHP写小程序后台代码。2018年3月正是当下，回想起前两年——初识PHP、多次尝试开发博客半途而废、学习ThinkPHP框架、找到Amaze UI前台框架、偶遇Purecss。从懵懂无知到一知半解，这一次的建站思路比以往任何一次都要清晰。这一次我能成功吗？创新源于模仿，成功源于实践。

',
        ]);
        return $this->fetch('index');
    }
}
