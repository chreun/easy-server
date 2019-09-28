<?php


namespace App\Service;




class OrderService extends BaseService
{
    const TABLE_NAME = 'orders';


    public static function addOrder($data){
        $data['create_at'] = self::localtime();
        return self::create($data);
    }

    public static function getByProject($id) {
        return self::db()->where('project_id', $id)->get(self::TABLE_NAME, null, 'user_id,amount');
    }

    public static function getByLastId($projectId, $lastId, $pageSize) {
        $model = self::db()->where('project_id', $projectId);
        if($lastId > 0) {
            $model = $model->where('id', '<', $lastId);
        }
        $data = $model->orderBy("id", 'desc')
            ->get(self::TABLE_NAME, $pageSize);
        $data = UserService::mergeUserInfo($data);
        foreach ($data as $k => $v) {
            $data[$k]['pre_day'] = self::formatDate(strtotime($v['create_at']));
            $data[$k]['total_ax'] = ($v['user_id'] % 10) * 100 + $v['user_id'] % 100 + rand(1, 200);
            $data[$k]['order_ax'] = intval($v['amount']) % 10  + ($v['id'] % 10) + 5;
        }
        return $data;
    }


}