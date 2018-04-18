<?php
namespace app\callme\controller;

use app\callme\controller\WxApi;
use think\Request;
use app\callme\model\Session;

class WxSession extends \think\Controller{
    public function index(){
        // WxSession 管理
        if(false){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            $jsCode = Request::instance()->param('code');
            $sessionArr = WxApi::login($jsCode);
            $aNewSession = Session::get([
                'session_key' => $sessionArr['session_key'],
                'openid' => $sessionArr['openid'],
            ]);
            if($aNewSession == NULL){
                $aNewSession = new Session;
                $aNewSession->session_key = $sessionArr['session_key'];
                $aNewSession->openid = $sessionArr['openid'];
                $aNewSession->session_id = sha1($sessionArr['session_key'] . date(DATE_ATOM, time()) );
                $aNewSession->isUpdate(false)->save();
            }
            return json_encode([
                "debug_openid" => $aNewSession->openid,
                "debug_session_key" => $aNewSession->session_key,
                "session_id" => $aNewSession->session_id,
            ]);
        }
    }

    public function debug_msg(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            $data = [
                "keyword1" => [
                    "value" => "word1",
                    "color" => "#173177",
                ],
                "keyword2" => [
                    "value" => "word2",
                    "color" => "#173177",
                ],
                "keyword3" => [
                    "value" => "word3",
                    "color" => "#173177",
                ],
                "keyword4" => [
                    "value" => "word4",
                    "color" => "#173177",
                ],
                "keyword5" => [
                    "value" => "word5",
                    "color" => "#173177",
                ],
            ];
            $openid = $session->openid;
            $session_key = $session->session_key;
            $session_id = $session->session_id;
            $formId = Request::instance()->param('formId');
            $state = WxApi::msgSend(
                $openid,
                $formId,
                $data
            );
            return json_encode([
                "debug_state" => $state,
                "debug_openid" => $openid,
                "debug_session_key" => $session_key,
                "session_id" => $session_id,
            ]);
        }
    }

    /*
    public function index(){
        // $jsCode = Request::instance()->param('code');
        $openid = 'o0Kk95fOUbKUy73CRFBza1EFhoj0';
        $data = [
            "keyword1" => [
                "value" => "word1",
                "color" => "#173177",
            ],
            "keyword2" => [
                "value" => "word2",
                "color" => "#173177",
            ],
            "keyword3" => [
                "value" => "word3",
                "color" => "#173177",
            ],
            "keyword4" => [
                "value" => "word4",
                "color" => "#173177",
            ],
            "keyword5" => [
                "value" => "word5",
                "color" => "#173177",
            ],
        ];
        // dump($sessionArr);
        // $openid = $sessionArr["openid"];
        $formId = Request::instance()->param('formId');
        // sleep(1);
        dump($formId);
        $state = WxApi::msgSend(
            $openid,
            $formId,
            $data
        );
        // return json_encode($sessionArr);
        return $state;
    }
     */
}

?>
