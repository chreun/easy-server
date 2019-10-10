<?php


namespace App\HttpController;


use App\Service\BaseService;
use App\Service\JsService;
use App\Service\OrderService;
use App\Service\SysConfService;
use App\Service\UserService;
use EasySwoole\Pay\Pay;
use EasySwoole\Pay\WeChat\Config;
use EasySwoole\Pay\WeChat\RequestBean\OfficialAccount;

class Wechat extends Base
{

    protected $needAuth = false;


    const BASE_OUT_NO = 10000000;

    public function pay(){
        $totalFee = $this->queryParam('total_fee', 0);
        $projectId = $this->queryParam('id');
        $token = $this->request()->getCookieParams("tokenAuth");

        if(empty($token)) {
            return $this->outData(100, 'token状态异常');
        }
        $userInfo = UserService::getUserByToken($token);
        if(empty($userInfo)) {
            return $this->outData(101, '获取用户状态异常');
        }

        $orderId = OrderService::addOrder([
            'user_id' => $userInfo['id'],
            'project_id' => $projectId,
            'amount' => $totalFee,
        ]);

        $officialAccount = new OfficialAccount();
        $officialAccount->setOpenid($userInfo['openid']);
        $officialAccount->setOutTradeNo($orderId + self::BASE_OUT_NO);
        $officialAccount->setBody('开始支付:' . $orderId);
        $officialAccount->setTotalFee(intval($totalFee ));
        $officialAccount->setSpbillCreateIp($this->request()->getHeader('x-real-ip')[0]);
        $pay = new Pay();
        $params = $pay->weChat($this->wechatConfig())->officialAccount($officialAccount);
        BaseService::logInfo("PAY_PARAM:" . json_encode($params->toArray()));
        return $this->outData(0, '', $params->toArray());
    }


    public function getWxUrl(){
        $arr['appid'] = SysConfService::wxAppId();
        $arr['redirect_uri'] = SysConfService::baseUri() . '/api/wechat/codeCallback?id=' . $this->queryParam('id');
        $arr['response_type'] = 'code';
        $arr['scope'] = 'snsapi_userinfo';
        //$scope='snsapi_base';
        $arr['state'] = 'STATE';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($arr) . '#wechat_redirect';
        $this->outData(0, '', $url);
    }


    public function codeCallback(){
        $code = $this->queryParam('code');
        $projectId = $this->queryParam('id');
        $arr['appid'] = SysConfService::wxAppId();
        $arr['secret'] = SysConfService::wxSecret();
        $arr['code'] = $code;
        $arr['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($arr);

        $res = BaseService::curlGet($url);
        $oauth2 = json_decode($res, true);
        BaseService::logInfo("CODE_CALLBACK:" . json_encode(['code' => $code, 'res' => $oauth2]));


        $access_token = $oauth2["access_token"];
        $openid = $oauth2['openid'];


        $wxUserUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=". $access_token."&openid=". $openid . "&lang=zh_CN";
        $wxUser = BaseService::curlGet($wxUserUrl);

        $wxUserArr = json_decode($wxUser, true);


        $updateData = [
            'username' => $wxUserArr['nickname'],
            'portrait' => $wxUserArr['headimgurl'],
            'openid' => $openid,
        ];
        $userInfo = UserService::getUserByField('openid', $openid);

        if(empty($userInfo)) {
            $userId = UserService::addRealUser($updateData);
        } else {
            $userId = $userInfo['id'];
            UserService::save($userId, $updateData);
        }

        BaseService::logInfo("WX_USER_INFO:" . json_encode(['openid' => $openid, 'wx_user' => $wxUser, 'user_id' => $userId]));

        $token = $this->generateToken();
        UserService::saveToken($userId, $token);
        $this->response()->redirect("/pay.html?id={$projectId}&token={$token}");
    }


    private function wechatConfig(){
        global $baseDir;
        $wechatConfig = new Config();
        $wechatConfig->setAppId(SysConfService::wxAppId());
        $wechatConfig->setMchId(SysConfService::wxMchId());
        $wechatConfig->setKey(SysConfService::wxApiKey());
        $wechatConfig->setNotifyUrl(SysConfService::baseUri() . '/api/wechat/payCallback');
        $wechatConfig->setApiClientCert($baseDir . "/Cert/apiclient_cert.pem");//客户端证书
        $wechatConfig->setApiClientKey($baseDir . "/Cert/apiclient_key.pem"); //客户端证书秘钥
        return $wechatConfig;
    }

    public function payCallback(){
        $content = $this->request()->getBody()->__toString();
        $pay = new Pay();
        $wechatConf = $this->wechatConfig();
        $data = $pay->weChat($wechatConf)->verify($content);
        if($arr = json_decode($data->__toString(), true)) {
            OrderService::save($arr['out_trade_no'] - self::BASE_OUT_NO, [
                'is_pay' => 1
            ]);
        }
        $this->response()->write($pay->weChat($wechatConf)->success());
    }

    public function index()
    {
        $signature = $this->queryParam("signature");
        $timestamp = $this->queryParam("timestamp");
        $nonce = $this->queryParam("nonce");

        $token = SysConfService::wxToken();
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            $this->response()->write($this->queryParam("echostr"));
        }else{
            $this->response()->write('校验失败');
        }
    }

    public function shareParam(){
        $url = $this->queryParam('url');
        $ret = (new JsService())->getJsConfig($url);
        BaseService::logInfo('shareParam:' . $url . '  ' . json_encode($ret));
        $this->outData(0, '', $ret);
    }


}