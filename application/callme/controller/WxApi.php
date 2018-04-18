<?php
namespace app\callme\controller;

use app\callme\model\WxAppInfo;
use app\callme\model\Session;

class WxApi extends \think\Controller{
    /* (string)$accessToken = accessToken();
     * */
    public static function accessToken(){
        $appInfo = WxAppInfo::get(1);
        if($appInfo->is_over_time){
            // 过期重新获取access token
            $appid = $appInfo->appid;
            $appsecret = $appInfo->appsecret;
            $grantType = 'client_credential';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?appid=' . $appid . '&secret=' . $appsecret . '&grant_type=' . $grantType;
            $api = curl_init();
            curl_setopt($api, CURLOPT_URL, $url);
            curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($api, CURLOPT_HEADER, false);
            $tokenObj = curl_exec($api);
            curl_close($api);
            $tokenObj = json_decode($tokenObj);
            $appInfo->access_token = $tokenObj->access_token;
            $appInfo->over_time = $tokenObj->expires_in;
            $appInfo->isUpdate(true)->save();
            $token = $tokenObj->access_token;
        }else{
            // 未过期
            $token = $appInfo->access_token;
        }

        return $token;
    }

    /* (object)$sessionObj = login($jsCode);
     *
     * (string)$jsCode
     * (string)$sessionObj->openid
     * (string)$sessionObj->session_key
     * (string)$sessionObj->unionid
     *
     * */
    public static function login($jsCode){
        $appInfo = WxAppInfo::get(1);
        $appid =  $appInfo->appid;
        $appsecret = $appInfo->appsecret;
        $grantType = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret . '&js_code=' . $jsCode . '&grant_type=' . $grantType; 
        $api = curl_init();
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HEADER, false);
        $sessionStr = curl_exec($api);
        curl_close($api);
        $sessionArr = json_decode($sessionStr, true);

        return $sessionArr;
    }

    /* (array)$msgTemplatesList = msgTemplatesList();
     *
     * (object)$msgTemplatesList[$id]
     * (string)$msgTemplatesList[$id]->template_id
     * (string)$msgTemplatesList[$id]->title
     * (string)$msgTemplatesList[$id]->content
     * (string)$msgTemplatesList[$id]->example
     * */
    public static function msgTemplatesList(){
        $accessToken = WxApi::accessToken();
        $offset = 0;
        $count = 5;
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=' . $accessToken;
        $postFields = json_encode(array(
            // "access_token" => $accessToken,
            "offset" => 0,
            "count" => 5,
        ));
        $api = curl_init();
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HEADER, false);
        curl_setopt($api, CURLOPT_POST, true);
        curl_setopt($api, CURLOPT_POSTFIELDS, $postFields);
        $listObj = curl_exec($api);
        curl_close($api);
        $listObj = json_decode($listObj);
        if($listObj->errcode != 0){
            return NULL;
        }else{
            return $listObj->list;
            // return json_encode($listObj->list);
        }
    }

    /* (int)$state = msgSend($toUser, $formId, $data, $page, $color, $emphasisKeyword);
     *
     * (string)$toUser 'OPENID' 必须的，无默认的
     * // (string)$templateId 'TEMPLATEID' 由$data选择的
     * (string)$formId 'FOEMID' 必须的，默认为
     * (array)$data [data] 必须的，无默认的
     * (string)$page 'PAGE' 不必须的，无默认的
     * (string)$color 'COLOR' 不必须的，无默认的
     * (string)$emphasisKeyword 'EMKEY' 不必须的，无默认的
     *
     * (int)$state
     *
     * */
    public static function msgSend(
        $toUser, 
        $formId, 
        $data, 
        $page = 'pages/index/index', 
        $color = '', 
        $emphasisKeyword = ''){

        $msgTemplatesList = WxApi::msgTemplatesList();
        if($msgTemplatesList[0]->title == '取餐提醒'){
            $templateQuId = $msgTemplatesList[0]->template_id;
            $templateDianId = $msgTemplatesList[1]->template_id;
        }else{
            $templateQuId = $msgTemplatesList[1]->template_id;
            $templateDianId = $msgTemplatesList[0]->template_id;
        }
        if(count($data) == 4){
            // 取餐提醒
            $templateId = $templateQuId;
        }else if(count($data) == 5){
            // 点餐成功通知
            $templateId = $templateDianId;
        }
        $accessToken = WxApi::accessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $accessToken;
        $postFields = json_encode(array(
            "touser" => $toUser,
            "template_id" => $templateId,
            "page" => $page,
            "form_id" => $formId,
            "data" => $data,
            "color" => $color,
            "emphasis_keyword" => $emphasisKeyword,
        ));
        $api = curl_init();
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HEADER, false);
        curl_setopt($api, CURLOPT_POST, true);
        curl_setopt($api, CURLOPT_POSTFIELDS, $postFields);
        $retval = curl_exec($api);
        curl_close($api);
        $retval = json_decode($retval);
        return $retval->errcode;
    }

}

?>
