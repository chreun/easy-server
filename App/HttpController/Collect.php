<?php

namespace App\HttpController;


use App\Service\DynamicService;
use App\Service\OrderService;
use App\Service\ProjectService;
use App\Service\ProveService;
use App\Service\UserService;

class Collect extends Base
{


    function index()
    {
        $project_id = $this->queryParam("project_id");
        $data = ProjectService::find($project_id);
        $orderList = OrderService::getByProject($project_id);
        $data['attain_amount'] = array_sum(array_column($orderList, 'amount'));
        $data['person_count'] = count(array_unique(array_column($orderList, 'user_id')));
        $data['collect_dynamic'] = DynamicService::getByProject($project_id, $data);
        $proveList = ProveService::getByProject($project_id);
        $data['prove_count'] = count($proveList);
        $sliceProveList = array_slice($proveList, 0, 7);
        $data['prove_lists'] = UserService::mergeUserInfo($sliceProveList);
        $data['collect_count'] = count($orderList);
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