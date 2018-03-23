<?php

        $video = file_get_contents('/var/www/html/wuaiwulu2/public/images/CATBOX.mp4');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize('/var/www/html/wuaiwulu2/public/images/CATBOX.mp4'));
        echo $video;
        return;


?>
