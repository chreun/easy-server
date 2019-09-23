<?php


namespace App\Service;




class ProveService extends BaseService
{
    const TABLE_NAME = 'project_prove';



    public static function getByProject($id) {
        $data = self::db()->where('project_id', $id)->orderBy('id', 'desc')
            ->get(self::TABLE_NAME, null, 'user_id,relation,introduce,create_at');
        return $data;
    }




}