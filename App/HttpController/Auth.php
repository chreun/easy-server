<?php

namespace App\HttpController;


use App\Common\RegExp;
use App\Service\UserService;

class Auth extends Base
{

    protected $needAuth = false;

    /**
     * 注册
     */
    public function register()
    {
        $mobile = $this->inputParam("username");
        if(!preg_match(RegExp::MOBILE, $mobile)) {
            $this->outData(101, "手机号格式错误");
            return;
        }
        if(UserService::getUserByMobile($mobile)) {
            $this->outData(102, "该手机号已注册");
            return;
        }
        $password = $this->inputParam("password");
        if (!preg_match(RegExp::PASSWORD, $password)) {
            $this->outData(103, "密码格式错误,至少包含数字和字母且不少于6位");
            return;

        }
        $repeat_password = $this->inputParam("repeat_password");
        if($repeat_password != $password) {
            $this->outData(104, "确认密码错误");
            return;
        }
        $user_id = UserService::register($mobile, $password);
        if(empty($user_id)) {
            $this->outData(105, "注册失败,服务器异常,请联系客服");
            return;
        }
        $this->outData(0, "ok", ['user_id' => $user_id]);
    }


    /**
     * 登录
     */
    public function login()
    {
        $password = $this->inputParam("password");
        if (empty($password)) {
            $this->outData(202, "密码为空");
            return;
        }
        $username = $this->inputParam("username");
        if(preg_match(RegExp::MOBILE, $username)) {
            $userInfo = UserService::getUserByMobile($username);
        } else {
            $userInfo = UserService::getUserByUserName($username);
        }
        if(empty($userInfo)) {
            $this->outData(203, "该用户名或手机号不存在");
            return;
        }
        $userId = $userInfo['id'];
        if(!UserService::checkPassword($userId, $password)) {
            $this->outData(204, "登录失败,密码错误");
            return;
        }
        $token = $this->generateToken($userId);
        UserService::saveToken($userId, $token);
        $this->outData(0, "ok", ['token' => $token, 'userInfo' => $userInfo]);
    }

    private function generateToken($userId){
        return md5(uniqid($userId) . rand(10000, 99999));
    }


    public function upload(){
        global $baseDir;
        $this->response()->withHeader('access-control-allow-credentials','true');
        $this->response()->withHeader('access-control-allow-origin','http://127.0.0.1:8080');
        $this->response()->withHeader('access-control-expose-headers','Location');
        if($this->request()->getMethod() != 'POST') {
            return $this->outData(0, '');
        }
        $file = $this->request()->getUploadedFile('file');
        $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $detFile = uniqid() . '.' . $ext;
        $this->log($file->moveTo($baseDir . "/Static/image/" . $detFile));
        return $this->outData(0, '', ['file' =>  'http://127.0.0.1/image/' . $detFile]);
    }

}