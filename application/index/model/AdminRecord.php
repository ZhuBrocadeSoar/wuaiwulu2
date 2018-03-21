<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class AdminRecord extends Model{
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';
    public function getOverTimeAttr($value, $data){
        /*
        if(date() - $data > (30 * 60 * 1000)){
            return true;
        }else{
            return false;
        }
         */
        return time() - $value;
    }
}

?>
