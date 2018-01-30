<?php
namespace app\index\controller;

use think\Db;

class Index
{
    public function index($name = 'thinkphp')
    {
        $list = Db::table('user')
            ->where('user_index', '1')
            ->select();
        dump($list['user_date']);
        return 'hello ' . $name;
    }
}
