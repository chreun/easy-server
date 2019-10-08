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
        $rankTmp = [];
        foreach ($orderList as $order) {
            isset($rankTmp[$order['user_id']]) or $rankTmp[$order['user_id']] = 0;
            $rankTmp[$order['user_id']] += $order['amount'];
        }
        arsort($rankTmp);
        $rankUser = [];
        $i = 0;
        foreach (array_slice($rankTmp, 0, 4, true) as  $k => $r) {
            $rankUser[] = ['user_id' => $k, 'class' => 'corwn-wrap0' . (string)$i++];
        }
        $data['rank_user'] = UserService::mergeUserInfo($rankUser);
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
        $data['image_list'] = (array)BaseService::formatImage($data['image_list']);
        return $this->outData(0, '', $data);
    }

    function order(){
        $id = $this->queryParam('id');
        $lastId = $this->queryParam('lastId', 0);
        $pageSize = 20;
        $orderArr = OrderService::getByLastId($id, $lastId, $pageSize);
        $orderArr = UserService::mergeUserInfo($orderArr);
        $data = [
            'list' => $orderArr,
            'lastId' => end($orderArr)['id'],
            'hasNext' => count($orderArr) >= $pageSize
        ];
        return $this->outData(0, 'ok', $data);
    }


    function info(){
        $id = $this->queryParam('id');
        $data = ProjectService::find($id);
        $data['image_list'] = (array)BaseService::formatImage($data['image_list']);
        return $this->outData(0, 'ok', $data);
    }


}