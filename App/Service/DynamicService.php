<?php


namespace App\Service;




class DynamicService extends BaseService
{
    const TABLE_NAME = 'project_dynamic';


    public static function addDynamic($data){
        $data['create_at'] = $data['create_at'] ?? self::localtime();
        return self::create($data);
    }

    public static function getByProject($id) {
        $data = self::db()->where('project_id', $id)->orderBy('create_at', 'desc')
            ->get(self::TABLE_NAME, null, 'title,image_list,create_at');
        foreach ($data as $k => $v) {
            $data[$k]['image_list'] = (array)self::formatImage($v['image_list']);
            $data[$k]['pre_day'] = self::formatDate(strtotime($v['create_at']));
        }
        return $data;
    }



}