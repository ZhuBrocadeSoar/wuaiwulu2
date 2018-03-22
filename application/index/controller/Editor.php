<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;

class Editor extends \think\Controller{
    public function index(){
        $this->assign([
            'title' => '-编辑器',
            'adsInputInit' => 'none',
            'conInputInit' => 'none',
        ]);
        return $this->render('editor');

    }

}

?>
