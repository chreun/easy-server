<?php

namespace App\HttpController;



use App\Common\DbStruct;
use EasySwoole\WeChat\OfficialAccount\OfficialAccount;

class Index extends Base
{
    protected $needAuth = false;


    public function test(){



        return $this->response()->write(json_encode($this->request()->getSwooleRequest()));
    }

}