<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use PHPMailer\PHPMailer\PHPMailer;

class Admin extends \think\Controller{
    public function index(){
        return $this->fetch('admin');
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
