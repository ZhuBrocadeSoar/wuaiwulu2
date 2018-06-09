<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class ProductHistory extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getNameAttr($v){
        return base64_decode($v);
    }

    public function setNameAttr($v){
        return base64_encode($v);
    }

    public function getInfoAttr($v){
        return base64_decode($v);
    }

    public function setInfoAttr($v){
        return base64_encode($v);
    }

    public function getLabelAttr($v){
        return base64_decode($v);
    }

    public function setLabelAttr($v){
        return base64_encode($v);
    }

}

?>
