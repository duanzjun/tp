<?php
namespace Admin\Controller;
use Think\Controller;
class MenuController extends AdminController
{
    public function index()
    {
        $menu_mod=M('Menu');
        $lists=$menu_mod->select();
        $tree=new \Org\Util\Tree;
        $menu_array=array();
        foreach($lists as $k=>$n){
            $glyphicon=$n['status'] ? 'glyphicon-ok' : 'glyphicon-remove';
            $n['status']='<span class="d-cursor" onclick="demo_toggle(this)" d-uri="'.U('menu/ajax_edit',array('id'=>$n['id'],'field'=>'status')).'" d-val="'.(1-$n['status']).'" ><i class="glyphicon '.$glyphicon.'"></i></span>';
            $glyphicon_eye=$n['display'] ? 'glyphicon-eye-open' : 'glyphicon-eye-close';
            $n['display']='<span class="d-cursor" onclick="demo_toggle(this)" d-uri="'.U('menu/ajax_edit',array('id'=>$n['id'],'field'=>'display')).'" d-val="'.(1-$n['display']).'"><i class="glyphicon '.$glyphicon_eye.'"></i></span>';
            $param=array('id'=>$n['id']);
            $n['pid'] && $param['pid']=$n['pid'];
            $n['edit']='<a href="'.U('menu/edit',$param).'">编辑</a> | ';
            $n['del']='<a class="confirmurl" data-msg="确认删除【'.$n['name'].'】？" data-uri="'.U('menu/del',array('id'=>$n['id'])).'" href="javascript:void(0);">删除</a>';
            $n['submenu']=$n['level'] >= 3 ? '' : '<a href="'.U('menu/add',array('pid'=>$n['id'])).'">添加子节点</a> |';
            //菜单路径
            $path=array();
            $n['module'] && $path[]='m='.$n['module'];
            $n['controller'] && $path[]='c='.$n['controller'];
            $n['action'] && $path[]='a='.$n['action'];
            $n['param'] && $path[]=$n['param'];
            !empty($path) && $n['path']='index.php?'.implode('&',$path);
            $menu_array[]=$n;
        }

        $str="<tr><td><input class='wd50' type='text' name='sort' value='\$sort' /></td>".
            "<td class='text-center'>\$id</td>".
            "<td>\$spacer \$name</td>".
            "<td>\$path</td>".
            "<td class='text-right'>\$status</td>".
            "<td class='text-right'>\$display</td>".
            "<td class='text-right'>\$submenu \$edit \$del</td></tr>";

        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($menu_array);
        $html_tree = $tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);
        $this->display();
    }

    public function add()
    {
        $menu_mod=M('Menu');
        if(IS_POST){
            if($menu_mod->create()){
                $menu_mod->add();
                $this->success('菜单添加成功!',U('menu/index'));
                exit;
            }
            $this->error('菜单添加失败');
        }else{
            $pid=I('get.pid',0,'intval');
            $menu_tree=getTreeOption('Menu',$pid);
            $this->assign('menu_tree',$menu_tree);
            $this->display();
        }
    }

    public function edit()
    {
        $menu_mod=M('Menu');
        if(IS_POST)
        {
            $id=I('get.id',0,'intval');
            if($menu_mod->create()){
                if(false!==$menu_mod->where('id='.$id)->save()){
                    $this->success('编辑成功!',U('menu/index'));
                    exit;
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('编辑失败');
            }
        }else{
            $id=I('get.id',0,'intval');
            $pid=I('get.pid',0,'intval');

            $menu_tree=getTreeOption('Menu',$pid);
            $menu=$menu_mod->where('id='.$id)->find();

            $this->assign('menu_tree',$menu_tree);
            $this->assign('menu',$menu);
            $this->display('add');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $menu_mod=M('Menu');
        $data=$menu_mod->where('id='.$id)->delete();
        if($data>0)
            $this->ajaxReturn(true);
        else
            $this->ajaxReturn(false);
    }

    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $field=I('get.field','','trim');
        if(!in_array($field,array('status','display')) || empty($id)){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }
        $model=M('Menu');
        $data[$field]=I('get.val',0,'intval');
        $model->where('id='.$id)->save($data);
        $this->ajaxReturn(array('status'=>true,'rel'=>array('state'=>$data[$field])),'JSON');
    }
}