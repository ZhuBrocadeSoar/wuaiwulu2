<?php
namespace app\index\controller;

use think\Db;

class Index
{
    public function index($name = 'thinkphp')
    {
        $list = Db::table('user')
            ->field('user_index, user_date')
            ->where('user_index', '1')
            ->select();
        dump($list);
        return 'hello ' . $name;
    }
}
