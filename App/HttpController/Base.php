<?php

namespace App\HttpController;



use App\Service\UserService;
use EasySwoole\Http\AbstractInterface\Controller;



class Base extends Controller
{

    protected $needAuth = true;
    protected $needAdmin = false;
    protected $curUserId = null;

    public function onRequest(?string $action): ?bool
    {
        if($this->needAuth) {
            $token = $this->request()->getCookieParams("token");
            if(empty($token)) {
                $this->outData(100, "用户未登录");
                return false;
            }
            $userInfo = UserService::getUserByToken($token);
            if(empty($userInfo)) {
                $this->outData(100, "登录已过期,可能其他人登录挤掉线");
                return false;
            }
            $this->curUserId = $userInfo['id'];
            if(!$this->needAdmin) {
                return true;
            }
            if($userInfo['user_type'] != UserService::USER_TYPE_ADMIN) {
                $this->outData(101, "非管理员用户");
                return false;
            }
            return true;
        } else {
            return true;
        }
    }


    public function index()
    {
        $this->response()->write($this->getActionName());
    }

    protected function inputParam($key, $default = null){
        return $this->request()->getParsedBody($key) ?? $default;
    }

    protected function queryParam($key, $default = null){
        return $this->request()->getQueryParam($key) ?? $default;
    }

    protected function outData($code, $msg, $data = []){
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        $this->response()->write(json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE));
        return true;
    }

    protected function generateToken(){
        return md5(uniqid(rand(10, 50000)) . rand(10000, 99999));
    }


}