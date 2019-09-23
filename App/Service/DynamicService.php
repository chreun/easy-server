<?php


namespace App\Service;




class DynamicService extends BaseService
{
    const TABLE_NAME = 'project_dynamic';


    public static function addDynamic($data){
        $data['create_at'] = self::localtime();
        $insert_id = self::db()->insert(self::TABLE_NAME, $data);
        return $insert_id;
    }

    public static function getByProject($id, $project) {
        $data = self::db()->where('project_id', $id)
            ->get(self::TABLE_NAME, null, 'title,image_list,create_at');
        foreach ($data as $k => $v) {
            $data[$k]['username'] = $project['username'];
            $data[$k]['portrait'] = $project['portrait'];
            $data[$k]['image_list'] = json_decode($v['image_list'], true);
            $data[$k]['pre_day'] = self::formatDate(strtotime($v['create_at']));
        }
        return ;
    }

    public static function formatDate($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 != $c =floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
        return '';
    }

}