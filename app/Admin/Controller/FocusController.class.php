<?php
namespace Admin\Controller;
use Think\Controller;
/*首页轮播图片广告管理*/
class FocusController extends AdminController
{
    public function index()
    {
        $adv_mod = M('Adv');
        $lists = $adv_mod->select();
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $adv_mod = M('Adv');
            $data = array();
            $data['adv_title'] = I('adv_title','广告图','trim');
            $data['adv_link'] = I('adv_link','#','trim');
            $data['adv_sort'] = I('adv_sort',0,'intval');
            $data['adv_pic'] = I('adv_pic',0,'trim');
            if($adv_mod->add($data)){
                $this->success('添加成功',U('focus/index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->display('adv_form');
        }
    }

    public function edit()
    {
        $adv_mod=M('Adv');
        if(IS_POST)
        {
            $data = array();
            $data['adv_title'] = I('adv_title','广告图','trim');
            $data['adv_link'] = I('adv_link','#','trim');
            $data['adv_sort'] = I('adv_sort',0,'intval');
            $data['adv_pic'] = I('adv_pic',0,'trim');
            if($adv_mod->where('adv_id='.I('post.adv_id',0,'intval'))->save($data)){
                $this->success('广告编辑成功',U('focus/index'));
            }else{
                $this->error('广告编辑失败');
            }
        }else{
            $id=I('get.id',0,'intval');
            $lists = $adv_mod->where('adv_id = '.$id)->find();
            $this->assign('list',$lists);
            $this->display('adv_form');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $adv_mod=M('Adv');
        $lists=$adv_mod->where('adv_id='.$id)->find();
        if($lists){
            if($lists['adv_pic'] && file_exists(SITE_PATH.'/'.$lists['adv_pic'])){
                @unlink(SITE_PATH.'/'.$lists['adv_pic']);
            }
            $adv_mod->where('adv_id='.$id)->delete();
            $this->ajaxReturn(true);
        }
    }

    public function adupload()
    {
        $this->ajaxUpload();
    }
}