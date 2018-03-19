<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;

class Admin extends \think\Controller{
    public function sendEmail(){
        $mail = new \PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = "zhujinteng2012@163,com";
        $mail->Port = 25;
        $mail->setFrom('zhujinteng2012@163e.com');
        $mail->addAddress('1422090554@qq.com');
        $mail->Subject('验证码');
        $mail->msgHTML('sdf774');
        $mail->AltBody = 'ert';

        if(!$mail->send()){
            return 'errr';
        }else{
            return 'success';
        }
    }
}


?>
