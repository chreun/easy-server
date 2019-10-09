<?php

namespace App\HttpController;




use App\Service\JsService;
use App\Service\SysConfService;

class Index1 extends Base
{
    protected $needAuth = false;


    public function test(){

        $url = 'http://mclog.cn/raise.html?id=2';

        $res = (new JsService())->getJsConfig($url);

        return $this->outData(0, '', $res);


    }

}