<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\View;
use think\Session;
use PHPMailer\PHPMailer\PHPMailer;
use app\index\model\AdminRecord;

class Admin extends \think\Controller{
    private function isSessionEnable($session_id){
        $adminRecord = new AdminRecord();
        if($adminRecord->id != NULL){
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
            if(sessionEnable(session_id())){
                // session 有效
                $this->assign([
                    'title' => '-欢迎回来-管理员',
                ]);
                $this->fetch('welcome');
            }else{
                // session 无效
                $this->assign([
                    'title' => '-获取验证码',
                ]);
                $this->fetch('chapcha');
            }
        }else{
            Session::set('check', 'checked');
        }
    }

    public function insertAdmin(){
        Session::has('check');
        $adminRecord = AdminRecord::get(AdminRecord::max('id'));
        $adminRecord->session_id = session_id();
        $adminRecord->isUpdate(true)->save();
        dump($adminRecord);
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
