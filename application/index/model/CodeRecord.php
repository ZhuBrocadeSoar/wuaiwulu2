<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class CodeRecord extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function getAddEnableAttr($value, $data){
        if((time() - strtotime($data['create_time'])) > (60)){
            return true;
        }else{
            return false;
        }
    }

    public function getConfirmedAttr($value){
        if($value == 'y'){
            return true;
        }else{
            return false;
        }
    }

    public function setConfirmedAttr($value){
        if($value){
            return 'y';
        }else{
            return 'n';
        }
    }
}

?>
