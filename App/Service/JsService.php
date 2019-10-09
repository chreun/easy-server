<?php


namespace App\Service;


class JsService extends BaseService
{

    private $wxAppId     = '';  //公众号的appid
    private $wxAppSecret = 'XXXXXXXXXXXXXXXXXX'; //公众号的app_secret


    public function __construct()
    {
        $this->wxAppId = SysConfService::wxAppId();
        $this->wxAppSecret = SysConfService::wxSecret();
    }




    public function getJsConfig($url)
    {
        try{
            if(!$url){
                return '缺少url参数';
            }
            $key = 'str:wx:js_ticket';
            $ticket = self::redis()->get($key);
            if(empty($ticket)) {
                $acc_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
                    . $this->wxAppId .'&secret='. $this->wxAppSecret;
                //TODO  实际开发中获取access_token要进行缓存，执行curlRequest前判断是否存在缓存，不存在才进行调用，建议缓存7200秒
                $accToken = json_decode(self::curlGet($acc_url),true) ;
                if(!isset($accToken['access_token'])){
                    return $accToken['errmsg'];
                }
                $accToken = $accToken['access_token'];
                $ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accToken.'&type=jsapi';
                $ticket_res = json_decode(self::curlGet($ticket_url),true);
                if(!isset($ticket_res['ticket'])){
                    return '微信服务器异常,获取ticket异常';
                }
                $ticket = $ticket_res['ticket'];
                self::redis()->set($key, $ticket, 7100);
            }

            //生成随机字符串
            $randStr = md5((string)rand(10000, 99999));
            $time = time();
            $tempSort = [
                'debug'=> true,
                'noncestr'=> $randStr,
                'jsapi_ticket'=> $ticket,
                'timestamp'=> $time,
                'url'=> $url
            ];
            $keyStr = array_flip($tempSort);
            //加密参数是按参数名排序，不是按值排序
            ksort($tempSort,SORT_STRING);
            $params = $tempSort;
            $shaString = '';
            foreach ($params as $key=>$val){
                if($shaString==''){
                    $shaString = $keyStr[$val].'='.$val;
                }else{
                    $shaString.='&'.$keyStr[$val].'='.$val;
                }
            }
            $signature = sha1($shaString);
            $jsConfig = [
                'appId'=>$this->wxAppId,
                'timestamp'=>$time,
                'nonceStr'=>$randStr,
                'signature'=>$signature,
                //此处填写你需要调用的JS列表，比如这里是调用的微信获取地理位置
                'jsApiList'=> ['updateAppMessageShareData', 'updateTimelineShareData'],
                'test'=>[
                    'ticket'=>$ticket,
                    'url'=>$url
                ]
            ];
            return $jsConfig;
        }catch (\Exception $e){
            return '微信服务器异常:' . $e->getMessage();
        }

    }

}
