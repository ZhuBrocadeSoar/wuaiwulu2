<?php
namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class User extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getNickAttr($v){
        return base64_decode($v);
    }

    public function setNickAttr($v){
        return base64_encode($v);
    }

}

?>
