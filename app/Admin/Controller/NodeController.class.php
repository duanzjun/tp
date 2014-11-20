<?php
namespace Admin\Controller;
use Think\Controller;
class NodeController extends AdminController
{
    public function index()
    {
        $node_mod=D('Node');
        $lists=$node_mod->select();
        $tree=new \Org\Util\Tree;
        $node_array=array();
        $level_val=$node_mod->_level;
        foreach($lists as $k=>$n){
            $glyphicon=$n['status'] ? 'glyphicon-ok' : 'glyphicon-remove';
            $n['status']='<span class="d-cursor" onclick="demo_toggle(this)" d-uri="'.U('node/ajax_edit',array('id'=>$n['id'],'field'=>'status')).'" d-val="'.(1-$n['status']).'" ><i class="glyphicon '.$glyphicon.'"></i></span>';
            $n['edit']='<a href="#">编辑</a> | ';
            $n['del']='<a href="#">删除</a>';
            $n['submenu']=$n['level'] >= 3 ? '' : '<a href="'.U('node/add',array('pid'=>$n['id'])).'">添加子节点</a> |';
            $n['level']=$level_val[$n['level']];
            $node_array[]=$n;
        }

        $str="<tr><td><input type='checkbox' name='checkid' value='\$id' /></td>".
            "<td>\$sort</td>".
            "<td>\$id</td>".
            "<td>\$spacer \$title (\$name)</td>".
            "<td>\$level</td>".
            "<td>\$status</td>".
            "<td>\$display</td>".
            "<td>\$submenu \$edit \$del</td></tr>";

        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($node_array);
        $html_tree = $tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $node_mod=M('Node');
            $pid=I('pid','0','intval');
            //通过选择节点计算出节点等级
            $level=$node_mod->where('id='.$pid)->getField('level');
            if($node_mod->create()){
                $node_mod->level=$level+1;
                $node_mod->add();
                $this->success('节点添加成功!',U('node/index'));
                exit;
            }
            $this->error('节点添加失败');
        }else{
            $node_mod=M('Node');
            $pid=I('get.pid',1,'intval');
            $node=$node_mod->where('id='.$pid)->find();
            if(empty($node))
                $this->error('父节点不存在');
            $this->assign('node',$node);
            $this->display();
        }
    }

    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $fields=I('get.field','','trim');
        $val=I('get.val','','trim');

        if(empty($id) || !in_array($fields,array('status'))){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }

        $data[$fields]=$val;
        M('Node')->where('id='.$id)->save($data);
        $this->ajaxReturn(array('status'=>true,'rel'=>$data),'JSON');
    }
}