<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends AdminController
{
    public function index()
    {
        $lists=M('Role')->order('sort ASC')->select();
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        $role_mod=M('Role');
        if(IS_POST){
            if($role_mod->create()){
                if($role_mod->add())
                    $this->success('角色添加成功',U('role/index'),IS_AJAX);
                else
                    $this->error('角色添加失败','',IS_AJAX);
            }else
                $this->error($role_mod->getError(),'',IS_AJAX);
        }else{
            $redio_status=array(array('key'=>0,'val'=>'关闭'),array('key'=>1,'val'=>'开启'));
            $this->assign('redio_status',$redio_status);
            $this->display('role_form');
        }
    }

    public function edit()
    {
        $role_mod=M('Role');
        if(IS_POST){
            if($role_mod->create()){
                if($role_mod->save()){
                    $this->success('角色编辑成功',U('role/index'),IS_AJAX);
                    exit;
                }else
                    $this->error('角色保存失败','',IS_AJAX);
            }else{
                $this->error($role_mod->getError(),'',IS_AJAX);
            }
        }else{
            $id=I('get.id',0,'intval');
            $list=$role_mod->find($id);
            $redio_status=array(array('key'=>0,'val'=>'关闭'),array('key'=>1,'val'=>'开启'));
            $this->assign('redio_status',$redio_status);
            $this->assign('list',$list);
            $this->display('role_form');
        }
    }

    /**
     * 分组权限设置
    */
    public function set_priv()
    {
        if(IS_POST){
            $id=I('post.id',0,'intval');
            $node_ids=I('post.node_id');
            $access_mod=D('Access');
            $access_mod->where('role_id='.$id)->delete();
            $map['id']=array('IN',$node_ids);
            $menues=D('Menu')->field('id,pid,level')->where($map)->select();
            $data=array();
            foreach($menues as $k=>$n){
                $data[$k]['role_id']=$id;
                $data[$k]['node_id']=$n['id'];
                $data[$k]['pid']=$n['pid'];
                $data[$k]['level']=$n['level'];
            }
            $access_mod->addAll($data);
            $this->success('权限设置成功！',U('role/index'));
        }else{
            $id=I('get.id',0,'intval');
            $access=M('Access')->where('role_id='.$id)->getField('menu_id',true);
            $menu_mod=M('Menu');
            //设置选中菜单
            $menues=$menu_mod->where('status=1')->select();
            if(is_array($access) && !empty($access)){
                foreach($menues as $k=>$n){
                    $menues[$k]['checked']=in_array($n['id'],$access) ? 'checked="checked"' : '';
                }
            }
            $tree=new \Org\Util\Tree;
            $str="<tr id='node-\$id' class='child-of-node-\$pid'><td>\$spacer <input type='checkbox' onclick='checknode(this)' level='\$level' name='node_id[]' value='\$id' \$checked /> \$name</td></tr>";
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $tree->init($menues);
            $html_tree = $tree->get_tree(0, $str);
            $this->assign('html_tree',$html_tree);
            $this->assign('id',$id);
            $this->display('priv');
        }
    }

    public function ajax_del()
    {
        //检查role_user表是否有关联数据
        //检查access表是否有关联数据
        $id=I('get.id',0,'intval');
        $roleuser=M('RoleUser')->where('role_id='.$id)->find();
        $member=M('Member')->where('role='.$id)->find();
        if(empty($roleuser) && empty($member)){
            M('Role')->delete($id);
            $this->ajaxReturn(true);
        }
        $this->ajaxReturn(false);
    }
}