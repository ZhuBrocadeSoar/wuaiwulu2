<?php
namespace app\think\controller;

use think\Db;
use think\Request;

class Index extends \think\Controller{
    public function index(){
        $video = file_get_contents('/var/www/html/wuaiwulu2/public/images/CATBOX.mp4');
        header('Content-Type: video/mpeg4');
        return $video;
    }
}

?>
