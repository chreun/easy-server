<?php

namespace App\HttpController;




use App\Service\SysConfService;

class Index1 extends Base
{
    protected $needAuth = false;


    public function test(){

        $data = SysConfService::getConfByKey('base_uri');
        setcookie('tokenAuth1', '1111111111111111111111111111111111');
        return $this->outData(0, '', [$data , SysConfService::baseUri()]);


    }

}