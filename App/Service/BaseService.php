<?php


namespace App\Service;




use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Mysqli\Mysqli;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\RedisPool\Redis;

class BaseService
{
    const TABLE_NAME = '';
    const PRIMARY_NAME = 'id';
    const PAGE_SIZE = 15;


    const STATE_OK = 1;

    const STATE_DEL = 0;



    /**
     * @return Mysqli
     */
    public static function db() {
        try {
            return Mysql::getInstance()->pool('mysql')::defer();
        } catch (\Exception $e) {
            return self::log()->error("db error :" . $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    /**
     * @return \Redis
     */
    public static function redis() {
        try {
            return Redis::getInstance()->pool('redis')::defer();
        } catch (\Exception $e) {
            return self::log()->error("redis error :" . $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    public static function localtime() {
        return date('Y-m-d H:i:s');
    }

    /**
     * @param $id
     * @param $column
     * @return Mysqli|mixed|null
     * @throws
     */
    public static function find($id, $column = '*') {
        try {
            return  self::db()->where(static::PRIMARY_NAME, $id)
                ->getOne(static::TABLE_NAME, $column);
        } catch (\Exception $e) {
            self::log()->error("find error: " . static::TABLE_NAME . ':' . $id);
            return null;
        }
    }


    /**
     * @return Mysqli|mixed|null
     * @throws \Throwable
     */
    public static function all() {
        return self::db()->get(static::TABLE_NAME);
    }

    /**
     *
     * @param $data
     * @return Mysqli|mixed|null
     * @throws
     */
    public static function create($data) {
        try{
            return self::db()->insert(static::TABLE_NAME, $data);
        }catch (\Exception $e) {
            self::log()->error('insert error:' . static::TABLE_NAME . $e->getMessage() . $e->getFile() . $e->getLine());
            return null;
        }
    }

    /**
     * @param $ids
     * @param string $column
     * @return Mysqli|mixed
     * @throws
     */
    public static function getIn($ids, $column = '*') {
        return self::db()->whereIn('id', $ids)->get(static::TABLE_NAME, null, $column);
    }

    /**
     * @return Logger
     */
    public static function log(){
        return Logger::getInstance();
    }


    /**
     * @param $page
     * @param string $columns
     * @return Mysqli|mixed|null
     * @throws
     */
    public static function paginate($page, $columns = '*'){
        return self::db()->orderBy('id', 'desc')->get(static::TABLE_NAME,
            [($page-1)* self::PAGE_SIZE, self::PAGE_SIZE], $columns);

    }

    public static function baseUri(){
        return Config::getInstance()->getConf('BASE_HOST');
    }


    public static function formatImage($str){
        if(empty($str)) return $str;
        if(strpos($str, ',') !== false) {
            $arr = explode(',', $str);
            foreach ($arr as  $k => $v) {
                $arr[$k] = $v;
            }
            return $arr;
        }
        return $str;
    }

    public static function formatDate($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 != $c =floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
        return '';
    }

}