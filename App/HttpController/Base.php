<?php

namespace App\HttpController;



use App\Service\UserService;
use EasySwoole\EasySwoole\Logger;
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
            $userId = UserService::getIdByToken($token);
            if(empty($userId)) {
                $this->outData(100, "登录已过期");
                return false;
            }
            $this->curUserId = $userId;
            if(!$this->needAdmin) {
                return true;
            }
            $userInfo = UserService::userInfo($userId);
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
    }


}