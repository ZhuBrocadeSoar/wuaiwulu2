<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Index
{
    public function index(){
        return $this->fetch();
    }
}
