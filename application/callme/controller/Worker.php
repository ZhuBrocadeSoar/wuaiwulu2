<?php
namespace app\callme\controller;

use think\worker\Server;
use Workerman\Lib\Timer;
use app\callme\model\Session;

class Worker extends Server{

    protected $socket = 'websocket://0.0.0.0:4431';
    protected $context = [
        'ssl' => [
            'local_cert' => '/etc/letsencrypt/live/brocadesoar.cn/cert.pem',
            'local_pk' => '/etc/letsencrypt/live/brocadesoar.cn/privkey.pem',
            'verify_peer' => false,
        ],
    ];
    // protected $socket = [$url, $context];

    protected $transport = 'ssl';

    public function onConnect($conn){
    }

    public function onClose($conn){
    }

    public function onMessage($conn, $data){
        $conn->lastMessageTime = time();
        if(empty($conn->session_id)){
            // 第一条消息
            $msg = json_decode($data, true);
            if(empty($msg['session_id'])){
                $conn->session_id = 'no session id';
            }else{
                // 检查session_id合法
                $session = Session::get([
                    'session_id' => $msg['session_id'],
                ]);
                if($session != NULL){
                    // 合法
                    $conn->session_id = $msg['session_id'];
                }else{
                    // 不合法
                    $conn->close();
                }
                $conn->session_id = $msg['session_id'];
            }
        }else{
            // 非第一条消息
            if($conn->session_id == 'no session id'){
                $conn->close();
            }else{
                // 业务逻辑
                $conn->send('hello' . $data);
                dump($data);
            }
        }
    }

    public function onWorkerStart($worker){
        Timer::add(1, function()use($worker){
            $time_now = time();
            foreach($worker->connections as $connection){
                if(empty($connection->lastMessageTime)){
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                if($time_now - $connection->lastMessageTime > 25){
                    $connection->close();
                }
            }
        });
    }
}

?>
