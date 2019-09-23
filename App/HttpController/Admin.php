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

    public function userAdd(){
        $username= $this->inputParam('username');
        if(empty($username)) {
            return $this->outData(101, '请输入用户名');
        }
        $portrait = $this->inputParam('portrait');
        if(empty($portrait)) {
            return $this->outData(101, '请上传头像');
        }
        $userId =  UserService::addUser($username, $portrait);
        return $this->outData(0, '新增成功', ['user_id' => $userId]);
    }

}