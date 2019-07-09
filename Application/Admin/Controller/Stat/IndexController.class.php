<?php

namespace Admin\Controller\Stat;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class IndexController extends CommonController
{

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','id']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            $model = M(Scheme::STAT);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            returnResult([
                'list' => handleRecords([
                    'created_at'      => ['time','Y-m-d','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }
}