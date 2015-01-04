<?php
namespace Admin\Controller;
use Think\Controller;
class AdpositionController extends AdminController
{
    public function index()
    {
        $mod = D('ad');
        $lists=$mod->order('ad_id DESC')->select();
        foreach($lists as $key=>$val){
            $lists[$key]['content']=json_decode($val['content'],true);
        }
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $ad_mod=M('ad');
            $data=array();
            if(!isset($_POST['ad_img']) || !isset($_POST['ad_url']) || empty($_POST['ad_img']) || empty($_POST['ad_url'])){
                $this->error('请上传图片');
            }
            for($i=0;$i<count($_POST['ad_img']);$i++){
                $file[$i]['img']=$_POST['ad_img'][$i];
                $file[$i]['url']=$_POST['ad_url'][$i];
                $file[$i]['del']=$_POST['ad_del'][$i];
            }
            $content=json_encode($file);
            $data=array(
                'ad_name'=>$_POST['ad_name'],
                'ad_label'=>$_POST['ad_label'],
                'ad_width'=>$_POST['ad_width'],
                'ad_height'=>$_POST['ad_height'],
                'status'=>$_POST['status'],
                'content'=>$content
            );
            if($ad_mod->add($data)){
                $this->success('添加成功',U('adposition/index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->assign('redio_txt',redio_txt());
            $this->display('ad_form');
        }

    }

    public function edit()
    {
        $ad_mod=M('ad');
        if(IS_POST)
        {
            $data=array();
            if(!isset($_POST['ad_img']) || !isset($_POST['ad_url']) || empty($_POST['ad_img']) || empty($_POST['ad_url'])){
                $this->error('请上传图片');
            }
            for($i=0;$i<count($_POST['ad_img']);$i++){
                $file[$i]['img']=$_POST['ad_img'][$i];
                $file[$i]['url']=$_POST['ad_url'][$i];
                $file[$i]['del']=$_POST['ad_del'][$i];
            }
            $content=json_encode($file);
            $data=array(
                'ad_name'=>$_POST['ad_name'],
                'ad_label'=>$_POST['ad_label'],
                'ad_width'=>$_POST['ad_width'],
                'ad_height'=>$_POST['ad_height'],
                'status'=>$_POST['status'],
                'content'=>$content
            );
            if($ad_mod->where('ad_id='.I('post.id',0,'intval'))->save($data)){
                $this->success('广告编辑成功',U('adposition/index'));
            }else{
                $this->error('广告编辑失败');
            }
        }else{
            $id=I('get.id',0,'intval');
            $lists=$ad_mod->where('ad_id='.$id)->find();
            if(!empty($lists['content'])){
                $lists['content']=json_decode($lists['content'],true);
            }
            $this->assign('list',$lists);
            $this->assign('redio_txt',redio_txt());
            $this->display('ad_form');
        }

    }

    public function adupload()
    {

        // $options=array();

        $this->ajaxupload();

        // 实例化上传类
        // $upload_handler->options

        // if(IS_POST){
        //     $images=$this->upload();
        //     $data=array();
        //     for($i=0;$i<count($images);$i++){
        //         $data[]=array(
        //             'img'=>$images[$i],
        //             'link'=>$_POST['link'][$i]
        //         );
        //     }
        //     $this->ajaxReturn(array('info'=>$data));
        // }else{
        //     $this->display();
        // }
    }
}