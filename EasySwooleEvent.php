<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Process\HotReload;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\MysqliPool\MysqlPoolException;
use EasySwoole\RedisPool\Redis;
use EasySwoole\RedisPool\RedisPoolException;


class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        $confInstance = Config::getInstance();
        $dbConf = $confInstance->getConf('MYSQL');
        $config = new \EasySwoole\Mysqli\Config($dbConf);
        try {
            Mysql::getInstance()->register('mysql', $config);
            // $poolConf->setMaxObjectNum($configData['maxObjectNum']);
        } catch (MysqlPoolException $e) {
            Logger::getInstance()->waring('db error' . $e->getMessage());
        }

        $redisConf = $confInstance->getConf('REDIS');
        $config = new \EasySwoole\RedisPool\Config($redisConf);

        try {
            Redis::getInstance()->register('redis',$config);
            // $poolConf->setMaxObjectNum($configData['maxObjectNum']);
        } catch (RedisPoolException $e) {
            Logger::getInstance()->waring('redis error' . $e->getMessage());
        }
    }

    public static function mainServerCreate(EventRegister $register)
    {

        //测试环境热加载
        if(Config::getInstance()->getConf('RELOAD') == true) {
            $swooleServer = ServerManager::getInstance()->getSwooleServer();
            $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        }
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}