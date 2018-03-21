<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class AdminRecord extends Model{
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = 'delete_time';
    public function getOverTimeAttr($value, $data){
        /*
        if((time() - strtotime($data['create_time'])) > (30 * 60)){
            return true;
        }else{
            return false;
        }
         */
        // return date('Y-m-d H:i:s') - $data['create_time'];
        // return $data['create_time'];
        return time() - strtotime($data['create_time']);
    }
}

?>
