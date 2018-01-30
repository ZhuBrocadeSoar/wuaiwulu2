<?php
namespace app\index\controller;

use think\Db;

class Index
{
    public function index($name = 'thinkphp')
    {
        $list = Db::table('user')
            ->where('user_index', '1')
            ->select('user_index', 'user_date', 'user_email');
        dump($list);
        return 'hello ' . $name;
    }
}
