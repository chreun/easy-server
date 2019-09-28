<?php

namespace App\HttpController;


use App\Service\BaseService;
use App\Service\DynamicService;
use App\Service\OrderService;
use App\Service\ProjectService;
use App\Service\ProveService;
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
        foreach ($data['list']  as $k => $v) {
            $data['list'][$k]['url'] = rtrim(BaseService::baseUri(), '/') . '/#/project?id=' . $v['id'];
        }
        $data['page'] = $page;
        return $this->outData(0, '', $data);
    }

    public function orderList(){
        $page = $this->queryParam("page", 1);
        $data['list'] = OrderService::paginate($page);
        $data['page'] = $page;
        return $this->outData(0, '', $data);
    }

    public function userAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['username'] ?? '')) {
            return $this->outData(101, '请输入用户名');
        }
        if(empty($data['real_name'] ?? '')) {
            return $this->outData(101, '请输入真实姓名');
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

    public function dynamicAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['project_id'] ?? '')) {
            return $this->outData(101, '项目ID为空');
        }
        if(empty($data['title'] ?? '')) {
            return $this->outData(102, '动态描述不能为空');
        }
        DynamicService::addDynamic($data);
        return $this->outData(0, '新增成功', $data);
    }


    public function orderAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['project_id'] ?? '')) {
            return $this->outData(101, '项目ID为空');
        }
        if(empty($data['amount'] ?? '')) {
            return $this->outData(102, '价格不能为空');
        }
        if(empty($data['encourage'] ?? [])) {
            return $this->outData(103, '鼓励语不能为空');
        }
        $data['user_id'] = $this->randomUser($data['project_id'], OrderService::TABLE_NAME);
        if(empty($data['user_id'])) {
            return $this->outData(104, '暂无新用户可添加,请新增用户');
        }
        OrderService::addOrder($data);
        return $this->outData(0, '新增成功', $data);
    }


    public function proveAdd(){
        $data = $this->request()->getParsedBody();
        if(empty($data['project_id'] ?? '')) {
            return $this->outData(101, '项目ID为空');
        }
        if(empty($data['relation'] ?? '')) {
            return $this->outData(102, '关系不能为空');
        }
        if(empty($data['introduce'] ?? [])) {
            return $this->outData(103, '请输入介绍');
        }
        $data['user_id'] = $this->randomUser($data['project_id'], ProveService::TABLE_NAME);
        if(empty($data['user_id'])) {
            return $this->outData(104, '一个用户仅可操作一次,请新增用户');
        }
        ProveService::addProve($data);
        return $this->outData(0, '新增成功', $data);
    }

    protected function randomUser($projectId, $tableName){
        $existUser = array_column(BaseService::db()->where('project_id', $projectId)
            ->get($tableName, null, 'user_id'), 'user_id');


        $model = UserService::db()->where('user_type', UserService::USER_TYPE_VIRTUAL);
        if($existUser) {
            $model = $model->whereNotIn('id', $existUser);
        }
        $userArr = $model->get('users', null, 'id');
        if(empty($userArr)) {
            return null;
        }
        $rand = rand(0, count($userArr) - 1);
        return $userArr[$rand]['id'];
    }
}