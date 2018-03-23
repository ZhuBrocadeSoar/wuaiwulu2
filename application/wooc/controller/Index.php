<?php
namespace app\wooc\controller;

use think\Db;
use think\Request;

class Index extends \think\Controller{
    public function index(){
        $video = file_get_contents('/var/www/html/wuaiwulu2/public/images/CATBOX.mp4');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize('/var/www/html/wuaiwulu2/public/images/CATBOX.mp4'));
        return $video;
    }
}

?>
