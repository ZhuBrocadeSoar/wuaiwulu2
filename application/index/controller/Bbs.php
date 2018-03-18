<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Bbs extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-留言',
        ]);
        return $this->fetch('bbs');
    }
}

?>
