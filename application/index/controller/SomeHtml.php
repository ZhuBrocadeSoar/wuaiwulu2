<?php
namespace app\index\controller;

use think\Request;

class SomeHtml extends \think\Controller{
    public function replyHtml(){
        $reply_form_str = '


<div class="am-comment-main">
    <header class="am-comment-hd">
        <div class="am-comment-meta">
            <form class="pure-form" onsubmit="return false">
                <fieldset>
                    <div class="pure-g">
                        <div class="pure-u-1-3">
                            <input class="pure-input-1" id="nick_in_reply" name="nick_in" type="text" placeholder="昵称(必填)">
                        </div>
                        <div class="pure-u-1-3">
                            <input class="pure-input-1" id="site_in_reply" name="site_in" type="text" placeholder="主页">
                        </div>
                        <div class="pure-u-1-3">
                            <input class="pure-input-1" id="email_in_reply" name="email_in" type="email" placeholder="邮箱(必填)">
                        </div>
                    </div>
                    <input id="comment_id_reply" type="hidden" value="' . Request::instance()->param('comment_id_reply') . '" />
                </fieldset>
            </form>
        </div>
    </header>
    <div class="am-comment-bd">
        <form class="pure-form" onsubmit="return false">
            <fieldset>
                <textarea class="pure-input-1" id="comment_in_reply" style="resize: none;" placeholder="须知：您无需注册登录即可回复，但您的ip地址和有效邮箱地址将被记录备案（但不会被公示），您必须对您的言论负责。您的昵称和主页信息将与邮箱、IP地址绑定，也就是说邮箱和IP地址都没有变化时昵称和主页将保持之前的状态。回复支持markdown语法" rows="8"></textarea>
            </fieldset>
            <p align="center" id="notices_reply" hidden></p>
            <p align="center" id="embed-captcha"></p>
            <p align="center" id="wait_reply" class="show">正在加载验证码......</p>
            <p align="center" id="notice_reply" class="hide">请先完成验证</p>
            <p align="center"><button class="pure-button pure-button-primary" id="embed-submit-reply" value=' . Request::instance()->param('level') . '>回复</button></p>
        </form>
    </div>
</div>

';
        return json_encode([
            'str_html' => $reply_form_str,
            'level' => Request::instance()->param('level'),
        ]);
    }
}

?>
