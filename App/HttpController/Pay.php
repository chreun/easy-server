<?php


namespace App\HttpController;


use EasySwoole\Pay\WeChat\Config;
use EasySwoole\Pay\WeChat\RequestBean\OfficialAccount;

class Pay extends Base
{

    public function index(){

        $wechatConfig = new Config();

        
        $wechatConfig->setAppId('xxxxxx');      // 除了小程序以外使用该APPID
        $wechatConfig->setMiniAppId('xxxxxx');  // 小程序使用该APPID
        $wechatConfig->setMchId('xxxxxx');
        $wechatConfig->setKey('xxxxxx');
        $wechatConfig->setNotifyUrl('xxxxx');
        $wechatConfig->setApiClientCert('xxxxxxx');//客户端证书
        $wechatConfig->setApiClientKey('xxxxxxx'); //客户端证书秘钥


        $officialAccount = new OfficialAccount();
        $officialAccount->setOpenid('xxxxxxx');
        $officialAccount->setOutTradeNo('CN' . date('YmdHis') . rand(1000, 9999));
        $officialAccount->setBody('xxxxx-测试' . $outTradeNo);
        $officialAccount->setTotalFee(1);
        $officialAccount->setSpbillCreateIp('xxxxx');
        $pay = new \EasySwoole\Pay\Pay();
        $params = $pay->weChat($wechatConfig)->officialAccount($officialAccount);
    }

}