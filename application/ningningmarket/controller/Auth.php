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

    public static function isAdmin0($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $row = Db::name('admins_0')
                ->where('token', sha1('woyaochuliubashengjian'))
                ->select();
            if($row == NULL){
                $retval = false;
            }else{
                $retval = true;
            }
        }
        return $retval;
    }

    public static function isAdmin1($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin2($hash){
        if($hash == NULL){
            $retval = false;
        }else{
            $retval = true;
        }
        return $retval;
    }

    public static function isAdmin($hash){
        if(Auth::isAdmin2($hash) 
            || Auth::isAdmin1($hash)
            || Auth::isAdmin0($hash)
        ){
            $retval = true;
        }else{
            $retval = false;
        }
        return $retval;
    }

    public function signIn($code){
        return $hash;
    }
}

?>
