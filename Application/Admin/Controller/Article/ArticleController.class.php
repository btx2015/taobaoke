<?php


namespace Admin\Controller\Article;

use Admin\Controller\CommonController;
use Think\Upload;

class ArticleController extends CommonController
{
    const T_ARTICLE = 'tr_article';

    const T_ARTICLE_CATE = 'tr_article_cate';

    const SAVE_PATH = '/article_img/';

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
            $data = cateFormat($cate);
            $this->assign('cate',$data);
            $this->display();
        }
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
            if(!isset($_FILES['img_upload']))
                showError(10006,'请上传图片');
            if(!isset($_FILES['img_upload']['size']) || !$_FILES['img_upload']['size'])
                showError(10006,'请上传图片');
            $upload = new Upload(); // 实例化上传类
            $upload->savePath = self::SAVE_PATH;// 设置原图上传目录
            $upload->saveName = uniqid();
            $info = $upload->upload();
            if(!$info)
                showError(20002,$upload->getError());
            foreach ($info as $v){
                $data['img'] = '/Uploads'.$v['savepath'].$v['savename'];
            }
            $data['created_at'] = time();
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);//创建失败
            returnResult();
        }else{
            $model = M(self::T_ARTICLE_CATE);
            $cate = $model->field('id,name,pid')
                ->where(['state'=>1])->order('sort desc')->select();
            $data = cateFormat($cate);
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
            if(isset($_FILES['img_upload'])){
                if(!isset($_FILES['img_upload']['size']) || !$_FILES['img_upload']['size'])
                    showError(10006,'请上传图片');
                $upload = new Upload(); // 实例化上传类
                $upload->savePath = self::SAVE_PATH;// 设置原图上传目录
                $upload->saveName = uniqid();
                $info = $upload->upload();
                if(!$info)
                    showError(20002,$upload->getError());
                foreach ($info as $v){
                    $data['img'] = '/Uploads'.$v['savepath'].$v['savename'];
                }
            }
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
            $data = cateFormat($cate);
            $this->assign([
                'cate'=> $data,
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