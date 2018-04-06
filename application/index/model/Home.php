<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Home extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getTaglineAttr($v){
        return base64_decode($v);
    }

    public function setTaglineAttr($v){
        return base64_encode($v);
    }

    public function getArt1TitleAttr($v){
        return base64_decode($v);
    }

    public function setArt1TitleAttr($v){
        return base64_encode($v);
    }

    public function getArt1ContentAttr($v){
        return base64_decode($v);
    }

    public function setArt1ContentAttr($v){
        return base64_encode($v);
    }

    public function getArt2TitleAttr($v){
        return base64_decode($v);
    }

    public function setArt2TitleAttr($v){
        return base64_encode($v);
    }

    public function getArt2ContentAttr($v){
        return base64_decode($v);
    }

    public function setArt2ContentAttr($v){
        return base64_encode($v);
    }

    public function getArt3TitleAttr($v){
        return base64_decode($v);
    }

    public function setArt3TitleAttr($v){
        return base64_encode($v);
    }

    public function getArt3ContentAttr($v){
        return base64_decode($v);
    }

    public function setArt3ContentAttr($v){
        return base64_encode($v);
    }

    public function getArt4TitleAttr($v){
        return base64_decode($v);
    }

    public function setArt4TitleAttr($v){
        return base64_encode($v);
    }

    public function getArt4ContentAttr($v){
        return base64_decode($v);
    }

    public function setArt4ContentAttr($v){
        return base64_encode($v);
    }
}

?>
