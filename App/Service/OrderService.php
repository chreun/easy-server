<?php


namespace App\Service;




class OrderService extends BaseService
{
    const TABLE_NAME = 'order';


    public static function addOrder($data){
        $data['create_at'] = self::localtime();
        $insert_id = self::db()->insert(self::TABLE_NAME, $data);
        return $insert_id;
    }

    public static function getByProject($id) {
        return self::db()->where('project_id', $id)->get(self::TABLE_NAME, null, 'user_id,amount');
    }

    public static function getByLastId($projectId, $lastId, $pageSize) {
        $model = self::db()->where('project_id', $projectId);
        if($lastId > 0) {
            $model = $model->where('id', '<', $lastId);
        }
        return $model->orderBy("id", 'desc')
            ->get(self::TABLE_NAME, $pageSize, 'id,user_id,amount,');
    }


}