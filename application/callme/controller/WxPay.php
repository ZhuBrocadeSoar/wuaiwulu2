<?php
namespace app\callme\controller;

use app\callme\model\WxAppInfo;
use app\callme\model\Session;
use app\callme\model\Order;
use app\callme\controller\WxApi;
use think\Request;

class WxPay extends \think\Controller{
    public function unifiedorder(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else if(!Request::instance()->has('product_id', 'post')){
            return json_encode([
                "errmsg" => "Format Error",
            ]);
        }else if(false/*检查product_id在数据库不对应*/){
            return;
        }else{
            // 通过所有检查
            $retval = WxApi::unifiedorder($session->openid, Request::instance()->param('product_id'));
            // dump($retval);
            if($retval['return_code'] != 'SUCCESS'){
                $errmsg = $retval['return_msg'];
                return json_encode([
                    "errmsg" => "Interner Error: $errmsg",
                ]);
            }else if($retval['result_code'] != 'SUCCESS'){
                return json_encode([
                    "errmsg" => "Interner Error: System Error",
                    "err_code" => $retval['err_code'],
                    "err_code_des" => $retval['err_code_des'],
                ]);
            }else if(!$retval['sign_state']){
                return json_encode([
                    "errmsg" => "Interner Error: Sign Error",
                ]);
            }else{
                return json_encode($retval['params']);
            }
            return;
        }
    }

    public function notify(){
        $xmlRetval = simplexml_load_string(file_get_contents("php://input"), "SimpleXMLElement", LIBXML_NOCDATA);
        // dump(Request::instance());
        // dump($GLOBALS['HTTPS_RAW_POST_DATA']);
        // dump(file_get_contents("php://input"));
        $jsonRetval = json_encode($xmlRetval);
        $arrayRetval = json_decode($jsonRetval, true);
        $retvalSign = $arrayRetval['sign'];
        unset($arrayRetval['sign']);
        // if((WxApi::verSign($arrayRetval, $retvalSign)) && ($arrayRetval['return_code'] == 'SUCCESS') && ($arrayRetval['result_code'] == 'SUCCESS')){
        // }
        if(!WxApi::verSign($arrayRetval, $retvalSign)){
            // 签名不通过
            return "
<xml>
    <return_code>FAIL</return_code>
    <return_msg>Sign Error</return_msg>
</xml>
";
        }else if($arrayRetval['return_code'] != 'SUCCESS'){
            // 通信失败
            return "
<xml>
    <return_code>FAIL</return_code>
    <return_msg>Param Error</return_msg>
</xml>
";
        }else if($arrayRetval['result_code'] != 'SUCCESS'){
            // 支付失败
            $theOrder = Order::get([
                "out_trade_no" => $arrayRetval['out_trade_no'],
            ]);
            if($theOrder == NULL){
                return "
    <xml>
        <return_code>FAIL</return_code>
        <return_msg>out_trade_no Not Match</return_msg>
    </xml>
    ";
            }else{
                // 记录支付失败内容
                if($theOrder->pay_state == 'wait'){
                    $theOrder->err_code = $arrayRetval['err_code'];
                    $theOrder->err_code_des = $arrayRetval['err_code_des'];
                    $theOrder->pay_state = 'fail';
                    $theOrder->isUpdate(true)->save();
                }
                return "
    <xml>
        <return_code>SUCCESS</return_code>
        <return_msg>OK</return_msg>
    </xml>
    ";
            }
        }else{
            // 支付成功
            $theOrder = Order::get([
                "out_trade_no" => $arrayRetval['out_trade_no'],
            ]);
            if($theOrder == NULL){
                return "
    <xml>
        <return_code>FAIL</return_code>
        <return_msg>out_trade_no Not Match</return_msg>
    </xml>
    ";
            }else{
                // 记录支付成功内容
                if($theOrder->pay_state == 'wait'){
                    $theOrder->cash_fee = $arrayRetval['cash_fee'];
                    $theOrder->transaction_id = $arrayRetval['transaction_id'];
                    $theOrder->time_end = $arrayRetval['time_end'];
                    if($theOrder->total_fee == $arrayRetval['total_fee']){
                        $theOrder->pay_state = 'done';
                        WxApi::msgSend(
                            $theOrder->openid,
                            $theOrder->prepay_id,
                            [
                                "keyword1" => [
                                    "value" => "门店名称",
                                ],
                                "keyword2" => [
                                    "value" => "取餐号码",
                                ],
                                "keyword3" => [
                                    "value" => "$theOrder->total_fee",
                                ],
                                "keyword4" => [
                                    "value" => $theOrder->create_time,
                                ],
                                "keyword5" => [
                                    "value" => "欢迎使用",
                                ],
                            ]
                        );
                    }else{
                        $theOrder->pay_state = 'alert';
                    }
                    $theOrder->isUpdate(true)->save();
                }
                return "
    <xml>
        <return_code>SUCCESS</return_code>
        <return_msg>OK</return_msg>
    </xml>
    ";
            }
        }
    }

}

?>
