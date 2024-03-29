<?php

namespace App\HttpController;


use App\Service\BaseService;
use App\Service\DynamicService;
use App\Service\OrderService;
use App\Service\ProjectService;
use App\Service\ProveService;
use App\Service\SysConfService;
use App\Service\UserService;
use EasySwoole\Log\Logger;

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
            $data['list'][$k]['url'] = SysConfService::baseUri() . '/raise.html?id=' . $v['id'];
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
        $userId =  UserService::addVirtualUser($data);
        return $this->outData(0, '新增成功', ['user_id' => $userId]);
    }


    public function confAdd(){
        $data = $this->request()->getParsedBody();
        foreach ($data as $k => $v) {
            SysConfService::saveValByKey($k, $v);
        }
        return $this->outData(0, '修改成功', []);
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
           // return $this->outData(103, '请上传证明图片');
        }
        $projectId = $data['id'] ?? 0;
        unset($data['id']);
        if($projectId > 0) {
            ProjectService::save($projectId, $data);
        } else {
            $projectId =  ProjectService::addProject($data);

            $totalDay = 5 * intval($data['need_amount']  / 100000);
            if($totalDay == 0) $totalDay = 5;

            $dynamic['project_id'] = $projectId;
            $dynamic['title'] = '目标金额' . $data['need_amount'] . '元';
            $dynamic['create_at'] = date('Y-m-d 09:15:00', time() - $totalDay * 86400);
            DynamicService::addDynamic($dynamic);
            $totalAmount = 0;

            $timeStamp = strtotime('today') - $totalDay * 86400 + 9 * 3600 + rand(1, 3600);
            $curDay = 1;
            $oneThree = intval($data['need_amount'] / 3);
            $orderArr = [];
            do {
                $arr40 = [1, 2, 3, 5, 10, 15, 20];
                $arr95 = [30, 50, 60, 80];
                $arr99 = [100, 200];
                $rand = rand(1, 100);
                if($rand <= 60) {
                    $insertAmount = $arr40[array_rand($arr40)];
                }elseif ($rand<= 80){
                    $insertAmount = $arr95[array_rand($arr95)];
                } elseif ($rand<= 95) {
                    $insertAmount = $arr99[array_rand($arr99)];
                } elseif ($rand<= 99) {
                    $insertAmount = 500;
                } else {
                    $insertAmount = 1000;
                }
                $order['project_id'] = $projectId;
                $order['amount'] = $insertAmount;
                $order['user_id'] = $this->randomUser($projectId, OrderService::TABLE_NAME);
                $order['is_pay'] = 1;
                $order['create_at'] = date('Y-m-d H:i:s', $timeStamp);
                $order['reply'] = rand(1, 2) == 1 ? OrderService::reply() : '';
                $order['encourage'] = OrderService::encourage();
                $orderArr[] = $order;
                if($totalAmount > intval(  $oneThree / $totalDay * $curDay)) {
                    $curDay++;
                    $timeStamp = strtotime(date('Y-m-d', $timeStamp)) + 86400 + 9*3600 + rand(1, 3600);
                } else {
                    $timeStamp += rand(60, 600);
                }
                if(count($orderArr) >= 1000) {
                    OrderService::addOrderArr($orderArr);
                    $orderArr = [];
                }
                $totalAmount += $insertAmount;
            } while($totalAmount <  $oneThree);
            if($orderArr) {
                OrderService::addOrderArr($orderArr);
            }
        }
        return $this->outData(0, '新增成功', ['id' => $projectId]);
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
        $data['user_id'] = $this->randomUser($data['project_id'], OrderService::TABLE_NAME);
        if(empty($data['user_id'])) {
            return $this->outData(104, '暂无新用户可添加,请新增用户');
        }
        $data['is_pay'] = 1;
        OrderService::addOrder($data, rand(1, 2) == 1);
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
        $ret['id'] = ProveService::addProve($data);
        return $this->outData(0, '新增成功', $ret);
    }

    protected function randomUser($projectId, $tableName){
        $existUser = array_column(BaseService::db()->where('project_id', $projectId)
            ->orderBy('id', 'desc')
            ->get($tableName, 100, 'user_id'), 'user_id');

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


    public function sysConf(){
        return $this->outData(0, '新增成功', array_column(SysConfService::all(), 'conf_val', 'conf_key'));

    }
}