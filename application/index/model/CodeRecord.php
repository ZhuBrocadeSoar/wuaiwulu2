<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class CodeRecord extends Model{
    protected $autoWriteTimestamp = 'datatime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function getAddEnableAttr($value, $data){
        if((time() - strtotime($data['create_time'])) > (60)){
            return true;
        }else{
            return false;
        }
    }
}

?>
