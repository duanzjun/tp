<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends AdminController
{
    public function index()
    {
        $user_mod=M('User');
        $lists=$user_mod->order('id DESC')->select();
        $role=M('Role')->getField('id,name');
        $this->assign('role',$role);
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $user_mod=M('User');
            if($user_mod->create()){
                $user_mod->password=md5($user_mod->password);
                $user_id=$user_mod->add();
                if($user_id){
                    $data['user_id']=$user_id;
                    $data['role_id']=$_POST['role'];
                    if(M('RoleUser')->data($data)->add()){
                        $this->success('帐户添加成功',U('User/index'));
                        exit;
                    }
                    $this->error('用户组写入失败');
                }else{
                    $this->error('用户添加成功，用户组写入失败');
                }
            }
            $this->error('帐户添加失败');
        }else{
            $role=M('Role')->order('sort DESC')->select();
            $this->assign('role',$role);
            $this->display();
        }
    }

    public function edit()
    {
        if(IS_POST){
            $user_mod=M('User');
            if($user_mod->create()){
                if(!empty($user_mod->password))
                    $user_mod->password=md5($user_mod->password);
                else
                    unset($user_mod->password);
                if($user_mod->save()){
                    $map['id']=$user_mod->id;
                    $data['role_id']=$user_mod->role;
                    M('RoleUser')->where($user_mod->id)->save($data);
                }
                $this->success('编辑成功',U('User/index'));
                exit;
            }
            $this->error('帐户编辑失败');
        }else{
            $id=I('get.id',0,'intval');
            $list=M('User')->where('id='.$id)->find();
            if(empty($list)){
                $this->error('用户名不存');
            }

            $role=M('Role')->order('sort DESC')->select();
            $this->assign('role',$role);
            $this->assign('list',$list);
            $this->display();
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');

        if(M('User')->delete($id))
        {
            M('RoleUser')->where('user_id='.$id)->delete();
            $this->ajaxReturn(true);
        }
        $this->ajaxReturn(false);
    }

    /**
     * AJAX更新数据
    */
    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $fields=I('get.field','','trim');
        $val=I('get.val','','trim');

        if(empty($id) || !in_array($fields,array('status'))){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }

        $data[$fields]=$val;
        M('User')->where('id='.$id)->save($data);
        $this->ajaxReturn(array('status'=>true,'rel'=>$data),'JSON');
    }
}