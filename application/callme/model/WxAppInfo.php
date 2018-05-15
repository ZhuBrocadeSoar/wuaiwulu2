<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class WxAppInfo extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getIsOverTimeAttr($v, $d){
        if(((time() - strtotime($d['update_time'])) > (int)$d['expires_in']) || ($d['update_time'] == NULL)){
            return true;
        }else{
            return false;
        }
    }

}

?>
