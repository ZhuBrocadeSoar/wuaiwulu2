<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class Auth{
    public static function isUser($hash){
        return true;
    }

    public static function isAdmin($hash){
        return true;
    }

    public function signIn($code){
        return $hash;
    }
}

?>
