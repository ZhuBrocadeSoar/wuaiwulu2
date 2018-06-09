<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class Order extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getProductIdAttr($v){
        $list = explode(',', $v);
        foreach($list as &$val){
            $val = intval($val);
        }
        return $list;
    }

        /*
    public function setProductIdAttr($v){
        $list = $v;
        foreach($list as &$val){
            $val = strval($val);
        }
        return implode(',', $list);
        return implode(',', $v);
    }
         */

    public function getProductQuAttr($v){
        $list = explode(',', $v);
        foreach($list as &$val){
            $val = intval($val);
        }
        return $list;
    }

        /*
    public function setProductQuAttr($v){
        $list = $v;
        foreach($list as &$val){
            $val = strval($val);
        }
        return implode(',', $list);
        return implode(',', $v);
    }
         */

}

?>
