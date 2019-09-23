<?php

namespace App\HttpController;



use App\Common\DbStruct;

class Index extends Base
{
    protected $needAuth = false;


    public function test(){
        $data = [
            'user' => DbStruct::user(),
            'order' => DbStruct::order(),
            'project' => DbStruct::project(),
            'prove' => DbStruct::prove(),
            'dynamic' => DbStruct::dynamic(),
        ];
        return $this->response()->write(json_encode($this->request()->getHeaders()));
    }

}