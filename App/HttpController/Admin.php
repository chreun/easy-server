<?php

namespace App\HttpController;


use App\Service\OrderService;
use App\Service\ProjectService;
use App\Service\UserService;

class Admin extends Base
{

    protected $needAdmin = true;

    public function userList(){
        $page = $this->queryParam("page", 1);
        $data['list'] = UserService::paginate($page);
        $data['page'] = $page;
        return $this->outData(0, '', $data);
    }

    public function projectList(){
        $page = $this->queryParam("page", 1);
        $data['list'] = ProjectService::paginate($page);
        $data['page'] = $page;
        return $this->outData(0, '', $data);
    }


    public function userAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['username'] ?? '')) {
            return $this->outData(101, '请输入用户名');
        }
        if(empty($data['portrait'] ?? '')) {
            return $this->outData(102, '请上传头像');
        }
        $userId =  UserService::addUser($data);
        return $this->outData(0, '新增成功', ['user_id' => $userId]);
    }


    public function projectAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['username'] ?? '')) {
            return $this->outData(101, '请输入用户名');
        }
        if(empty($data['portrait'] ?? '')) {
            return $this->outData(102, '请上传头像');
        }
        if(empty($data['image_list'] ?? [])) {
            return $this->outData(103, '请上传证明图片');
        }
        $userId =  ProjectService::addProject($data);
        return $this->outData(0, '新增成功', ['user_id' => $userId]);
    }



    public function orderAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['project_id'] ?? '')) {
            return $this->outData(101, '项目信息为空');
        }
        if(empty($data['user_id'] ?? '')) {
            return $this->outData(102, '请选择用户');
        }
        $userId =  OrderService::addOrder($data);
        return $this->outData(0, '新增成功', ['user_id' => $userId]);
    }
}