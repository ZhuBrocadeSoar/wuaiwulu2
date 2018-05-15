<?php
namespace app\callme\controller;

use app\callme\model\BdAppInfo;
use app\callme\model\Session;
use app\callme\model\Order;
use think\Request;

class BdApi extends \think\Controller{
    public static function accessToken(){
        $appInfo = BdAppInfo::get(1);
        if($appInfo->is_over_time){
            // 过期重新获取access token
            $app_id = $appInfo->app_id;
            $api_key = $appInfo->api_key;
            $secret_key = $appInfo->secret_key;
            $grant_type = 'client_credentials';
            $client_id = $api_key;
            $client_secret = $secret_key;
            $url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=$grant_type&client_id=$client_id&client_secret=$client_secret&";
            $api = curl_init();
            curl_setopt($api, CURLOPT_URL, $url);
            curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($api, CURLOPT_HEADER, false);
            $tokenObj = curl_exec($api);
            curl_close($api);
            $tokenObj = json_decode($tokenObj);
            $appInfo->access_token = $tokenObj->access_token;
            $appInfo->expires_in = $tokenObj->expires_in;
            $appInfo->refresh_token = $tokenObj->refresh_token;
            $appInfo->scope = $tokenObj->scope;
            $appInfo->session_key = $tokenObj->session_key;
            $appInfo->session_secret = $tokenObj->session_secret;
            $appInfo->isUpdate(true)->save();
            $token = $tokenObj->access_token;
        }else{
            $token = $appInfo->access_token;
        }

        return $token;
    }

    public static function getAudioUrl($tex, $spd = 5, $pit = 5, $vol = 5, $per = 0){
        $url = "http://tsn.baidu.com/text2audio?";
        $url = $url . "tex=$tex";
        $tok = BdApi::accessToken();
        $url = $url . "&tok=$tok";
        $url = $url . "&cuid=CUID";
        $url = $url . "&ctp=1";
        $url = $url . "&lan=zh";
        $url = $url . "&spd=$spd&pit=$pit&vol=$vol&per=$per";

        return $url;
    }
    
}

?>
