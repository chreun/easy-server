<?php

namespace App\HttpController;




use App\Service\JsService;
use App\Service\SysConfService;

class Index1 extends Base
{
    protected $needAuth = false;


    public function test(){


        return $this->outData(0, '', 1);


    }

}