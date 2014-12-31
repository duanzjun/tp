<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends AdminController
{
    public function index()
    {
        $model = M('Article');
        $result=$model->order('order_sort ASC,id DESC')->page(I('get.p',0,'intval').',10')->select();
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
            $article_mod=D('Article');
            if($article_mod->create()){
                $article_mod->cate_name=M('Category')->where('id='.$article_mod->cate_id)->getField('cate_name');
                if(false!==$article_mod->add()){
                    $this->success('文章添加成功',U('article/index'));
                }else{
                    $this->error('文章添加失败');
                }
            }else{
                $this->error($article_mod->getError());
            }
        }else{
            $html_tree=getTreeOption('Category',0,1,array('name'=>'cate_name'));
            $this->assign('html_tree',$html_tree);
            $this->assign('curr','index_add');
            $this->display();
        }
    }

    public function edit()
    {
        $id=I('get.id',0,'intval');
        $article_mod=D('Article');
        if(IS_POST){
            if($article_mod->create()){
                $article_mod->cate_name=M('Category')->where('id='.$article_mod->cate_id)->getField('cate_name');
                if(false!==$article_mod->where('id='.$id)->save()){
                    $this->success('文章编辑成功',U('article/index'));
                }else{
                    $this->error('文章保存失败');
                }
            }else{
                $this->error($article_mod->getError());
            }
        }else{
            $result=$article_mod->where('id='.$id)->find();
            $html_tree=getTreeOption('Category',$result['cate_id'],1,array('name'=>'cate_name'));
            $this->assign('html_tree',$html_tree);
            $this->assign('result',$result);
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
}