<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class DiningHall extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getSellerListAttr($v){
        $list = explode(',', $v);
        foreach($list as &$val){
            $val = intval($val);
        }
        return $list;
    }

    public function getNameAttr($v){
        return base64_decode($v);
    }

    public function setNameAttr($v){
        return base64_encode($v);
    }

}

?>
