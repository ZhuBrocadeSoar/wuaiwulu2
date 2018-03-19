<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Blog extends \think\Controller{
    public function index(){
        if(!Request::instance()->has('to_page')){
            $this->assign([
                'title' => '-博客',
                'blog_title' => '博客',
                'blog_subtitle' => '原创、转载、分享  |  随笔、日志、文稿',
                'subhead_1' => '置顶',
                'post_url_1_1' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_1_1' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_1_1' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_1_1' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'subhead_2' => '时间轴',
                'post_url_2_1' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_1' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_1' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_1' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_2' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_2' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_2' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_2' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_3' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_3' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_3' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_3' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_4' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_4' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_4' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_4' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_5' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_5' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_5' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_5' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'options_num' => 5,
                'current_page' => 3,
            ]);
            return $this->fetch('blog');
        }else{
            $to_page = Request::instance()->param('to_page');
            return json_encode([
                'title' => '-博客-第' . $toPage . '页',
                'blog_title' => '博客',
                'blog_subtitle' => '原创、转载、分享  |  随笔、日志、文稿',
                'subhead_1' => '置顶',
                'post_url_1_1' => '/blog/2018-03-18/log-testtesttest',
                'post_title_1_1' => '[test]-dfdfdf',
                'post_date_1_1' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_1_1' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'subhead_2' => '时间轴',
                'post_url_2_1' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_1' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_1' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_1' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_2' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_2' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_2' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_2' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_3' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_3' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_3' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_3' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_4' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_4' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_4' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_4' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'post_url_2_5' => '/blog/2018-03-18/log-ofwuaiwulu2',
                'post_title_2_5' => '[日志]-吾爱吾庐网站开发日志',
                'post_date_2_5' => 'Sun 18 Mar 2018 13:27:05',
                'post_description_2_5' => 'php/html/css/js这些东西总要先有个大概的认识，给自己定一个目标，比如给自己建立一个博客站，这既是对自己技术的锻炼也作为一份优秀的简历展示自己的想法和梦',
                'options_num' => 5,
                'current_page' => $to_page,
            ]);
        }
    }

    public function art($date, $title){
        $markdown = new \Parsedown();
        $this->assign([
            'title' => '-博客-' . $title,
            'blog_title' => '[日志]-吾爱吾庐网站开发日志',
            'blog_date' => 'Sun 18 Mar 2018 13:27:05',
            'markdown_content' => $markdown->text('

            ![logo](https://brocadesoar.cn/images/biglogo.png)

            ## 该站使用了***尽量少***的js，但是会大量运用php类库

            ```C++

            #include<stdio.h>

            int main(void){

            printf("hello world!\n");

            return 0;

    }
    ```
        '),
        ]);
    return $this->fetch('blog_');
    }
}

?>
