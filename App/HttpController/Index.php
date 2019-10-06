<?php

namespace App\HttpController;



use App\Common\DbStruct;
use EasySwoole\WeChat\OfficialAccount\OfficialAccount;

class Index extends Base
{
    protected $needAuth = false;


    public function test(){


        setcookie('token', 'ddadasdadas');
        $this->response()->redirect('/index.html?id=1');

    }

}