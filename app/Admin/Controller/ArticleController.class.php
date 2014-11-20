<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends AdminController
{
    public function index()
    {
        $model = M('Article');
        $result=$model->order('add_time DESC')->page(I('get.p',0,'intval').',10')->select();
        $count=$model->count();
        $Page=new \Think\Page($count,10);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show=$Page->show();
        $this->assign('page',$show);
        $this->assign('result',$result);
        $this->assign('curr','index');
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            extract($_POST);
            $data['add_time']=time();
            if($data['cate_id']>0)
                $category=M('Article_category')->find($data['cate_id']);
            if(empty($category))
                 $this->error('分类不存在');
            $data['cate_name']=$category['cate_name'];
            M('Article')->add($data);
            redirect(U('index/index'));
        }else{
            $art_cate_mod=D('Articlecategory');
            $categories=$art_cate_mod->get_all();
            $this->assign('categories',$categories);
            $this->assign('curr','index_add');
            $this->display();
        }
    }

    public function edit()
    {
        $id=I('get.id',0,'intval');
        $article_mod = M('Article');

        if(IS_POST){
            extract($_POST);
            if($data['cate_id']>0)
                $category=M('Article_category')->find($data['cate_id']);
            if(empty($category))
                $this->error('分类不存在');
            $data['cate_name']=$category['cate_name'];
            $article_mod->where('id='.$id)->save($data);
            $this->success('编辑成功',U('index/index'));
        }else{
            $result=$article_mod->where('id='.$id)->find();
            $art_cate_mod=D('Articlecategory');
            $categories=$art_cate_mod->get_all();
            // foreach($categories as $key=>$val){

            // }


            $this->assign('result',$result);
            $this->assign('categories',$categories);
            $this->display();
        }
    }

    public function del()
    {
        var_dump($_SESSION['_ACCESS_LIST']);exit;
        $id=I('get.id',0,'intval');
        $article_mod=M('Article');
        $data=$article_mod->where('id='.$id)->delete();

        if($data>0)
            $this->ajaxReturn(true);
        else
            $this->ajaxReturn(false);
    }

    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $field=I('get.field','','trim');
        if(!in_array($field,array('visibility','sort')) || empty($id)){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }
        $model=D('Article');
        $data[$field]=I('get.val',0,'intval');

        $model->where('id='.$id)->save($data);
        $this->ajaxReturn(array('status'=>true,'rel'=>$data),'JSON');
    }

    public function images()
    {
        $image=new \Think\Image();
        // $image=
    }
}