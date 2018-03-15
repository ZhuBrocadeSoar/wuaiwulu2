<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class Auth{
    public static function isUser($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin0($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $row = Db::name('admins_0')
                ->where('token', sha1('woyaochuliubashengjian'))
                ->select();
            if($row == NULL){
                $retval = false;
            }else{
                $retval = true;
            }
        }
        return $retval;
    }

    public static function isAdmin1($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin2($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin($hash){
        if(Auth::isAdmin2($hash) 
            || Auth::isAdmin1($hash)
            || Auth::isAdmin0($hash)
        ){
            $retval = true;
        }else{
            $retval = false;
        }
        return $retval;
    }

    public function signIn(){
        // 获取参数code
        if(Request::instance()->has('code')){
            $code = Request::instance()->param('code');
            // 获取其他参数
            $wxapp_info = Db::name('wxapp_info')
                ->field('appid', 'secret')
                ->where('id', 1)
                ->select();
            $appid = $wxapp_info[0]['appid'];
            $secret = $wxapp_info[0]['secret'];
            $grant_type = 'authorization_code';
            // curl 调用wx api
            $connToWxApi = curl_init();
            $urlWithParam = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=' . $grant_type;
            curl_setopt($connToWxApi, CURLOPT_URL, $urlWithParam);
            curl_setopt($connToWxApi, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connToWxApi, CURLOPT_HEADER, false);
            $responce = curl_exec($connToWxApi);
            $signInInfo = json_decode($responce, true);
            if(isset($signInInfo['openid'])){
                $openid = $signInInfo['openid'];
                $session_key = $signInInfo['session_key'];
                // 存储 登记
                $check = Db::name('session')
                    ->where('openid_hash', sha1($openid))
                    ->select();
                if($check != NULL){
                    // signInInfo already
                    $retval = array("state" => "error", "errorMsg" => "Record already");
                }else{
                    // recode session
                    // 选择组
                    $row = Db::name('admins_0')
                        ->where('openid_hash', sha1($openid))
                        ->select();
                    $group = '0';
                    if($row == NULL){
                        $row = Db::name('admins_1')
                            ->where('openid_hash', sha1($openid))
                            ->select();
                        $group = '1';
                        if($row == NULL){
                            $row = Db::name('admin_2')
                                ->where('openid_hash', sha1($openid))
                                ->select();
                            $group = '2';
                        }else{
                            $group = '3';
                        }
                    }
                    // 生成hash
                    $hash = sha1($session_key + time());
                    $datas = array('hash' => $hash, 'openid_hash' => sha1($openid), 'group' => $group);
                    $counts = Db::name('session')
                        ->insert($datas);
                    if($counts > 0){
                        $retval = array("state" => "success", "hash" => $hash);
                    }else{
                        $retval = array("state" => "error", "errorMsg" => "Insert error");
                    }
                }
            }else{
                $retval = array("state" => "error", "errorMsg" => "Weixin api error", "responce" => $signInInfo);
            }
        }else{
            $retval = array("state" => "error", "errorMsg" => "Param error");
        }
        return json_encode($retval);
    }
}

?>
