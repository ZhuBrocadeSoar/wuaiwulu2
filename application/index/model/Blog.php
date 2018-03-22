<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Blog extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getCreateDateAttr($v, $d){
        return date('Y-m-d', strtotime($d['create_time']));
    }

    public function getTag1Attr($v){
        return base64_decode($v);
    }

    public function setTag1Attr($v){
        return base64_encode($v);
    }

    public function getTag2Attr($v){
        return base64_decode($v);
    }

    public function setTag2Attr($v){
        return base64_encode($v);
    }

    public function getTag3Attr($v){
        return base64_decode($v);
    }

    public function setTag3Attr($v){
        return base64_encode($v);
    }

    public function getTag4Attr($v){
        return base64_decode($v);
    }

    public function setTag4Attr($v){
        return base64_encode($v);
    }

    public function getTag5Attr($v){
        return base64_decode($v);
    }

    public function setTag5Attr($v){
        return base64_encode($v);
    }

    public function getTitleViewAttr($v){
        return base64_decode($v);
    }

    public function setTitleViewAttr($v){
        return base64_encode($v);
    }

    public function getAbstractAttr($v){
        return base64_decode($v);
    }

    public function setAbstractAttr($v){
        return base64_encode($v);
    }

    public function getContentAttr($v){
        return base64_decode($v);
    }

    public function setContentAttr($v){
        return base64_encode($v);
    }

    public function getCommentEnableAttr($v){
        if($v == 'y'){
            return true;
        }else{
            return false;
        }
    }

    public function setCommentEnableAttr($v){
        if($v){
            return 'y';
        }else{
            return 'n';
        }
    }

}

?>
