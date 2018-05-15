<?php
namespace app\callme\controller;

use app\callme\model\WxAppInfo;
use app\callme\model\Session;
use app\callme\model\Order;
use think\Request;

class WxApi extends \think\Controller{
    /* (string)$sign = genSign($array);
     *
     * */
    public static function genSign($array){
        // 获取排序
        ksort($array);
        $stringA = "";
        foreach($array as $key => $val){
            if($val != NULL){
                $stringA = $stringA . "$key=$val&";
            }
        }
        $mch_key = WxAppInfo::get(1)->mch_key;
        $stringA = $stringA . "key=$mch_key";
        return strtoupper(md5($stringA));
    }

    /* (bool)$success = verSign($array, $sign);
     *
     * */
    public static function verSign($array, $sign){
        if($sign == WxApi::genSign($array)){
            return true;
        }else{
            return false;
        }
    }

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
            $appInfo->expires_in = $tokenObj->expires_in;
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

    /*
     * unifiedorder
     * 统一下单
     * */
    public static function unifiedorder(
        // $body,
        // $total_fee,
        $openid,
        $product_id,
        // $appid,
        // $mch_id,
        $device_info = 'String(32)',
        // $nonce_str
        // $sign,
        // $sign_type = 'MD5',
        $detail = 'String(6000)',
        $attach = 'String(127)',
        // $out_trade_no,
        // $fee_type = 'CNY',
        // $spbill_create_ip,
        // $time_start,
        // $time_expire,
        $goods_tag = 'String(32)',
        // $notify_url = 'https://brocadesoar.cn/wx_pay/notify',
        // $trade_type = 'JSAPI',
        $limit_pay = '__no_credit'
    ){
        $appInfo = WxAppInfo::get(1);
        $appid = $appInfo->appid;
        // $attach;
        $body = "DEBUG"; // 读写cm_product获得
        // $detail;
        // $device_info;
        // $fee_type;
        // $goods_tag;
        // $limit_pay;
        $mch_id = $appInfo->mch_id;
        $nonce_str = substr(str_shuffle(sha1(time())), rand(0, 7), 32);
        $notify_url = 'https://brocadesoar.cn/callme/wx_pay/notify';
        // $openid; // 传入
        // $out_trade_no; // 时间戳记 + 3位序列号(000-999循环) // 读写cm_order获得
        // $product_id;
        // $sign; // 计算获得
        // $sign_type;
        $spbill_create_ip = Request::instance()->server('REMOTE_ADDR');
        // $time_expire;
        // $time_start;
        $total_fee = 1; // 读写cm_product获得,单位分
        $trade_type = "JSAPI";
        // 读写cm_order
        $anOrder = new Order;
        $anOrder->data([
            'openid' => $openid,
            // 'nonce_str' => $nonce_str,
            'spbill_create_ip' => $spbill_create_ip,
            'product_id' => $product_id,
            'total_fee' => $total_fee,
            'pay_state' =>'unifiedorder',
        ]);
        $anOrder->isUpdate(false)->save();
        $theOrder = Order::get($anOrder->id);
        // 生成out_trade_no
        $orderindex = $anOrder->id % 1000;
        $out_trade_no = date("YmdHis", time()) . "$orderindex";
        $postFieldsArray = [
            "trade_type" => $trade_type,
            "total_fee" => $total_fee,
            "spbill_create_ip" => $spbill_create_ip,
            "out_trade_no" => $out_trade_no,
            "openid" => $openid,
            "notify_url" => $notify_url,
            "nonce_str" => $nonce_str,
            "mch_id" => $mch_id,
            "body" => $body,
            "appid" => $appid,
        ];
        // 生成sign
        $sign = WxApi::genSign($postFieldsArray);
        $theOrder->data([
            'out_trade_no' => $out_trade_no,
            // 'sign' => $sign,
        ]);
        $theOrder->isUpdate(true)->save();
        $postFields = "
<xml>
    <appid>$appid</appid>
    <body>$body</body>
    <mch_id>$mch_id</mch_id>
    <nonce_str>$nonce_str</nonce_str>
    <notify_url>$notify_url</notify_url>
    <openid>$openid</openid>
    <out_trade_no>$out_trade_no</out_trade_no>
    <sign>$sign</sign>
    <spbill_create_ip>$spbill_create_ip</spbill_create_ip>
    <total_fee>$total_fee</total_fee>
    <trade_type>$trade_type</trade_type>
</xml>
";
        $api = curl_init();
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_POST, true);
        curl_setopt($api, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($api, CURLOPT_HTTPHEADER, ["Content-Type:text/xml; charset=utf-8"]);
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HEADER, false);
        $retval = curl_exec($api);
        $xmlRetval = simplexml_load_string($retval, "SimpleXMLElement", LIBXML_NOCDATA);
        $jsonRetval = json_encode($xmlRetval);
        $arrayRetval = json_decode($jsonRetval, true);
        curl_close($api);
        $theOrder = Order::get([
            'out_trade_no' => $out_trade_no,
        ]);
        // 根据retval修改订单状态
        $retvalSign = $arrayRetval['sign'];
        unset($arrayRetval['sign']);
        if(($arrayRetval['return_code'] == 'SUCCESS') && ($arrayRetval['sign_state'] = WxApi::verSign($arrayRetval, $retvalSign)) && ($arrayRetval['result_code'] == 'SUCCESS')){
            // 下单成功,修改订单状态
            $theOrder->pay_state = 'wait';
            $theOrder->prepay_id = $arrayRetval['prepay_id'];
            $theOrder->isUpdate(true)->save();
            // 准备下发参数
            $timeStamp = date("YmdHid", time());
            $nonceStr = substr(str_shuffle(sha1(time())), rand(0, 7), 32);
            $package = 'prepay_id=' . $arrayRetval['prepay_id'];
            $paramsArray = [
                "appId" => $appid,
                "timeStamp" => $timeStamp,
                "nonceStr" => $nonceStr,
                "package" => $package,
                "signType" => 'MD5',
            ];
            $sign2 = WxApi::genSign($paramsArray);
            $arrayRetval['params'] = [
                "appid" => $appid,
                "timeStamp" => $timeStamp,
                "nonceStr" => $nonceStr,
                "package" => $package,
                "signType" => 'MD5',
                "paySign" => $sign2,
            ];
        }else{
            // 下单失败,关闭订单
            $theOrder->pay_state = 'closed';
            $theOrder->isUpdate(true)->save();
        }

        return $arrayRetval;
    }

    public static function closeorder($out_trade_no){
        $appInfo = WxAppInfo::get(1);
        $appid = $appInfo->appid;
        $mch_id = $appInfo->mch_id;
        $nonce_str = substr(str_shuffle(sha1(time())), rand(0, 7), 32);
        $postFieldsArray = [
            "appid" => $appid,
            "mch_id" => $mch_id,
            "out_trade_no" => $out_trade_no,
            "nonce_str" => $nonce_str,
        ];
        $sign = WxApi::genSign($postFieldsArray);
        $postFieldsArray['sign'] = $sign;
        $postFields = "
<xml>
    <appid>$appid</appid>
    <mch_id>$mch_id</mch_id>
    <out_trade_no>$out_trade_no</out_trade_no>
    <nonce_str>$nonce_str</nonce_str>
    <sign>$sign</sign>
</xml>
";
        $api = curl_init();
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        curl_setopt($api, CURLOPT_URL, $url);
        curl_setopt($api, CURLOPT_POST, true);
        curl_setopt($api, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($api, CURLOPT_HTTPHEADER, ["Content-Type:text/xml; charset=utf-8"]);
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($api, CURLOPT_HEADER, false);
        $retval = curl_exec($api);
        $xmlRetval = simplexml_load_string($retval, "SimpleXMLElement", LIBXML_NOCDATA);
        $jsonRetval = json_encode($xmlRetval);
        $arrayRetval = json_decode($jsonRetval, true);
        curl_close($api);

        $theOrder = Order::get([
            "out_trade_no" => $out_trade_no,
        ]);
        // 根据retval修改订单关闭
        $retvalSign = $arrayRetval['sign'];
        unset($arrayRetval['sign']);
        /*
        if(
            ($arrayRetval['return_code'] == 'SUCCESS') &&
            ($arrayRetval['sign_state'] = WxApi::verSign($arrayRetval, $retvalSign)) &&
            ($arrayRetval['result_code'] == 'SUCCESS')
        ){
        }
         */
        if($arrayRetval['return_code'] != 'SUCCESS'){
            // 通信错误
            $retval = [
                "errmsg" => "Internal Error",
            ];
        }else if(WxApi::verSign($arrayRetval, $retvalSign)){
            // 签名错误
            $retval = [
                "errmsg" => "Internal Error: Sign Error",
            ];
        }else if($arrayRetval['result_code'] != 'SUCCESS'){
            // 关单错误
            $retval = [
                "errmsg" => "Internal Error: System Error",
                "err_code" => $arrayRetval['err_code'],
                "err_code_des" => $arrayRetval['err_code_des'],
            ];
        }else{
            // 关单成功
            $theOrder->pay_state = 'closed';
            $theOrder->isUpdate(true)->save();
            $retval = [
                "errmsg" => "SUCCESS",
            ];
        }
        return $retval;
    }

}

?>
