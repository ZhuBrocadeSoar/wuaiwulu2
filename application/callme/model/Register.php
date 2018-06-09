<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class Register extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getStaffListAttr($v){
        $list = explode(',', $v);
        foreach($list as &$val){
            $val = intval($val);
        }
        return $list;
    }

    public function getOwnerNameAttr($v){
        return base64_decode($v);
    }

    public function setOwnerNameAttr($v){
        return base64_encode($v);
    }

    public function getSellerNameAttr($v){
        return base64_decode($v);
    }

    public function setSellerNameAttr($v){
        return base64_encode($v);
    }

}

?>
