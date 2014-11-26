<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController
{
    public function _before_index()
    {
        $articles=M('Article')->where('publish=1')->order('order_sort ASC,id DESC')->select();
        $this->assign('articles',$articles);
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