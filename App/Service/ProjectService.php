<?php


namespace App\Service;



class ProjectService extends BaseService
{
    const TABLE_NAME = 'projects';

    public static function addProject($data){
        $data['create_at'] = self::localtime();
        $insert_id = self::create($data);
        return $insert_id;
    }


}