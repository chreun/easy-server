<?php

namespace App\HttpController;


use App\Service\BaseService;
use App\Service\DynamicService;
use App\Service\OrderService;
use App\Service\ProjectService;
use App\Service\ProveService;
use App\Service\UserService;

class Project extends Base
{

    protected $needAuth = false;

    function index()
    {
        $project_id = $this->queryParam("id");
        $data = ProjectService::find($project_id);
        $orderList = OrderService::getByProject($project_id);
        $data['attain_amount'] = array_sum(array_column($orderList, 'amount'));
        $data['collect_count'] = count($orderList);
        $data['person_count'] = count(array_unique(array_column($orderList, 'user_id')));
        $data['collect_dynamic'] = DynamicService::getByProject($project_id);
        $proveList = ProveService::getByProject($project_id);
        $data['prove_count'] = count($proveList);
        $sliceProveList = array_slice($proveList, 0, 5);
        $data['prove_lists'] = UserService::mergeUserInfo($sliceProveList);

        foreach (['collect_count', 'attain_amount', 'need_amount'] as $k) {
            $data[$k] = number_format($data[$k]);
        }
        $orderArr = OrderService::getByLastId($project_id, 0, 20);
        $data['order_list'] = $orderArr;

        $data['image_list'] = BaseService::formatImage($data['image_list']);
        return $this->outData(0, '', $data);
    }

    function order(){
        $lastId = $this->queryParam('lastId');
        $id = $this->queryParam('id');
        $pageSize = 20;
        $orderArr = OrderService::getByLastId($id, $lastId, $pageSize);
        $orderArr = UserService::mergeUserInfo($orderArr);
        $data = [
            'list' => $orderArr,
            'lastId' => end($orderArr)['id'],
            'has_next' => count($orderArr) >= $pageSize
        ];
        return $this->outData(0, 'ok', $data);
    }




}