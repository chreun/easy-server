<?php

namespace App\HttpController;



use App\Common\DbStruct;
use App\Service\SysConfService;
use EasySwoole\WeChat\OfficialAccount\OfficialAccount;

class Index extends Base
{
    protected $needAuth = false;


    public function test(){

        SysConfService::getConfByKey('asdas');
        setcookie('tokenAuth1', '1111111111111111111111111111111111');
        $this->response()->redirect('/index.html?id=1');

    }

}