<?php
namespace Admin\Controller;
use Think\Controller;

class NewsController extends AdminController
{
    // public function index()
    // {
    //     $cid=I('cid',0,'intval');
    //     $model = M('Article');
    //     $map=array('cate_id'=>$cid);
    //     $result=$model->where($map)->order('order_sort ASC,id DESC')->page(I('get.p',0,'intval').',10')->select();
    //     $count=$model->count();
    //     $Page=new \Think\Page($count,10);
    //     $Page->setConfig('prev','上一页');
    //     $Page->setConfig('next','下一页');
    //     $show=$Page->show();
    //     $this->assign('page',$show);
    //     $this->assign('result',$result);
    //     $this->assign('curr','index');
    //     $this->display();
    // }

    // public function list_cate()
    // {
    //     $cate_mod=D('Category');
    //     $categories=$cate_mod->select();
    //     $lists=list_to_tree($categories);
    //     $article_cnt=M('Article')->field('cate_id,count(\'cate_id\') as c')->group('cate_id')->select();
    //     foreach($article_cnt as $cnt){
    //         $artCnt[$cnt['cate_id']]=$cnt['c'];
    //     }
    //     $this->assign('ctype',$cate_mod->_ctype);
    //     $this->assign('artcnt',$artCnt);
    //     $this->assign('lists',$lists);
    //     $this->display();
    // }

    public function add()
    {
        if(IS_POST){
            $news_mod = D('News');
            if($news_mod->create()){
                if($news = $news_mod->where('cateid = '.$news_mod->cateid)->find()){ //修改
                    $cate = M('Categories')->where('id='.$news_mod->cateid.' AND ctype = 1')->find();
                    if($cate){
                        $news_mod->create($_POST,2); //设置状态为更新
                        if(false!==$news_mod->where('id='.$news['id'])->save()){
                            $this->success('新闻修改成功',U('news/add',array('catid'=>$cate['id'])));
                        }else{
                            $this->error('新闻修改失败');
                        }
                    }else{
                        if(false!==$news_mod->add($_POST)){
                            $this->success('新闻添加成功',U('news/lists',array('catid'=>$_POST['cateid'])));
                        }else{
                            $this->error('新闻添加失败');
                        }
                    }
                }else{ //添加
                    $cate = M('categories')->where('id='.$news_mod->cateid)->find();
                    if(empty($cate)){
                        $this->error('新闻栏目不正确');
                    }
                    if(false!==$news_mod->add()){
                        $this->success('新闻添加成功');
                    }else{
                        $this->error('新闻添加失败');
                    }
                }
            }else{
                $this->error($news_mod->getError());
            }
        }else{
            $catid = I('get.catid',0,'intval');
            $news_mod = M('News');
            $cate = M('Categories')->where('id='.$catid.' AND ctype = 1')->find();
            if($cate){
                $list = $news_mod->where('cateid = '.$catid)->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }

    public function lists()
    {
        $news_mod = M('News');
        $catid = I('catid',0,'intval');
        $lists = $news_mod->where('cateid = '.$catid)->order('id DESC')->page(I('get.p',0,'intval').',15')->select();
        $count=$news_mod->count();
        $Page=new \Think\Page($count,15);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show=$Page->show();
        $this->assign('page',$show);
        $this->assign('lists',$lists);
        $this->assign('catid',$catid);
        $this->display();
    }

    public function edit()
    {
        $id=I('get.id',0,'intval');
        $news_mod=D('News');
        if(IS_POST){
            if($news_mod->create($_POST,2)){
                $cateid = $news_mod->cateid;
                if(false!==$news_mod->where('id='.$id)->save()){
                    $this->success('新闻编辑成功',U('news/lists',array('catid'=>$cateid)));
                }else{
                    $this->error('新闻保存失败');
                }
            }else{
                $this->error($news_mod->getError());
            }
        }else{
            $list=$news_mod->where('id='.$id)->find();
            $this->assign('list',$list);
            $this->display('add');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $news_mod=M('News');
        $data=$news_mod->where('id='.$id)->delete();
        if($data>0)
            $this->ajaxReturn(true);
        else
            $this->ajaxReturn(false);
    }
}