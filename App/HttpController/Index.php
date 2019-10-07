<?php

namespace App\HttpController;



use App\Common\DbStruct;
use App\Service\SysConfService;
use EasySwoole\WeChat\OfficialAccount\OfficialAccount;

class Index extends Base
{
    protected $needAuth = false;


    public function test(){

        $data = SysConfService::getConfByKey('base_uri');
        setcookie('tokenAuth1', '1111111111111111111111111111111111');
        return $this->outData(0, '', $data);


    }

}