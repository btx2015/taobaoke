<?php


namespace Admin\Controller\Article;

use Admin\Controller\CommonController;

class ArticleController extends CommonController
{
    const T_ARTICLE = 'tr_article';

    const T_ARTICLE_CATE = 'tr_article_cate';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'title'       => [[],false,true,['like','a.title']],
                'cate'        => [['num'],false,true,['eq','a.cate_id']],
                'author'      => [[],false,true,['like','a.author',]],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],'a');

            $model = M(self::T_ARTICLE);
            $list = $model->alias('a')
                ->field('a.id,a.title,a.cate_id,a.author,a.state,a.sort,a.created_at,b.name')
                ->join('left join '.self::T_ARTICLE_CATE.' b on a.cate_id = b.id')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->alias('a')->where($where)->count()
            ]);
        }else{
            $model = M(self::T_ARTICLE_CATE);
            $cate = $model->field('id,name,pid')
                ->where(['state'=>1])->order('sort desc')->select();
            $data = $this->cateFormat($cate);
            $this->assign('cate',$data);
            $this->display();
        }
    }

    private function cateFormat($cate,$pid = 0,$level = 0){
        $data = [];
        foreach($cate as $k => $v){
            if($v['pid'] == $pid){
                $name = '';
                if($pid)
                    $name = '|';
                for($i = 0;$i < $level;$i ++){
                    $name .= '-';
                }
                $data[] = [
                    'id'   => $v['id'],
                    'name' => $name.$v['name'],
                ];
                unset($cate[$k]);
                $children = $this->cateFormat($cate,$v['id'],$level+1);
                if($children)
                    $data = array_merge($data,$children);
            }
        }
        return $data;
    }

    public function add(){
        if(IS_POST){
            $model = M(self::T_ARTICLE);
            $rule = [
                'title'   => [[],true],
                'cate'    => [[],true,false,['eq','cate_id']],
                'author'  => [],
                'sort'    => [['num']],
                'content' => []
            ];
            $data = beforeSave($model,$rule,['title']);
            $data['created_at'] = time();
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);//创建失败
            returnResult();
        }else{
            $model = M(self::T_ARTICLE_CATE);
            $cate = $model->field('id,name,pid')
                ->where(['state'=>1])->order('sort desc')->select();
            $data = $this->cateFormat($cate);
            $this->assign('cate',$data);
            $this->display();
        }
    }

    public function edit(){
        $model = M(self::T_ARTICLE);
        if(IS_POST){
            $rule = [
                'id'      => [['num'],true,false],
                'title'   => [],
                'cate'    => [[],false,false,['eq','cate_id']],
                'author'  => [],
                'sort'    => [['num']],
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
            $model = M(self::T_ARTICLE_CATE);
            $cate = $model->field('id,name,pid')
                ->where(['state'=>1])->order('sort desc')->select();
            $data = $this->cateFormat($cate);
            $this->assign([
                'cate'=> $cate,
                'data' => $user
            ]);
            $this->display();
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_ARTICLE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}