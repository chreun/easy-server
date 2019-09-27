<?php

namespace App\HttpController;




use App\Service\UserService;

class User extends Base
{

    public function cur()
    {
        $userInfo = UserService::userInfo($this->curUserId);
        return $this->outData(0, '', $userInfo);
    }

}