<?php


namespace App\HttpController;


use App\Service\BaseService;
use App\Service\OrderService;
use App\Service\SysConfService;
use App\Service\UserService;
use EasySwoole\Pay\Pay;
use EasySwoole\Pay\WeChat\Config;
use EasySwoole\Pay\WeChat\RequestBean\OfficialAccount;

class Wechat extends Base
{

    protected $needAuth = false;


    public function pay(){
        $totalFee = $this->queryParam('total_fee', 0);
        $projectId = $this->queryParam('project_id');
        $orderId = OrderService::addOrder([
            'project_id' => $projectId,
            'amount' => $totalFee,
            'encourage' => '加油加油!!!',
        ]);

        $officialAccount = new OfficialAccount();
        $officialAccount->setOpenid('xxxxxxx');
        $officialAccount->setOutTradeNo($orderId);
        $officialAccount->setBody('开始支付:' . $orderId);
        $officialAccount->setTotalFee(intval($totalFee * 100));
        $officialAccount->setSpbillCreateIp($this->request()->getHeader('x-real-ip')[0]);
        $pay = new Pay();
        $params = $pay->weChat($this->wechatConfig())->officialAccount($officialAccount);
    }


    public function getCode(){
        $arr['appid'] = SysConfService::wxAppId();
        $arr['redirect_uri'] = SysConfService::baseUri() . '/wechat/codeCallback';
        $arr['response_type'] = 'code';
        $arr['scope'] = 'snsapi_userinfo';
        //$scope='snsapi_base';
        $arr['state'] = 'STATE';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($arr) . '#wechat_redirect';
        $this->outData(0, '', $url);
    }


    public function codeCallback(){
        $code = $this->queryParam('code');
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
            'portrait' => $wxUserArr['headimgurl']
        ];
        $userInfo = UserService::getUserByField('open_id', $openid);
        if(empty($userInfo)) {
            $userId = UserService::addRealUser($updateData);
        } else {
            $userId = $userInfo['id'];
            UserService::save($userId, $updateData);
        }

        $token = $this->generateToken();
        UserService::saveToken($userId, $token);
        $this->response()->redirect("/newURL/index.html");
    }


    private function wechatConfig(){
        global $baseDir;
        $wechatConfig = new Config();
        $wechatConfig->setAppId(SysConfService::wxAppId());
        $wechatConfig->setMchId(SysConfService::wxMchId());
        $wechatConfig->setNotifyUrl(SysConfService::baseUri() . '/wechat/payCallback');
        $wechatConfig->setApiClientCert('$baseDir');//客户端证书
        $wechatConfig->setApiClientKey('$baseDir'); //客户端证书秘钥
    }

    public function payCallback(){
        $content = $this->request()->getBody()->__toString();
        $pay = new Pay();
        $wechatConf = $this->wechatConfig();
        $data = $pay->weChat($wechatConf)->verify($content);
        BaseService::logInfo( 'PAY_NOTIFY:' . var_export($data, 1));
        $this->response()->write($pay->weChat($wechatConf)->success());
    }

    public function index()
    {
        $signature = $this->queryParam("signature");
        $timestamp = $this->queryParam("timestamp");
        $nonce = $this->queryParam("nonce");

        $token = 'mclog';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            $this->response()->write($this->queryParam("nonce"));
        }else{
            $this->response()->write('');
        }
    }

}