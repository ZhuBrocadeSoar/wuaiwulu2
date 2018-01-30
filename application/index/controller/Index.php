<?php
namespace app\index\controller;

use think\Db;

class Index
{
    public function index($name = 'thinkphp')
    {
        $list = Db::table('user')
            ->select();
        dump($list);
        return 'hello ' . $name;
    }
}
