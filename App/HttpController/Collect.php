<?php

namespace App\HttpController;


use App\Common\DbStruct;
use App\Service\BaseService;
use App\Service\UserService;

class Collect extends Base
{

    private function portrait(){
        return $_SERVER['HTTP_HOST'] . '/images/portrait.png';
    }
    private function display(){
        return $_SERVER['HTTP_HOST'] . '/images/display.png';
    }


    function index()
    {
        $detail['attain_amount'] = 279800;
        $detail['person_count'] = 1744;


        $detail['collect_dynamic'] = [
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'title' => 'XXXXXXX',
                'image_lists' => [ $this->display(), $this->display()],
                'pre_day' => '今天'
            ],
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'title' => 'XXXXXXX',
                'image_lists' => [ $this->display(), $this->display()],
                'pre_day' => '今天'
            ],
        ];



        $detail['prove_count'] = 67;
        $detail['prove_lists'] = [
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'relation' => '同事',
                'introduce' => '情况属实',
                'create_time' => date('Y-m-d H:i:s')
            ],
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'relation' => '同事',
                'introduce' => '情况属实',
                'create_time' => date('Y-m-d H:i:s')
            ],
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'relation' => '同事',
                'introduce' => '不要顶部的已经有X名医护人员那个了, 直接共有66人为他证实就好,下面显示的就用最后一个人的证明就好',
                'create_time' => date('Y-m-d H:i:s')
            ]
        ];

        $detail['collect_count'] = 2625;
        $detail['collect_lists'] = [
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'desc' => 'XXXXXXX',
                'amount' => '1',
                'time' => '1小时前'
            ],
            [
                'portrait' => $this->portrait(),
                'name' => '刘洋',
                'desc' => 'XXXXXXX',
                'amount' => '1',
                'time' => '2小时前'
            ],

        ];

        $this->outData(0, '', $detail);


    }





}