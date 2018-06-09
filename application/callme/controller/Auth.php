<?php
namespace app\callme\controller;

use app\callme\model\AuthList;

class Auth extends \think\Controller{
    public static function permissionDenided(){
        return json_encode([
            "errmsg" => "Permission Denided",
        ]);
    }

    public static function checkPass($openid, $optArr){
        $one = AuthList::get($openid);
        if($one == NULL) return false;
        foreach($optArr as $id){
            switch($id){
            case 'seller':
                if($one->seller != 1) return false;
                break;
            case 'owner':
                if($one->owner != 1) return false;
                break;
            case 'platform':
                if($one->platform != 1) return false;
                break;
            case 'develop':
                if($one->develop != 1) return false;
                break;
            default:
                return false;
            }
        }
        return true;
    }
}

?>
