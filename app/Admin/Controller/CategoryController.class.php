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
            $categories[$key]['child_cate']='<a href="'.U('category/add',array('id'=>$cate['id'])).'">添加子分类</a>';
            $categories[$key]['edit']='<a href="'.U('category/edit',array('id'=>$cate['id'])).'">编辑</a>';
            $categories[$key]['del']='<a class="confirmurl" data-msg="你确定删除【'.$cate['cate_name'].'】吗？'."\n".'不能删除有子级的内容！" data-uri="'.U('category/del',array('id'=>$cate['id'])).'" href="#">删除</a>';
        }

        $tree=new \Org\Util\Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($categories);
        $str="<tr><td class='text-center'><input type='checkbox' name='checked[]' value='\$id' /></td>".
            "<td>\$spacer\$cate_name</td>".
            "<td>\$active</td>".
            "<td>\$order_sort</td>".
            "<td>\$child_cate | \$edit | \$del</td></tr>";
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
            $id=I('get.id',0,'intval');
            $html_tree=getTreeOption('Category',$id,1,array('name'=>'cate_name'));
            $this->assign('radio_txt',radio_txt());
            $this->assign('html_tree',$html_tree);
            $this->display('category_form');
        }
    }

    public function edit()
    {
        if(IS_POST){
            $category_mod=M('Category');
            if($category_mod->create()){
                if(false !==$category_mod->where('id='.I('get.id',0,'intval'))->save()){
                    $this->success('分类编辑成功',U('category/index'));
                }else{
                    $this->error('分类编辑失败');
                }
            }else{
                $this->error($category_mod->error());
            }
        }else{
            $id=I('get.id',0,'intval');
            $category_mod=D('Category');
            $category=$category_mod->find($id);
            $html_tree=getTreeOption('Category',$category['pid'],1,array('name'=>'cate_name'));

            $this->assign('radio_txt',radio_txt());
            $this->assign('category',$category);
            $this->assign('html_tree',$html_tree);
            $this->display('category_form');
        }
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $category_mod=M('Category');
        $list=$category_mod->where('pid='.$id)->find();
        if($list){
            $this->ajaxReturn(false);
        }else{
            $data=$category_mod->where('id='.$id)->delete();
            if($data>0)
                $this->ajaxReturn(true);
            else
                $this->ajaxReturn(false);
        }
    }
}