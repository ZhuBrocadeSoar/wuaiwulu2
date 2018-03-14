<?php
namespace app\ningningmarket\controller;

use think\Db;

class DbDebug{
    public function showTables(){
        $retval = Db::query('SHOW TABLES');
        dump($retval);
    }

    public function descTable($tableName){
        $retval = Db::query('DESC `' . $tableName . '`');
        dump($retval);
    }
}

?>
