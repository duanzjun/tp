<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends AdminController
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
            $nodes=D('Node')->field('id,pid,level')->where($map)->select();
            $data=array();
            foreach($nodes as $k=>$n){
                $data[$k]['role_id']=$id;
                $data[$k]['node_id']=$n['id'];
                $data[$k]['pid']=$n['pid'];
                $data[$k]['level']=$n['level'];
            }
            $access_mod->addAll($data);
            $this->success('权限设置成功！',U('role/index'));
        }else{
            $id=I('get.id',0,'intval');
            $access=M('Access')->where('role_id='.$id)->getField('node_id',true);
            $node_mod=M('Node');
            $nodes=$node_mod->where('status=1')->select();
            if(is_array($access) && !empty($access)){
                foreach($nodes as $k=>$n){
                    $nodes[$k]['checked']=in_array($n['id'],$access) ? 'checked="checked"' : '';
                }
            }
            $tree=new \Org\Util\Tree;
            $str="<tr><td id='node-\$id' class='child-node-\$pid'>\$spacer <input type='checkbox' onclick='checknode(this)' level='\$level' name='node_id[]' value='\$id' \$checked /> \$title(\$name)</td></tr>";
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $tree->init($nodes);
            $html_tree = $tree->get_tree(0, $str);
            $this->assign('html_tree',$html_tree);
            $this->assign('id',$id);
            $this->display('priv');
        }
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
        if(empty($roleuser) && empty($user)){
            // M('Role')->delete($id);
            $this->ajaxReturn(true);
        }
        $this->ajaxReturn(false);
    }
}