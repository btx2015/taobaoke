<?php


namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;

class FaqController extends CommonController
{
    const T_FAQ = 'tr_manage_faq';

    const T_FAQ_CATE = 'tr_manage_faq_cate';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'title'       => [[],false,true,['like','a.title']],
                'cate'        => [['num'],false,true,['eq','a.cate_id']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],'a');

            $model = M(self::T_FAQ);
            $list = $model->alias('a')
                ->field('a.id,a.title,a.cate_id,a.state,a.sort,a.created_at,b.name')
                ->join('left join '.self::T_FAQ_CATE.' b on a.cate_id = b.id')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->alias('a')->where($where)->count()
            ]);
        }else{
            $model = M(self::T_FAQ_CATE);
            $cate = $model->field('id,name')
                ->where(['state'=>['neq',3]])->order('sort desc')->select();
            $this->assign('cate',$cate);
            $this->display();
        }
    }

    public function add(){
        if(IS_POST){
            $model = M(self::T_FAQ);
            $rule = [
                'title'   => [[],true],
                'cate'    => [[],true,false,['eq','cate_id']],
                'content' => []
            ];
            $data = beforeSave($model,$rule,['title']);
            $data['created_at'] = time();
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);//创建失败
            returnResult();
        }else{
            $model = M(self::T_FAQ_CATE);
            $cate = $model->field('id,name')
                ->where(['state'=>['neq',3]])->order('sort desc')->select();
            $this->assign('cate',$cate);
            $this->display();
        }
    }

    public function edit(){
        $model = M(self::T_FAQ);
        if(IS_POST){
            $rule = [
                'id'      => [['num'],true,false],
                'title'   => [],
                'cate'    => [[],false,false,['eq','cate_id']],
                'state'   => [['in'=>[1,2,3]]],
                'content' => []
            ];
            $data = beforeSave($model,$rule,['title']);
            $cate = $model->where(['id'=>$data['id']])->find();
            if(!$cate)
                showError(20004);
            if($model->save($data) === false)
                showError(20002);
            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);
            $user['content'] = stripcslashes(htmlspecialchars_decode($user['content']));
            $model = M(self::T_FAQ_CATE);
            $cate = $model->field('id,name')
                ->where(['state'=>['neq',3]])->order('sort desc')->select();
            $this->assign('cate',$cate);
            $this->assign('data',$user);
            $this->display();
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_FAQ);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}