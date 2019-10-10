<?php


namespace App\Service;




class OrderService extends BaseService
{
    const TABLE_NAME = 'orders';


    public static function addOrder($data){
        $data['create_at'] = self::localtime();
        $data['encourage'] = self::encourage();
        return self::create($data);
    }

    public static function getByProject($id) {
        return self::db()->where('project_id', $id)
            ->where('is_pay', 1)
            ->get(self::TABLE_NAME, null, 'user_id,amount');
    }

    public static function getByLastId($projectId, $lastId, $pageSize) {
        $model = self::db()->where('project_id', $projectId)
            ->where('is_pay', 1);
        if($lastId > 0) {
            $model = $model->where('id', $lastId, '<');
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


    private static function encourage(){
        $map = [
            '相信明天会更好！',
            '不放弃就一定有奇迹！',
            '加油！坚持就是胜利！',
            '爱的力量一定能帮你战胜病魔！',
            '一切都会好起来的，加油！',
            '加油，我们都支持你！',
            '风雨过后一定能见到彩虹！',
            '祝你早日康复！',
            '你要坚信一切都会过去的！',
        ];
        return $map[rand(0, count($map) - 1)];
    }

}