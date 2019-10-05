<?php


namespace App\Service;






use EasySwoole\EasySwoole\Config;

class SysConfService extends BaseService
{


    const STATE_OK = 1;

    const STATE_DEL = 0;


    const TABLE_NAME = 'sys_conf';


    public static function baseUri(){
        return self::getValByKey('base_host');
    }


    public static function getValByKey($key) {
        $ret = self::getConfByKey($key);
        return $ret['conf_val'] ?? '';
    }

    public static function getConfByKey($key) {
        return self::db()->where('conf_key', $key)->getOne(self::TABLE_NAME);
    }

    public static function wxAppId(){
        return self::getValByKey('wx_app_id');
    }

    public static function wxMchId(){
        return self::getValByKey('wx_mch_id');
    }


    public static function wxSecret(){
        return self::getValByKey('wx_secret');
    }


    public static function saveValByKey($key, $val) {
        $conf = self::getConfByKey($key);
        if($conf) {
            self::save($conf['id'], [
                'conf_val' => $val
            ]);
        } else {
            self::create([
                'conf_key' => $key,
                'conf_val' => $val
            ]);
        }
    }


}