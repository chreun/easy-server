<?php


namespace App\Service;




class ProjectService extends BaseService
{
    const TABLE_NAME = 'project';

    public static function addProject($data){
        $data['create_at'] = self::localtime();
        $insert_id = self::db()->insert(self::TABLE_NAME, $data);
        return $insert_id;
    }


}