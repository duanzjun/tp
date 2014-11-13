<?php
namespace Home\Controller;
use Think\Controller;
class ArticlecategoryController extends Controller
{
    public function index()
    {
        $model = M('Article_category');
        $result=$model->order('sort DESC')->select();
        $this->assign('result',$result);
        $this->assign('curr','artcate');
        $this->display();
    }

    public function add()
    {
        $art_cate_mod=D('Articlecategory');
        if(IS_POST){
            extract($_POST);
            $art_cate_mod->add($data);
            redirect(U('articlecategory/index'));
        }else{
            $result=$art_cate_mod->where('visibility=1')->order('id DESC')->select();
            $this->assign('result',$result);
            $this->assign('curr','artcate_add');
            $this->display();
        }
    }

    public function del()
    {
        $id=I('get.id','','trim');
        if(empty($id))
            $this->ajaxReturn(false);
        $ids=explode(',',$id);
        $art_cate_mod=D('Articlecategory');
        $map['id']=array('IN',$ids);
        $art_cate_mod->where($map)->delete();
        $this->ajaxReturn(true);
    }

    public function ajax_edit()
    {
        $id=I('get.id',0,'intval');
        $field=I('get.field','','trim');
        if(!in_array($field,array('visibility','sort')) || empty($id)){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }
        $model=D('Articlecategory');
        $data[$field]=I('get.val',0,'intval');

        $model->where('id='.$id)->save($data);
        $this->ajaxReturn(array('status'=>true,'rel'=>$data),'JSON');
    }
}