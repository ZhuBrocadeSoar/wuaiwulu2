<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class AdminRecord extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function getOverTimeAttr($value, $data){
        if((time() - strtotime($data['create_time'])) > (120 * 60)){
            return true;
        }else{
            return false;
        }
    }
}

?>
