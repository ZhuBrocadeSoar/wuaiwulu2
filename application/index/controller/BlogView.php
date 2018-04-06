<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use app\index\model\Blog;
use app\index\model\Comment;
use app\index\model\User;

class BlogView extends \think\Controller{

    private $values = array(
        'title' => '',
        'blog_title' => '',
        'blog_date' => '',
        'markdown_content' => '',
    );

    private $values0 = array(
        'title' => '-博客',
        'blog_title' => '博客',
        'blog_subtitle' => '原创、转载、分享  |  随笔、日志、文稿',
        'subhead_1' => '置顶',
        'post_url_1_1' => '',
        'post_title_1_1' =>'',
        'post_date_1_1' => '',
        'post_description_1_1' => '',
        'subhead_2' => '时间轴',
        'post_url_2_1' => '',
        'post_title_2_1' =>'',
        'post_date_2_1' => '',
        'post_description_2_1' => '',
        'post_url_2_2' => '',
        'post_title_2_2' =>'',
        'post_date_2_2' => '',
        'post_description_2_2' => '',
        'post_url_2_3' => '',
        'post_title_2_3' =>'',
        'post_date_2_3' => '',
        'post_description_2_3' => '',
        'post_url_2_4' => '',
        'post_title_2_4' =>'',
        'post_date_2_4' => '',
        'post_description_2_4' => '',
        'post_url_2_5' => '',
        'post_title_2_5' =>'',
        'post_date_2_5' => '',
        'post_description_2_5' => '',
        'options_num' => 5,
        'current_page' => 1,
    );

    private function isARightSha1($num, $hash){
        for($i = 1; $i <= $num; $i++){
            if(sha1("$i") == $hash){
                return true;
            }
        }
        return false;
    }

    public function index(){
        Session::has('check');
        $count = Blog::count();
        $options_num = ($count - $count % 5) / 5 + (($count % 5)?1:0);
        $this->values0['options_num'] = $options_num;
        if(!Request::instance()->has('to_page') || !BlogView::isARightSha1($options_num, Request::instance()->param('to_page'))){
            // 共$options_num页的第1页的条目回显
            // $rows = new Blog;
            $blogs = new Blog;
            $result = $blogs->order('id', 'desc')
                ->field('id')
                ->limit(0, 5)
                ->select();
            $titleLabels = array('post_title_2_1', 'post_title_2_2', 'post_title_2_3', 'post_title_2_4', 'post_title_2_5');
            $urlLabels = array('post_url_2_1', 'post_url_2_2', 'post_url_2_3', 'post_url_2_4', 'post_url_2_5');
            $dateLabels = array('post_date_2_1', 'post_date_2_2', 'post_date_2_3', 'post_date_2_4', 'post_date_2_5');
            $absLabels = array('post_description_2_1', 'post_description_2_2', 'post_description_2_3', 'post_description_2_4', 'post_description_2_5');
            foreach($result as $key => $data){
                $iiiddd = $data->id;
                $this->values0[$titleLabels[$key]] = '[' . Blog::get($iiiddd)->tag1 . ']' . '-' . Blog::get($iiiddd)->title_view;
                $this->values0[$urlLabels[$key]] = Blog::get($iiiddd)->url;
                $this->values0[$dateLabels[$key]] = Blog::get($iiiddd)->create_time;
                $this->values0[$absLabels[$key]] = Blog::get($iiiddd)->abstract;
            }
            $this->assign($this->values0);
            return $this->fetch('blog');
        }else{
            $to_page = Request::instance()->param('to_page');
            for($i = 1; $i <= $options_num; $i++){
                if($to_page == sha1("$i")){
                    $to_page = $i;
                    break;
                }
            }
            $this->values0['title'] = '-博客-第' . $to_page . '页';
            $this->values0['current_page'] = $to_page;
            // 共$options_num页的第$to_page页的条目回显
            $blogs = new Blog;
            $result = $blogs->order('id', 'desc')
                ->field('id')
                ->limit(($to_page - 1) * 5, 5)
                ->select();
            $titleLabels = array('post_title_2_1', 'post_title_2_2', 'post_title_2_3', 'post_title_2_4', 'post_title_2_5');
            $urlLabels = array('post_url_2_1', 'post_url_2_2', 'post_url_2_3', 'post_url_2_4', 'post_url_2_5');
            $dateLabels = array('post_date_2_1', 'post_date_2_2', 'post_date_2_3', 'post_date_2_4', 'post_date_2_5');
            $absLabels = array('post_description_2_1', 'post_description_2_2', 'post_description_2_3', 'post_description_2_4', 'post_description_2_5');
            foreach($result as $key => $data){
                $iiiddd = $data->id;
                $this->values0[$titleLabels[$key]] = '[' . Blog::get($iiiddd)->tag1 . ']' . '-' . Blog::get($iiiddd)->title_view;
                $this->values0[$urlLabels[$key]] = Blog::get($iiiddd)->url;
                $this->values0[$dateLabels[$key]] = Blog::get($iiiddd)->create_time;
                $this->values0[$absLabels[$key]] = Blog::get($iiiddd)->abstract;
            }
            return json_encode($this->values0);
        }
    }

    public function art($date, $title){
        $markdown = new \Parsedown();
        $aBlog = Blog::get([
            'date_route' => $date,
            'title_route' => $title,
        ]);
        if($aBlog == NULL){
            // 404
        }else{
            $id = $aBlog->id;
            $this->values['title'] = '-' . $title;
            $this->values['blog_title'] = '[' . $aBlog->tag1 . ']-' . $aBlog->title_view;
            $this->values['blog_date'] = $aBlog->create_time;
            $this->values['markdown_content'] = "\n" . $aBlog->content . "\n";
            $this->values['date_route'] = $date;
            $this->values['title_route'] = $title;
            if(Admin::isAdmin()){
                $this->values['removeHidden'] = 'true';
            }else{
                $this->values['removeHidden'] = 'false';
            }
            $prev_id = Blog::get($id)->prev_id;
            $next_id = Blog::get($id)->next_id;
            if($prev_id == 0){
                // 没有了
                $this->values['url_prev'] = '';
                $this->values['title_prev'] = '';
            }else{
                $this->values['url_prev'] = Blog::get($prev_id)->url;
                $this->values['title_prev'] = Blog::get($prev_id)->title_view;
            }
            if($next_id == 0){
                // 没有了
                $this->values['url_next'] = '';
                $this->values['title_next'] = '';
            }else{
                $this->values['url_next'] = Blog::get($next_id)->url;
                $this->values['title_next'] = Blog::get($next_id)->title_view;
            }
            $identicon = new \Identicon\Identicon();
            $this->values['identicon_uri'] = $identicon->getImageDataUri('brocadesoar@163.com'); 
            // 渲染评论表volist 的值
            $fatherCommentId = $aBlog->comment_id;
            if(($fatherCommentId == NULL) || ($fatherCommentId == 0)){
                // 没有评论
                $identicon = new \Identicon\Identicon();
                $identicon_uri = $identicon->getImageDataUri('brocadesoar@163.com');
                $comment_list = [
                    [
                        'level' => '0',
                        'nick' => '吾爱吾庐',
                        'site' => NULL,
                        'datetime' => $aBlog->create_time,
                        'comment' => "\n" . '暂无评论' . "\n",
                        'identicon_uri' => $identicon_uri,
                        'admin' => 'yes',
                        'comment_id' => '0',
                        'reply_list' => BlogView::getReplyList(0),
                    ],
                ];
            }else{
                $fatherComment = Comment::get($fatherCommentId);
                $user = User::get($fatherComment->user_id);
                $identicon = new \Identicon\Identicon();
                $identicon_uri = $identicon->getImageDataUri($user->mail);
                $comment_list = [
                    [
                        'level' => $fatherComment->level,
                        'nick' => $user->nick,
                        'site' => $user->site,
                        'datetime' => $fatherComment->create_time,
                        'comment' =>"\n" . $fatherComment->content . "\n",
                        'identicon_uri' => $identicon_uri,
                        'admin' => (($fatherComment->admin_mark)?'yes':NULL),
                        'comment_id' => $fatherCommentId,
                        'reply_list' => BlogView::getReplyList($fatherComment->reply_id),
                    ],
                ];
                while(($fatherComment->son_id != NULL) && ($fatherComment->son_id != 0)){
                    $fatherCommentId = $fatherComment->son_id;
                    $fatherComment = Comment::get($fatherCommentId);
                    $user = User::get($fatherComment->user_id);
                    $identicon_uri = $identicon->getImageDataUri($user->mail);
                    $comment_list[] = [
                        'level' => $fatherComment->level,
                        'nick' => $user->nick,
                        'site' => $user->site,
                        'datetime' => $fatherComment->create_time,
                        'comment' => "\n" . $fatherComment->content . "\n",
                        'identicon_uri' => $identicon_uri, 
                        'admin' => (($fatherComment->admin_mark)?'yes':NULL),
                        'comment_id' => $fatherCommentId,
                        'reply_list' => BlogView::getReplyList($fatherComment->reply_id),
                    ];
                }
            }
            $this->values['comment_list'] = $comment_list;
            $this->assign($this->values);
            return $this->fetch('blog_');
        }
    }

    public static function getReplyList($reply_id){
        if($reply_id == 0){
            return NULL;
        }else{
            $fatherReply = Comment::get($reply_id);
            $user = User::get($fatherReply->user_id);
            $identicon = new \Identicon\Identicon();
            $identicon_uri = $identicon->getImageDataUri($user->mail);
            $reply_list = [
                [
                    'level' => $fatherReply->level,
                    'nick' => $user->nick,
                    'site' => $user->site,
                    'datetime' => $fatherReply->create_time,
                    'comment' => "\n" . $fatherReply->content . "\n",
                    'identicon_uri' => $identicon_uri,
                    'admin' => (($fatherReply->admin_mark)?'yes':NULL),
                    'reply_id' => $fatherReply->id,
                ]
            ];
            while(($fatherReply->son_id != NULL) && ($fatherReply->son_id != 0)){
                $fatherReplyId = $fatherReply->son_id;
                $fatherReply = Comment::get($fatherReplyId);
                $user = User::get($fatherReply->user_id);
                $identicon_uri = $identicon->getImageDataUri($user->mail);
                $reply_list[] = [
                    'level' => $fatherReply->level,
                    'nick' => $user->nick,
                    'site' => $user->site,
                    'datetime' => $fatherReply->create_time,
                    'comment' => "\n" . $fatherReply->content . "\n",
                    'identicon_uri' => $identicon_uri,
                    'admin' => (($fatherReply->admin_mark)?'yes':NULL),
                    'reply_id' => $fatherReply->id,
                ];
            }
            return $reply_list;
        }
    }

    public function comment(){
        if(Session::has('captcha_status') && (Session::get('captcha_status') == 'success')){
            Session::set('captcha_status', 'reset');
            $thisBlog = Blog::get([
                'date_route' => Request::instance()->param('date_route'),
                'title_route' => Request::instance()->param('title_route'),
            ]);
            if($thisBlog == NULL){
                // something wrong
                return json_encode([
                    'errMsg' => 'Blog Route Error',
                ]);
            }else{
                if(!$thisBlog->comment_enable){
                    // comment closed
                    return json_encode([
                        'errMsg' => 'Comment Closed',
                    ]);
                }else{
                    $aUser = User::get([
                        'mail' => Request::instance()->param('email_in'),
                        'ip' => Request::instance()->server('REMOTE_ADDR'),
                    ]);
                    if($aUser == NULL){
                        // no user marked with this email and this ip
                        $aUser = new User;
                        $aUser->mail = Request::instance()->param('email_in');
                        $aUser->nick = Request::instance()->param('nick_in');
                        $site = Request::instance()->param('site_in');
                        if($site != ''){
                            $aUser->site = $site;
                        }
                        $aUser->ip = Request::instance()->server('REMOTE_ADDR');
                        $aUser->isUpdate(false)->save();
                        $uid = $aUser->id;
                    }else{
                        // a user has this email and this ip
                        $uid = $aUser->id;
                    }
                    $aNewComment = new Comment;
                    $aNewComment->user_id = $uid;
                    $aNewComment->content = htmlspecialchars_decode(Request::instance()->param('comment_in'));
                    if(Admin::isAdmin()){
                        $aNewComment->admin_mark = true;
                    }else{
                        $aNewComment->admin_mark = false;
                    }
                    $aNewComment->isUpdate(false)->save();
                    // 处理父对象子对象问题
                    if(($thisBlog->comment_id == NULL) || ($thisBlog->comment_id == 0)){
                        // 文章的第一个评论
                        $thisBlog->comment_id = $aNewComment->id;
                        $thisBlog->isUpdate(true)->save();
                        $aNewComment->father_id = 0;
                        $aNewComment->level = 1;
                        $aNewComment->reply_id = 0;
                        $aNewComment->isUpdate(true)->save();
                    }else{
                        // 非第一个评论
                        // 找到父评论
                        $fatherCommentId = $thisBlog->comment_id;
                        $fatherComment = Comment::get($fatherCommentId);
                        while(($fatherComment->son_id != NULL) && ($fatherComment->son_id != 0)){
                            $fatherCommentId = $fatherComment->son_id;
                            $fatherComment = Comment::get($fatherCommentId);
                        }
                        $fatherComment->son_id = $aNewComment->id;
                        $fatherComment->isUpdate(true)->save();
                        $aNewComment->father_id = $fatherCommentId;
                        $aNewComment->level = $fatherComment->level + 1;
                        $aNewComment->reply_id = $fatherComment->reply_id;
                        $aNewComment->isUpdate(true)->save();
                    }
                    Admin::noteMe(Request::instance()->param('date_route'),
                        Request::instance()->param('title_route'),
                        $aNewComment->level
                    );
                    return json_encode([
                        'success' => 'done',
                        // 'level' => "$aNewComment->level",
                    ]);
                }
            }
        }else{
            return json_encode([
                'errMsg' => 'Captcha Error',
            ]);
        }
    }

    public function reply(){
        if(Session::has('captcha_status') && (Session::get('captcha_status') == 'success')){
            Session::set('captcha_status', 'reset');
            $thisBlog = Blog::get([
                'date_route' => Request::instance()->param('date_route'),
                'title_route' => Request::instance()->param('title_route'),
            ]);
            if($thisBlog == NULL){
                // something wrong
                return json_encode([
                    'errMsg' => 'Blog Route Error',
                ]);
            }else{
                if(!$thisBlog->comment_enable){
                    // 不可评论
                    return json_encode([
                        'errMsg' => 'Comment Closed',
                    ]);
                }else{
                    $aUser = User::get([
                        'mail' => Request::instance()->param('email_in_reply'),
                        'ip' => Request::instance()->param('REMOTE_ADDR'),
                    ]);
                    if($aUser == NULL){
                        $aUser = new User;
                        $aUser->mail = Request::instance()->param('email_in_reply');
                        $aUser->nick = Request::instance()->param('nick_in_reply');
                        $site = Request::instance()->param('site_in_reply');
                        if($site != ''){
                            $aUser->site = $site;
                        }
                        $aUser->ip = Request::instance()->server('REMOTE_ADDR');
                        $aUser->isUpdate(false)->save();
                        $uid = $aUser->id;
                    }else{
                        $uid = $aUser->id;
                    }
                    $aNewReply = new Comment;
                    $aNewReply->user_id = $uid;
                    $aNewReply->content = htmlspecialchars_decode(Request::instance()->param('comment_in_reply'));
                    if(Admin::isAdmin()){
                        $aNewReply->admin_mark = true;
                    }else{
                        $aNewReply->admin_mark = false;
                    }
                    $aNewReply->isUpdate(false)->save();
                    $theComment = Comment::get(Request::instance()->param('comment_id_reply'));
                    if($theComment->reply_id == 0){
                        // 评论的第一个回复
                        $theComment->reply_id = $aNewReply->id;
                        $theComment->isUpdate(true)->save();
                        $aNewReply->father_id = 0;
                        $aNewReply->level = 1;
                        $aNewReply->reply_id = 0;
                        $aNewReply->isUpdate(true)->save();
                    }else{
                        // 非第一个回复
                        $fatherReplyId = $theComment->reply_id;
                        $fatherReply = Comment::get($fatherReplyId);
                        while($fatherReply->son_id != NULL){
                            $fatherReplyId = $fatherReply->son_id;
                            $fatherReply = Comment::get($fatherReplyId);
                        }
                        $fatherReply->son_id = $aNewReply->id;
                        $fatherReply->isUpdate(true)->save();
                        $aNewReply->father_id = $fatherReplyId;
                        $aNewReply->level = $fatherReply->level + 1;
                        $aNewReply->reply_id = $fatherReply->reply_id;
                        $aNewReply->isUpdate(true)->save();
                    }
                    Admin::noteMe(Request::instance()->param('date_route'),
                        Request::instance()->param('title_route'),
                        $theComment->level
                    );
                    return json_encode([
                        'success' => 'done',
                    ]);
                }
            }
        }else{
            return json_encode([
                'errMsg' => 'Captcha Error',
            ]);
        }
    }

    public function removeComment(){
        if(Admin::isAdmin()){
            $theComment = Comment::get(Request::instance()->param('comment_id_remove'));
            if($theComment != NULL){
                if($theComment->father_id != 0){
                    // 不需操作blog
                    if($theComment->son_id != 0){
                        // 需要操作son(不是第一条也不是最后一条评论)
                        $fatherComment = Comment::get($theComment->father_id);
                        $sonComment = Comment::get($theComment->son_id);
                        $fatherComment->son_id = $sonComment->id;
                        $sonComment->father_id = $fatherComment->id;
                        $fatherComment->isUpdate(true)->save();
                        $sonComment->isUpdate(true)->save();
                        $theComment->delete();
                    }else{
                        // 不需操作son(不是第一条评论是最后一条评论)
                        $fatherComment = Comment::get($theComment->father_id);
                        $fatherComment->son_id = 0;
                        $fatherComment->isUpdate(true)->save();
                        $theComment->delete();
                    }
                }else{
                    // 需要操作blog
                    $theBlog = Blog::get([
                        'date_route' => Request::instance()->param('date_route'),
                        'title_route' => Request::instance()->param('title_route'),
                    ]);
                    if($theComment->son_id != 0){
                        // 需要操作son(是第一条评论不是最后一条评论)
                        $sonComment = Comment::get($theComment->son_id);
                        $theBlog->comment_id = $sonComment->id;
                        $sonComment->father_id = 0;
                        $theBlog->isUpdate(true)->save();
                        $sonComment->isUpdate(true)->save();
                        $theComment->delete();
                    }else{
                        // 不需操作son(是第一条评论也是最后一条评论)
                        $theBlog->comment_id = 0;
                        $theBlog->isUpdate(true)->save();
                        $theComment->delete();
                    }
                }
                return json_encode([
                    'success' => 'done',
                ]);
            }else{
                return json_encode([
                    'errMsg' => 'Comment Id Error',
                ]);
            }
        }else{
            return json_encode([
                'errMsg' => 'Permition Denide',
            ]);
        }
    }

    public function removeReply(){
        if(Admin::isAdmin()){
            $theReply = Comment::get(Request::instance()->param('reply_id_remove'));
            if($theReply != NULL){
                if($theReply->father_id != 0){
                    // 不需要操作comment
                    if($theReply->son_id != 0){
                        // 不需要操作son(不是第一条回复也不是最后一条回复)
                        $fatherReply = Comment::get($theReply->father_id);
                        $sonReply = Comment::get($theReply->son_id);
                        $fatherReply->son_id = $sonReply->id;
                        $sonReply->father_id = $fatherReply->id;
                        $fatherReply->isUpdate(true)->save();
                        $sonReply->isUpdate(true)->save();
                        $theReply->delete();
                    }else{
                        // 需要操作son(不是第一条回复是最后一条回复)
                        $fatherReply = Comment::get($theReply->father_id);
                        $fatherReply->son_id = 0;
                        $fatherReply->isUpdate(true)->save();
                        $theReply->delete();
                    }
                }else{
                    // 需要操作comment
                    $theComment = Comment::get(Request::instance()->param('comment_id_remove'));
                    if($theReply->son_id != 0){
                        // 需要操作son(是第一条回复不是最后一条回复)
                        $sonReply = Comment::get($theReply->son_id);
                        $theComment->reply_id = $sonReply->id;
                        $sonReply->father_id = 0;
                        $theComment->isUpdate(true)->save();
                        $sonReply->isUpdate(true)->save();
                        $theReply->delete();
                    }else{
                        // 不需要操作son(是第一条回复时最后一条回复)
                        $theComment->reply_id = 0;
                        $theComment->isUpdate(true)->save();
                        $theReply->delete();
                    }
                }
                return json_encode([
                    'success' => 'done',
                ]);
            }else{
                return json_encode([
                    'errMsg' => 'Reply Id Error',
                ]);
            }
        }else{
            return json_encode([
                'errMsg' => 'Permition Denide',
            ]);
        }
    }

}

?>
