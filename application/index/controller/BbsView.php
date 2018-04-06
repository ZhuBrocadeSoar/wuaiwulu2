<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use app\index\model\User;
use app\index\model\Bbs;

class BbsView extends \think\Controller{

    private $values = array(
        'title' => '-留言板',
    );

    public function index(){
        Session::has('check');

        if(Admin::isAdmin()){
            $this->values['removeHidden'] = 'true';
        }else{
            $this->values['removeHidden'] = 'false';
        }
        // 渲染评论表volist 的值
        $fatherComment = Bbs::get([
            'father_id' => 0,
        ]);
        if(($fatherComment == NULL)/* || ($fatherCommentId == 0)*/){
            // 没有评论
            $identicon = new \Identicon\Identicon();
            $identicon_uri = $identicon->getImageDataUri('brocadesoar@163.com');
            $comment_list = [
                [
                    'level' => '0',
                    'nick' => '吾爱吾庐',
                    'site' => NULL,
                    'datetime' => '2018-04-01',
                    'comment' => "\n" . '暂无留言' . "\n",
                    'identicon_uri' => $identicon_uri,
                    'admin' => 'yes',
                    'comment_id' => '0',
                    'reply_list' => BbsView::getReplyList(0),
                ],
            ];
        }else{
            $fatherCommentId = $fatherComment->id;
            $fatherComment = Bbs::get($fatherCommentId);
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
                    'reply_list' => BbsView::getReplyList($fatherComment->reply_id),
                ],
            ];
            while(($fatherComment->son_id != NULL) && ($fatherComment->son_id != 0)){
                $fatherCommentId = $fatherComment->son_id;
                $fatherComment = Bbs::get($fatherCommentId);
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
                    'reply_list' => BbsView::getReplyList($fatherComment->reply_id),
                ];
            }
        }
        $this->values['comment_list'] = array_reverse($comment_list);

        $this->assign($this->values);
        return $this->fetch('bbs');
    }

    public static function getReplyList($reply_id){
        if($reply_id == 0){
            return NULL;
        }else{
            $fatherReply = Bbs::get($reply_id);
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
                $fatherReply = Bbs::get($fatherReplyId);
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
            if(false){
                // something wrong
                return json_encode([
                    'errMsg' => 'Blog Route Error',
                ]);
            }else{
                if(false){
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
                    $aNewComment = new Bbs;
                    $aNewComment->user_id = $uid;
                    $aNewComment->content = htmlspecialchars_decode(Request::instance()->param('comment_in'));
                    if(Admin::isAdmin()){
                        $aNewComment->admin_mark = true;
                    }else{
                        $aNewComment->admin_mark = false;
                    }
                    $aNewComment->isUpdate(false)->save();
                    // 处理父对象子对象问题
                    // if(($thisBlog->comment_id == NULL) || ($thisBlog->comment_id == 0)){
                    if((Bbs::where('father_id', 0)->count()) == 0){
                        // 第一个留言
                        $aNewComment->father_id = 0;
                        $aNewComment->level = 1;
                        $aNewComment->reply_id = 0;
                        $aNewComment->isUpdate(true)->save();
                    }else{
                        // 非第一个评论
                        // 找到父留言
                        $fatherComment = Bbs::get([
                            'father_id' => 0,
                        ]);
                        $fatherCommentId = $fatherComment->id;
                        while(($fatherComment->son_id != NULL) && ($fatherComment->son_id != 0)){
                            $fatherCommentId = $fatherComment->son_id;
                            $fatherComment = Bbs::get($fatherCommentId);
                        }
                        $fatherComment->son_id = $aNewComment->id;
                        $fatherComment->isUpdate(true)->save();
                        $aNewComment->father_id = $fatherCommentId;
                        $aNewComment->level = $fatherComment->level + 1;
                        $aNewComment->reply_id = $fatherComment->reply_id;
                        $aNewComment->isUpdate(true)->save();
                    }
                    Admin::bbsMe();
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
            if(false){
                // something wrong
                return json_encode([
                    'errMsg' => 'Blog Route Error',
                ]);
            }else{
                if(false){
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
                    $aNewReply = new Bbs;
                    $aNewReply->user_id = $uid;
                    $aNewReply->content = htmlspecialchars_decode(Request::instance()->param('comment_in_reply'));
                    if(Admin::isAdmin()){
                        $aNewReply->admin_mark = true;
                    }else{
                        $aNewReply->admin_mark = false;
                    }
                    $aNewReply->isUpdate(false)->save();
                    $theComment = Bbs::get(Request::instance()->param('comment_id_reply'));
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
                        $fatherReply = Bbs::get($fatherReplyId);
                        while($fatherReply->son_id != NULL){
                            $fatherReplyId = $fatherReply->son_id;
                            $fatherReply = Bbs::get($fatherReplyId);
                        }
                        $fatherReply->son_id = $aNewReply->id;
                        $fatherReply->isUpdate(true)->save();
                        $aNewReply->father_id = $fatherReplyId;
                        $aNewReply->level = $fatherReply->level + 1;
                        $aNewReply->reply_id = $fatherReply->reply_id;
                        $aNewReply->isUpdate(true)->save();
                    }
                    Admin::bbsMe();
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
            $theComment = Bbs::get(Request::instance()->param('comment_id_remove'));
            if($theComment != NULL){
                if($theComment->father_id != 0){
                    // 不需操作blog
                    if($theComment->son_id != 0){
                        // 需要操作son(不是第一条也不是最后一条评论)
                        $fatherComment = Bbs::get($theComment->father_id);
                        $sonComment = Bbs::get($theComment->son_id);
                        $fatherComment->son_id = $sonComment->id;
                        $sonComment->father_id = $fatherComment->id;
                        $fatherComment->isUpdate(true)->save();
                        $sonComment->isUpdate(true)->save();
                        $theComment->delete();
                    }else{
                        // 不需操作son(不是第一条评论是最后一条评论)
                        $fatherComment = Bbs::get($theComment->father_id);
                        $fatherComment->son_id = 0;
                        $fatherComment->isUpdate(true)->save();
                        $theComment->delete();
                    }
                }else{
                    // 需要操作blog
                    if($theComment->son_id != 0){
                        // 需要操作son(是第一条评论不是最后一条评论)
                        $sonComment = Bbs::get($theComment->son_id);
                        $sonComment->father_id = 0;
                        $sonComment->isUpdate(true)->save();
                        $theComment->delete();
                    }else{
                        // 不需操作son(是第一条评论也是最后一条评论)
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
            $theReply = Bbs::get(Request::instance()->param('reply_id_remove'));
            if($theReply != NULL){
                if($theReply->father_id != 0){
                    // 不需要操作comment
                    if($theReply->son_id != 0){
                        // 不需要操作son(不是第一条回复也不是最后一条回复)
                        $fatherReply = Bbs::get($theReply->father_id);
                        $sonReply = Bbs::get($theReply->son_id);
                        $fatherReply->son_id = $sonReply->id;
                        $sonReply->father_id = $fatherReply->id;
                        $fatherReply->isUpdate(true)->save();
                        $sonReply->isUpdate(true)->save();
                        $theReply->delete();
                    }else{
                        // 需要操作son(不是第一条回复是最后一条回复)
                        $fatherReply = Bbs::get($theReply->father_id);
                        $fatherReply->son_id = 0;
                        $fatherReply->isUpdate(true)->save();
                        $theReply->delete();
                    }
                }else{
                    // 需要操作comment
                    $theComment = Bbs::get(Request::instance()->param('comment_id_remove'));
                    if($theReply->son_id != 0){
                        // 需要操作son(是第一条回复不是最后一条回复)
                        $sonReply = Bbs::get($theReply->son_id);
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
