<?php


namespace App\Service;




class UserService extends BaseService
{
    const TABLE_NAME = 'users';

    const USER_TYPE_COMMON = 0;

    const USER_TYPE_VIRTUAL = 1;

    const USER_TYPE_ADMIN = 2;

    public static function addUser($data){
        $data['create_at'] = self::localtime();
        $data['user_type'] = self::USER_TYPE_VIRTUAL;
        $data['password'] = password_hash(123456, PASSWORD_BCRYPT);;
        $insert_id = self::db()->insert(self::TABLE_NAME, $data);
        return $insert_id;
    }


    /**
     * 暴露给前端的字段
     * @return string
     */
    public static function exposeFiled(){
        return 'id,username,mobile,portrait,user_type';
    }



    public static function userInfo($userId){
        return self::find($userId, self::exposeFiled());
    }

    /**
     * 根据电话获取用户信息
     * @param $mobile
     * @return mixed
     * @throws
     */
    public static function getUserByMobile($mobile) {
        return self::db()->where("mobile", $mobile)->getOne(self::TABLE_NAME, self::exposeFiled());
    }


    /**
     * 根据电话获取用户信息
     * @param $username
     * @return mixed
     * @throws
     */
    public static function getUserByUserName($username) {
        return self::db()->where("username", $username)->getOne(self::TABLE_NAME, self::exposeFiled());
    }

    /**
     * 注册
     * @param $mobile
     * @param $password
     * @return int
     * @throws
     */
    public static function register($mobile, $password):int {
        $data['mobile'] = $mobile;
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        $data['create_at'] = self::localtime();
        $data['last_login'] = self::localtime();
        $data['user_type'] = self::USER_TYPE_COMMON;
        $insert_id = self::db()->insert(self::TABLE_NAME, $data);
        return $insert_id;
    }

    /**
     * 检查密码正确性
     * @param $userId
     * @param string $password
     * @return bool
     * @throws
     */
    public static function checkPassword($userId,string $password):bool {
        $userInfo = self::find($userId, 'password');
        return password_verify($password, $userInfo['password']);
    }

    /**
     * 存储用户token对应用户ID
     * @param $userId
     * @param string $token
     * @return mixed
     */
    public static function saveToken($userId,string $token) {
        $redis = self::redis();
        return $redis->hset(self::tokenKey(), $token,  $userId . ':' . time()) ;
    }

    /**
     * 根据token获取用户信息
     * @param string $token
     * @return int|null
     */
    public static function getIdByToken(string $token) {
        $redis = self::redis();
        $tokenKey = self::tokenKey();
        $userStr = $redis->hGet($tokenKey, $token);
        if(empty($userStr)) {
            return null;
        }
        list($userId, $time) = explode(':', $userStr);
        if(time() - $time > 86400 * 7) {
            $redis->hDel($tokenKey, $token);
            return null;
        }
        return $userId;
    }

    /**
     * 存储token的key
     * @return string
     */
    private static function tokenKey(){
        return 'hash:user:token';
    }


    public static function mergeUserInfo($data){
        if(empty($data)) {
            return $data;
        }
        $userIds = array_unique(array_column($data, 'user_id'));
        $userInfo = self::getIn($userIds, 'id,username,portrait');
        $userInfo = array_column($userInfo, null, 'id');
        foreach ($data as $k => $v) {
            $data[$k]['user_name'] = $userInfo[$v['user_id']]['user_name'] ?? '';
            $data[$k]['portrait'] = $userInfo[$v['user_id']]['portrait'] ?? '';
        }
        return $data;
    }

}