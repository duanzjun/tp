<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends AdminController
{
    var $_member_mod;
    public function _initialize()
    {
        $this->_member_mod=D('Member');
    }

    public function _before_index()
    {
        //用户角色列表
        $role=M('Role')->getField('id,name');
        $this->assign('role',$role);
    }

    public function add()
    {
        if(IS_POST){
            if($this->_member_mod->create()){
                $this->_member_mod->password=md5($this->_member_mod->password);
                $user_id=$this->_member_mod->add();
                if($user_id){
                    $data=array(
                        'user_id'=>$user_id,
                        'role_id'=>$_POST['role']
                    );
                    if(M('RoleUser')->data($data)->add()){
                        $this->success('用户添加成功',U('user/index'),IS_AJAX);
                        exit;
                    }else
                        $this->error('用户组写入失败','',IS_AJAX);
                }else{
                    $this->error('用户添加成功，用户组写入失败','',IS_AJAX);
                }
            }else
                $this->error($this->_member_mod->getError());
        }else{
            $role=M('Role')->where('status=1')->order('sort DESC')->select();
            $this->assign('role',$role);
            $this->assign('redio_status',$this->_member_mod->_status);
            $this->display();
        }
    }

    public function edit()
    {
        if(IS_POST){
            $member_mod=M('Member');
            if($member_mod->create()){
                //密码框有值，修改密码
                if(!empty($member_mod->password))
                    $member_mod->password=md5($member_mod->password);
                else
                    unset($member_mod->password);

                if($member_mod->save()){
                    $map['id']=$member_mod->id;
                    M('RoleUser')->where('user_id='.$member_mod->id)->save(array('role_id'=>$this->_member_mod->role));
                    $this->success('编辑成功','',IS_AJAX);
                    exit;
                }else
                    $this->error('所属角色编辑失败','',IS_AJAX);
            }else
                $this->error('帐户编辑失败','',IS_AJAX);
        }else{
            $id=I('get.id',0,'intval');
            $list=$this->_member_mod->where('id='.$id)->find();
            if(empty($list)){
                $this->error('用户名不存','',IS_AJAX);
            }
            $role=M('Role')->where('status=1')->order('sort DESC')->select();
            $this->assign('role',$role);
            $this->assign('list',$list);
            $this->assign('redio_status',$this->_member_mod->_status);
            $this->display('add');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        if(M('Member')->delete($id))
        {
            M('RoleUser')->where('user_id='.$id)->delete();
            $this->ajaxReturn(true);
        }
        $this->ajaxReturn(false);
    }
}