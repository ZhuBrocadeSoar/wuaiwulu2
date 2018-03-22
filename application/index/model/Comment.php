<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Comment extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getContentAttr($v){
        return base64_decode($v);
    }

    public function setContentAttr($v){
        return base64_encode($v);
    }

}

?>
