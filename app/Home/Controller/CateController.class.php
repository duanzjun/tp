<?php
namespace Home\Controller;
use Think\Controller;
class CateController extends CommonController
{
    public function index()
    {
        $model = D('Categories');
        $result=$model->order('add_time DESC')->select();
        $this->assign('result',$result);
        $this->assign('curr','cate');
        $this->display();
    }

    public function add()
    {
        $model=D('Categories');
        $result=$model->order('add_time DESC')->select();
        $this->assign('result',$result);
        $this->assign('curr','cate_add');
        $this->display();
    }

    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $field=I('get.field','','trim');

        if(!in_array($field,array('status','ordid')) || empty($id)){
            $this->ajaxReturn(false,'json');
        }
        $model=D('Categories');
        $data[$field]=I('get.val',0,'intval');

        $model->where('id='.$id)->save($data);
        $this->ajaxReturn($data,'json');
    }
}