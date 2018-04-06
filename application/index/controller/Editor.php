<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;
use app\index\controller;
use app\index\model\Blog;
use app\index\model\Home;

class Editor extends \think\Controller{

    private $values = array(
        'title' => '-编辑器',
        'absInputInit' => ' ',
        'conInputInit' => ' ',
        'menu_selected' => '',
        'title_route' => '',
        'title_view' => '',
        'tag1' => '',
        'abs' => '',
        'comment_enable' => 'false',
        'disable' => 'false',
        'two_level' => '',
        'content' => '',
        'new_or_update' => 'new',
        'date_route' => '',
    );

    public function index(){
        if(Admin::isAdmin()){
            $this->assign($this->values);
            return $this->fetch('editor');
        }else{
            return $this->fetch('404');
        }
    }

    public function new(){
        if(Admin::isAdmin()){
            $this->values['menu_selected'] = 'pure-menu-selected';
            $this->assign($this->values);
            return Editor::index();
        }else{
            return $this->fetch('404');
        }
    }

    public function newSave(){
        if(Admin::isAdmin()){
            $date_route = date('Y-m-d', time());
            $title_route = Request::instance()->param('title_route_in');
            $check = Blog::get([
                'title_route' => $title_route,
                'date_route' => $date_route,
            ]);
            if($check == NULL){
                $aNewBlog = new Blog;
                $aNewBlog->date_route = $date_route;
                $aNewBlog->title_route = $title_route;
                $aNewBlog->title_view = Request::instance()->param('title_view_in');
                $aNewBlog->tag1 = Request::instance()->param('tag1_in');
                $aNewBlog->abstract = Request::instance()->param('abs_in');
                $aNewBlog->content = htmlspecialchars_decode(Request::instance()->param('content_in'));
                $aNewBlog->comment_enable = Request::instance()->param('comment_enable_in');
                $retval = $aNewBlog->save();
                $url = 'https://brocadesoar.cn/blog/' . $date_route . '/' . $title_route;
                return json_encode([
                    'success' => $retval,
                    'url' => $url,
                ]);
            }else{
                return json_encode([
                    'errMsg' => 'Check Error',
                ]);
            }
        }else{
            // 没有权限
            return json_encode([
                'errMsg' => 'Permition Denided',
            ]);
        }
    }

    public function update($date_route, $title_route){
        if(Admin::isAdmin()){
            // render
            $aBlog = Blog::get([
                'date_route' => $date_route,
                'title_route' => $title_route,
            ]);
            $this->values['title_route'] = $aBlog->title_route;
            $this->values['title_view'] = $aBlog->title_view;
            $this->values['tag1'] = $aBlog->tag1;
            $this->values['abs'] = $aBlog->abstract;
            $this->values['disable'] = true;
            $this->assign($this->values);
            $this->values['menu_selected'] = 'pure-menu-selected';
            $this->values['two_level'] = '../../';
            $this->values['content'] = $aBlog->content;
            $this->values['new_or_update'] = 'update';
            $this->values['date_route'] = $date_route;
            $this->values['comment_enable'] = $aBlog->comment_enable;
            return Editor::index();
        }else{
            // 404
            $this->fetch('404');
        }
    }

    public function updateSave(){
        if(Admin::isAdmin()){
            $date_route = Request::instance()->param('date_route_in');
            $title_route = Request::instance()->param('title_route_in');
            $check = Blog::get([
                'title_route' => $title_route,
                'date_route' => $date_route,
            ]);
            if($check){
                $aNewBlog = Blog::get([
                    'date_route' => $date_route,
                    'title_route' => $title_route,
                ]);
                $aNewBlog->title_view = Request::instance()->param('title_view_in');
                $aNewBlog->tag1 = Request::instance()->param('tag1_in');
                $aNewBlog->abstract = Request::instance()->param('abs_in');
                $aNewBlog->content = htmlspecialchars_decode(Request::instance()->param('content_in'));
                $aNewBlog->comment_enable = (Request::instance()->param('comment_enable_in') == "true")?true:false;
                $retval = $aNewBlog->isUpdate(true)->save();
                $url = 'https://brocadesoar.cn/blog/' . $date_route . '/' . $title_route;
                return json_encode([
                    'success' => $retval,
                    'url' => $url,
                ]);
            }else{
                return json_encode([
                    'errMsg' => 'Check Error',
                ]);
            }
        }else{
            // 没有权限
            return json_encode([
                'errMsg' => 'Permition Denided',
            ]);
        }
    }

    public function home(){
        $values = [
            'title' => '-修改首页',
            'menu_selected' => 'pure-menu-selected',
            'tagline' => '',
            'art1_title' => '',
            'art1_content' => '',
            'art2_title' => '',
            'art2_content' => '',
            'art3_title' => '',
            'art3_content' => '',
            'art4_title' => '',
            'art4_content' => '',
        ];
        $aHomePage = Home::get(Home::max('id'));
        if($aHomePage != NULL){
            $values['tagline'] = $aHomePage->tagline;
            $values['art1_title'] = $aHomePage->art1_title;
            $values['art1_content'] = $aHomePage->art1_content;
            $values['art2_title'] = $aHomePage->art2_title;
            $values['art2_content'] = $aHomePage->art2_content;
            $values['art3_title'] = $aHomePage->art3_title;
            $values['art3_content'] = $aHomePage->art3_content;
            $values['art4_title'] = $aHomePage->art4_title;
            $values['art4_content'] = $aHomePage->art4_content;
        }
        $this->assign($values);
        return $this->fetch('home');
    }

    public function homeSave(){
        $aNewHome = new Home;
        $aNewHome->tagline = Request::instance()->param('tagline_in');
        $aNewHome->art1_title = Request::instance()->param('art1_title_in');
        $aNewHome->art1_content = Request::instance()->param('art1_content_in');
        $aNewHome->art2_title = Request::instance()->param('art2_title_in');
        $aNewHome->art2_content = Request::instance()->param('art2_content_in');
        $aNewHome->art3_title = Request::instance()->param('art3_title_in');
        $aNewHome->art3_content = Request::instance()->param('art3_content_in');
        $aNewHome->art4_title = Request::instance()->param('art4_title_in');
        $aNewHome->art4_content = Request::instance()->param('art4_content_in');
        $aNewHome->isUpdate(false)->save();
        return json_encode([
           'success' => 'done',
        ]);
    }

}

?>
