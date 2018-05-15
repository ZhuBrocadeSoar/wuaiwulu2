<?php
namespace app\callme\model;

use think\Model;
use traits\model\SoftDelete;

class Seller extends Model{
    protected $autoWriteTimestamp = 'datetime';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}

?>
