<?php

namespace App\HttpController;




use App\Service\UserService;

class User extends Base
{

    public function cur()
    {
        return $this->outData(0, '', UserService::userInfo($this->curUserId));
    }

}