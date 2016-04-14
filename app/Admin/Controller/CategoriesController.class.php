<?php
namespace Admin\Controller;
use Think\Controller;
class CategoriesController extends AdminController
{
    protected $_cate_mod;
    public function _initialize()
    {
        $this->_cate_mod = D('Categories');
        $this->assign('_type',$this->_cate_mod->_type);
        $this->assign('_module',$this->_cate_mod->_module);
        $this->assign('_nav',$this->_cate_mod->_nav);
    }

    public function index()
    {
        $ids = I('post.id',0,'intval');
        $lists=$this->_cate_mod->getAll();
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            if($this->_cate_mod->create()){
                if(false !==$this->_cate_mod->add()){
                    $this->success('栏目添加成功',U('categories/index'));
                }else{
                    $this->error('栏目添加失败');
                }
            }else{
                $this->error($this->_cate_mod->getError());
            }
        }else{
            $this->display('categories_form');
        }
    }

    //编辑栏目列字段
    public function editcol()
    {
        $ids = I('post.ids','','trim');
        $ids = explode(',',$ids);
        is_array($ids) || $this->ajaxReturn(false);

        foreach($ids as $v){
            $data = array(
                'nav' => intval($_POST['nav'][$v])?:0,
                'order_sort' => intval($_POST['order_sort'][$v])?:255
            );
            $_POST['cate_name'][$v] && $data['cate_name'] = trim($_POST['cate_name'][$v]);
            $this->_cate_mod->where('id='.$v)->save($data);
        }
        $this->ajaxReturn(true);
    }

    public function del()
    {
        $id=I('get.id',0,'intval');
        $list=$this->_cate_mod->where('pid='.$id)->find();
        if($list){
            $this->ajaxReturn(false);
        }else{
            $data=$this->_cate_mod->where('id='.$id)->delete();
            if($data>0)
                $this->ajaxReturn(true);
            else
                $this->ajaxReturn(false);
        }
    }
}