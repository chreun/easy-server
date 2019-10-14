<?php
namespace App\Cron;



use App\Service\BaseService;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class AddOrder extends AbstractCronTask
{

    public static function getRule(): string
    {
        // 定时周期 （每分钟一次）
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        // TODO: Implement getTaskName() method.
        // 定时任务名称
        return 'AddOrder';
    }

    static function run(\swoole_server $server, int $taskId, int $fromWorkerId,$flags=null)
    {
        // TODO: Implement run() method.
        // 定时任务处理逻辑
        BaseService::logInfo('run once every minutes');
    }
}