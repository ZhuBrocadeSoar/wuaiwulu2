<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;
use app\index\model\AdminRecord;

class Admin extends \think\Controller{
    public function index(){
        if(Session::has('check')){
            if(Session::has('check') == 'session_checked');
        }else{
            Session::set('check', 'nonlogined');
        }
        $this->assign([
            'title' => '-管理',
            'session_id' => session_id(),
        ]);
        dump(Session::get('check'));
        $adminRecord = AdminRecord::get(AdminRecord::max('id'));
        dump($adminRecord);
        // return $this->fetch('admin');
    }

    public function insertAdmin(){
        $adminRecord = new AdminRecord();
        $adminRecord->session_id = session_id();
        $adminRecord->code = 'testtt';
        $adminRecord->isUpdate(false)->save();
    }

    public function sendEmail(){
        $code0 = sha1(time());
        $code1 = str_split($code0);
        $code2 = 
            $code1[rand(0, strlen($code0) - 1)].
            $code1[rand(0, strlen($code0) - 1)].
            $code1[rand(0, strlen($code0) - 1)].
            $code1[rand(0, strlen($code0) - 1)].
            $code1[rand(0, strlen($code0) - 1)].
            $code1[rand(0, strlen($code0) - 1)];
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.163.com";
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = "brocadesoar@163.com";
        $mail->Password = "zjt1add7eq8";
        $mail->setFrom('brocadesoar@163.com');
        $mail->addAddress('1422090554@qq.com');
        $mail->Subject = '验证码';
        $mail->msgHTML($code2);
        $mail->AltBody = 'ert';

        if(!$mail->send()){
            return 'errr';
        }else{
            return 'success';
        }
    }
}


?>
