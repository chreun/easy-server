<?php

namespace App\HttpController;


use App\Service\UserService;

class Admin extends Base
{

    protected $needAdmin = true;

    public function userLists(){
        $page = $this->queryParam("page", 1);
        $data['list'] = UserService::paginate($page);
        $data['page'] = $page;
        return $this->outData(0, '', $data);
    }

}