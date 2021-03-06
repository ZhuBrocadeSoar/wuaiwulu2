<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;
use app\index\model\AdminRecord;
use app\index\model\Gt3idkey;
use app\index\model\CodeRecord;
use app\index\model\MailAddr;
use traits\model\SoftDelete;
use GeetestLib;
use Jenssegers\Agent\Agent;

class Admin extends \think\Controller{
    public static function isAdmin(){
        Session::has('check');
        if(Admin::isSessionEnable(session_id())){
            return true;
        }else{
            return false;
        }
    }

    private static function isSessionEnable($session_id){
        $adminRecord = AdminRecord::get(AdminRecord::max('id'));
        if($adminRecord != NULL){
            if($adminRecord->session_id == $session_id){
                if(!$adminRecord->over_time){
                    return true;
                }
            }
        }
        return false;
    }

    public function index(){
        if(Session::has('check')){
            if(Admin::isSessionEnable(session_id())){
                // session 有效
                $this->assign([
                    'title' => '-欢迎回来-管理员',
                ]);
                return $this->fetch('welcome');
            }else{
                // session 无效
                $this->assign([
                    'title' => '-获取验证码',
                ]);
                return $this->fetch('captcha');
            }
        }else{
            Session::set('check', 'checked');
        }
    }

    public function captcha($timeStamp){
        $idkey = Gt3idkey::get(1);
        $GtSdk = new GeetestLib($idkey->gt3idkey_id, $idkey->gt3idkey_key);
        $agent = new Agent();
        if($agent->isMobile()){
            $client_type = 'h5';
        }else{
            $client_type = 'web';
        }
        $data = array(
            "user_id" => session_id(),
            "client_type" => $client_type,
            "ip_address" => Request::instance()->server('REMOTE_ADDR'),
        );
        $status = $GtSdk->pre_process($data, 1);
        Session::set('gtserver', $status);
        Session::set('user_id', $data['user_id']);
        // dump($GtSdk->get_response_str());
        echo $GtSdk->get_response_str();
        return;
    }

    public function captcha2(){
        Session::has('gtserver');
        $idkey = Gt3idkey::get(1);
        $GtSdk = new GeetestLib($idkey->gt3idkey_id, $idkey->gt3idkey_key);
        $agent = new Agent();
        if($agent->isMobile()){
            $client_type = 'h5';
        }else{
            $client_type = 'web';
        }
        $data = array(
            "user_id" => session_id(),
            "client_type" => $client_type,
            "ip_address" => Request::instance()->server('REMOTE_ADDR'),
        );
        if(Session::get('gtserver') == 1){
            $result = $GtSdk->success_validate(Request::instance()->param('geetest_challenge'), Request::instance()->param('geetest_validate'), Request::instance()->param('geetest_seccode'), $data);
            if($result){
                Session::set('captcha_status', 'success');
                echo '{"status" : "success"}';
            }else{
                Session::set('captcha_status', 'fail');
                echo '{"status" : "fail"}';
            }
        }else{
            if($GtSdk->fail_validate(Request::instance()->param('geetest_challenge'), Request::instance()->param('geetest_validate'), Request::instance()->param('geetest_seccode'))){
                Session::set('captcha_status', 'success');
                echo '{"status" : "success"}';
            }else{
                Session::set('captcha_status', 'fail');
                echo '{"status" : "fail"}';
            }
        }
        return;
    }

    public function code(){
        if(Session::has('captcha_status') && (Session::get('captcha_status') == 'success')){
            Session::set('captcha_status', 'reset');
            // 检查当前code状态
            $code = CodeRecord::get(CodeRecord::max('id'));
            if($code == NULL || $code->add_enable){
                // 发送邮件，记录code
                $code0 = sha1(time());
                $code1 = str_split($code0);
                $code2 = 
                    $code1[rand(0, strlen($code0) - 1)].
                    $code1[rand(0, strlen($code0) - 1)].
                    $code1[rand(0, strlen($code0) - 1)].
                    $code1[rand(0, strlen($code0) - 1)].
                    $code1[rand(0, strlen($code0) - 1)].
                    $code1[rand(0, strlen($code0) - 1)];
                $code = new CodeRecord;
                $code->code = $code2;
                $code->confirmed = false;
                $code->isUpdate(false)->save();
                Admin::sendEmail($code2);
                $this->assign([
                    'title' => '-验证您的身份',
                ]);
                return $this->fetch('code');
            }else{
                // 不发送邮件，啥也不做？再次渲染验证码页面
                return Admin::index();
            }
        }else{
            // 验证不通过，或者跳过验证
            return Admin::index();
        }
    }

    public function code2(){
        $codeLast = CodeRecord::get(CodeRecord::max('id'));
        if($codeLast == NULL){
            return Admin::index();
        }else{
            if(Request::instance()->has('code2', 'post') &&
                !$codeLast->confirmed &&
                !$codeLast->over_time &&
                ($codeLast->code == Request::instance()->param('code2'))
            ){
                // 登记到admin_record
                $adminLast = new AdminRecord;
                Session::has('check');
                $adminLast->session_id = session_id();
                $adminLast->code = Request::instance()->param('code2');
                $adminLast->isUpdate(false)->save();
            }
            // 登记 confirmed
            $codeLast->confirmed = true;
            $codeLast->isUpdate(true)->save();
            return Admin::index();
        }
    }

    private function sendEmail($code2){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.163.com";
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = MailAddr::get(1)->addr;
        $mail->Password = MailAddr::get(1)->password;
        $mail->setFrom(MailAddr::get(1)->addr);
        $mail->addAddress('1422090554@qq.com');
        $mail->Subject = 'Your code for login';
        $mail->msgHTML('<h1>' . $code2 . '</h1>Hello Administrator,</br>Your code for login is:</br><h1>' . $code2 . '</h1>');
        $mail->AltBody = 'ert';

        if(!$mail->send()){
            return 'errr';
        }else{
            return 'success';
        }
    }

    public static function noteMe($date_route, $title_route, $level){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.163.com";
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = MailAddr::get(1)->addr;
        $mail->Password = MailAddr::get(1)->password;
        $mail->setFrom(MailAddr::get(1)->addr);
        $mail->addAddress('1422090554@qq.com');
        $mail->Subject = 'A new comment';
        // $mail->msgHTML('<h1>' . $code2 . '</h1>Hello Administrator,<br />Your code for login is:<br /><h1>' . $code2 . '</h1>');
        $url = "https://brocadesoar.cn/blog/" . $date_route . "/" . $title_route . "#" . $level;
        $mail->msgHTML('Hello Administrator,<br />You get a new comment from <a href="' . $url . '">here</a>');
        $mail->AltBody = 'ert';

        if(!$mail->send()){
            return 'errr';
        }else{
            return 'success';
        }
    }

    public static function bbsMe(){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.163.com";
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = MailAddr::get(1)->addr;
        $mail->Password = MailAddr::get(1)->password;
        $mail->setFrom(MailAddr::get(1)->addr);
        $mail->addAddress('1422090554@qq.com');
        $mail->Subject = 'A new post on bbs';
        // $mail->msgHTML('<h1>' . $code2 . '</h1>Hello Administrator,<br />Your code for login is:<br /><h1>' . $code2 . '</h1>');
        // $url = "https://brocadesoar.cn/blog/" . $date_route . "/" . $title_route . "#" . $level;
        // $mail->msgHTML('Hello Administrator,<br />You get a new comment from <a href="' . $url . '">here</a>');
        $mail->msgHTML('Hello Administrator,<br />There is a new post on the bbs.');
        $mail->AltBody = 'ert';

        if(!$mail->send()){
            return 'errr';
        }else{
            return 'success';
        }
    }
}


?>
