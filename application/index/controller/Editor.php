<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;

class Editor extends \think\Controller{
    private $values = array(
        'title' => '-编辑器',
        'absInputInit' => 'none',
        'conInputInit' => 'none',
        'menu_selected' => '',
    );
    public function index(){
        $this->assign($values);
        return $this->fetch('editor');

    }

    public function new(){
        $values['menu_selected'] = 'pure-menu-selected';
        $this->assign($values);
        return Editor::index();
    }

}

?>
