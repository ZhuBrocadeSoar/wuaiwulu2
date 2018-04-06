<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Bbs extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getContentAttr($v){
        return base64_decode($v);
    }

    public function setContentAttr($v){
        return base64_encode($v);
    }

    public function getAdminMarkAttr($v){
        if($v == 'y'){
            return true;
        }else{
            return false;
        }
    }

    public function setAdminMarkAttr($v){
        if($v){
            return 'y';
        }else{
            return 'n';
        }
    }
}

?>
