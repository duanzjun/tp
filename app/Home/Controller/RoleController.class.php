<?php
namespace Home\Controller;
use Think\Controller;
class RoleController extends Controller
{
    public function index()
    {
        $lists=M('Role')->order('sort DESC')->select();
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        $role_mod=M('Role');
        if($role_mod->create()){
            $role_mod->add();
            $this->success('添加成功',U('Role/index'));
            exit;
        }
        $this->error('添加角色失败');
    }

    public function edit()
    {
        $role_mod=M('Role');
        if(IS_POST){
            if($role_mod->create()){
                $role_mod->save();
                $this->success('角色编辑成功',U('Role/index'));
                exit;
            }
            $this->error('编辑角色失败');
        }else{
            $id=I('get.id',0,'intval');
            $list=$role_mod->find($id);
            $this->assign('list',$list);
            $this->display();
        }
    }

    public function ajax_del()
    {
        //检查role_user表是否有关联数据
        //检查access表是否有关联数据
        $id=I('get.id',0,'intval');
        $roleuser=M('RoleUser')->where('role_id='.$id)->find();
        $user=M('User')->where('role='.$id)->find();
        trace('333333333333333');
        if(empty($roleuser) && empty($user)){
            trace('22222');
            // M('Role')->delete($id);
            $this->ajaxReturn(true);
        }
        $this->ajaxReturn(false);
    }
}