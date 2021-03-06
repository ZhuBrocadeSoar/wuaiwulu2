<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'blog/$' => 'index/blog_view/index',
    'blog/:to_page$' => 'index/blog_view/index',
    'blog/:date/:title$' => 'index/blog_view/art',
    'bbs$' => 'index/bbs_view/index',
    'msg$' => 'index/msg/index',
    'about$' => 'index/about/index',
    'admin$' => 'index/admin/index',

];
