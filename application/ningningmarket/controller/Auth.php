<?php
namespace app\ningningmarket\controller;

use think\Db;
use think\Request;

class Auth{
    public function isUser($hash){
        return true;
    }

    public function isAdmin($hash){
        return true;
    }

    public function signIn($code){
        return $hash;
    }
}

?>
