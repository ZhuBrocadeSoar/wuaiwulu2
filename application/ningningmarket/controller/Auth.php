<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class Auth{
    public static function isUser($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public function signIn($code){
        return $hash;
    }
}

?>
