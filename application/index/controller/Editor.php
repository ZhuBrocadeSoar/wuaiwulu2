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
            'absInputInit' => 'none',
            'conInputInit' => 'none',
        ]);
        return $this->fetch('editor');

    }

    public function new(){
        $this->assign([
            '$menu_selected' => 'pure-menu-selected',
        ]);
        return Editor::index();
    }

}

?>
