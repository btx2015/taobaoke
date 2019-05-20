<?php
namespace Tb\Controller;
use Think\Controller;
class CommunityController extends Controller {
	const T_ARTICLE = 'tr_article';
	const T_ARTICLE_CATE = 'tr_article_cate';
    public function article(){
    	$model = M(self::T_ARTICLE);
    	$article = $model->where(['id'=>$_GET['id']])->find();
    	$this->assign('article',$article);
        $this->display();
    }

    public function cate(){
    	$model = M(self::T_ARTICLE_CATE);
        $models = M(self::T_ARTICLE);
    	$cate = $model->field('id,name,pid,sort')->select();
        foreach ($cate as $key => $value) {
            $cate[$key]['children'] = $models->where(['cate_id'=>'4'])->limit(10)->select();
        }
    	returnResult($cate);
    }

    public function index(){
        $model = M(self::T_ARTICLE_CATE);
        $models = M(self::T_ARTICLE);
        $cate = $model->field('id,name,pid,sort')->select();
        foreach ($cate as $key => $value) {
            if ($key==0) {
                $cate[$key]['isone'] = 1 ;
            }else{
                $cate[$key]['isone'] = 0;
            }
            $cate[$key]['children'] = $models->where(['cate_id'=>$value['id']])->limit(10)->select();
        }
        $this->assign('cates',$cate);
        $this->display();
    }

    public function getpage(){
        $model = M(self::T_ARTICLE);
        $data = $model->limit(10)->select();
        foreach ($data as $key => $value) {
            $data[$key]['content'] = htmlspecialchars_decode($value['content']);
        }
        returnResult($data);
    }
}