<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends AdminController
{
    public function index()
    {
        $cate_mod=D('Common/Category');
        $categories=$cate_mod->getAll(0);

        foreach($categories as $key=>$cate){
            $categories[$key]['active']='<span class="d-cursor" data-val="'.(1-$cate['is_active']).'" ><i class="glyphicon '.($cate['is_active']?'glyphicon-ok':'glyphicon-remove').'"></i></span>';
            $categories[$key]['edit']='<a href="'.U('category/edit',array('id'=>$cate['id'])).'">编辑</a>';
            $categories[$key]['del']='<a data-toggle="modal" data-target="#delModal" data-uri="'.U('category/del',array('id'=>$cate['id'])).'" href="#">删除</a>';
        }

        $tree=new \Org\Util\Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($categories);
        $str="<tr><td class='text-center'><input type='checkbox' name='checked[]' value='\$id' /></td>".
            "<td>\$spacer\$cate_name</td>".
            "<td>\$active</td>".
            "<td>\$order_sort</td>".
            "<td>\$edit | \$del</td></tr>";
        $html_tree = $tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);
        $this->assign('categories',$categories);
        $this->display();
    }

    public function add()
    {
        $cate_mod=D('Common/Category');
        if(IS_POST){
            if($cate_mod->create()){
                if(false !==$cate_mod->add()){
                    $this->success('分类添加成功',U('category/index'));
                }else{
                    $this->error('分类添加失败');
                }
            }else{
                $this->error($cate_mod->getError());
            }
        }else{
            $categories=$cate_mod->getAll();
            $tree=new \Org\Util\Tree;
            $tree->icon=array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp='&nbsp;&nbsp;&nbsp;';
            $tree->init($categories);
            $str="<option value='\$id'>\$spacer\$cate_name</option>";
            $html_tree=$tree->get_tree(0,$str);
            $this->assign('html_tree',$html_tree);
            $this->display();
        }
    }

    public function edit()
    {

    }
}