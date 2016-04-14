<?php
namespace Admin\Controller;
use Think\Controller;
/*友情链接管理*/
class LinksController extends AdminController
{
    public function index()
    {
        $link_mod = M('Links');
        $lists = $link_mod->order('yqid DESC')->page(I('get.p',0,'intval').',15')->select();
        $count=$link_mod->count();
        $Page=new \Think\Page($count,15);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show=$Page->show();
        $this->assign('page',$show);
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $link_mod = M('Links');
            $data = array();
            $data['title'] = I('title','','trim');
            $data['linkurl'] = I('linkurl','#','trim');
            $data['linkspic'] = I('linkspic',10,'intval');
            $data['time'] = date('Y-m-d H:i:s',time());
            if($link_mod->add($data)){
                $this->success('添加成功',U('links/index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->display('links_form');
        }
    }

    public function edit()
    {
        $link_mod=M('Links');
        if(IS_POST)
        {
            $data = array();
            $data['title'] = I('title','','trim');
            $data['linkurl'] = I('linkurl','#','trim');
            $data['linkspic'] = I('linkspic',10,'intval');
            if($link_mod->where('yqid='.I('post.yqid',0,'intval'))->save($data)){
                $this->success('编辑成功',U('links/index'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $id=I('get.id',0,'intval');
            $lists = $link_mod->where('yqid = '.$id)->find();
            $this->assign('list',$lists);
            $this->display('links_form');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $link_mod=M('Links');
        $lists=$link_mod->where('yqid='.$id)->find();
        if($lists){
            $link_mod->where('yqid='.$id)->delete();
            $this->ajaxReturn(true);
        }
    }
}