<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller
{
    public function _initialize()
    {

        // 后台用户权限检查
        // if(C('USER_AUTH_ON') && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))){
        //     if(!\Org\Util\Rbac::AccessDecision()){
        //         //检查认真识别号
        //         if(!$_SESSION[C('USER_AUTH_KEY')]){
        //             //跳转到认证网关
        //             redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
        //         }
        //         //没有权限 抛出错误
        //         if(C('RBAC_ERROR_PAGE')){
        //             //定义权限错误页面
        //             redirect(C('RBAC_ERROR_PAGE'));
        //         }else{
        //             if(C('GUEST_AUTH_ON')){
        //                 $this->assign('jumpUrl',PHP_FILE.C('USER_AUTH_GATEWAY'));
        //             }
        //             //提示错误信息
        //             $this->error(L('_VALID_ACCESS_'));
        //         }
        //     }
        // }
        $this->menuCate();
    }

    public function index()
    {
        // $mod = D(CONTROLLER_NAME);
        // $lists=$mod->order('id DESC')->select();
        // $this->assign('lists',$lists);
        // $this->display();
    }

    /**
     * 后台菜单路径组合
    */
    public function menuPath($menu)
    {
        foreach($menu as $k=>$v){
            $path=array();
            $v['module'] && $path[]='m='.$v['module'];
            $v['controller'] && $path[]='c='.$v['controller'];
            $v['action'] && $path[]='a='.$v['action'];
            $v['param'] && $path[]=$v['param'];
            !empty($path) && $menu[$k]['path']='index.php?'.implode('&',$path);
        }
        return $menu;
    }

    public function menuCate()
    {
        $cate_mod = M('Categories');
        $lists = $cate_mod->order('pid ASC,order_sort ASC')->select();
        if(empty($lists)) return false;
        $cate = array();
        foreach($lists as $val)
        {
            if($val['pid'] == 0 && !isset($cate[$val['id']])){
                $cate[$val['id']] = $val;
            }else{
                $cate[$val['pid']]['child'][] = $val;
            }
        }
        $this->assign('menu_cate',$cate);
    }

    /**
     * 文件上传
    */
    public function uploadone($field='')
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './static/uploads/'; // 设置附件上传根目录
        // 上传单个文件
        $info = $upload->uploadOne($field);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
             return 'static/uploads/'.$info['savepath'].$info['savename'];
        }
    }

    public function upload($field='')
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './static/uploads/'; // 设置附件上传根目录
        // 上传单个文件

        $info = $upload->upload($field);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $file=array();
            foreach($info as $f){
                $file[]='static/uploads/'.$f['savepath'].$f['savename'];
            }
            return $file;
        }
    }

    public function ajaxUpload()
    {
        //上传初始化定义路径
        $upload_handler=new \Think\Uploadhandler(array(
            'upload_url'=>'static/uploads/'.date('Ymd').'/', //图片显示地址
            'upload_dir'=>'static/uploads/'.date('Ymd').'/', //图片存放路径
            'script_url'=>'index.php?m=admin&c=focus&a=adupload' //处理图片地址
        ));
        $files=$upload_handler->get_response(); //返回上传数据
    }

    /**
     * ajax修改单个字段值
     */
    public function ajax_edit()
    {
        $id = I("get.id",0,'intval');
        $field = I('get.field','','trim');
        $val = I('get.val','','trim');
        if(!in_array($field,array('status','publish','display','visibility','sort')) || empty($id)){
            $this->ajaxReturn(array('status'=>false),'JSON');
        }

        $mod = D(CONTROLLER_NAME);
        $pk = $mod->getPk();

        //允许异步修改的字段列表  放模型里面去
        $mod->where(array($pk=>$id))->setField($field, $val);
        $this->ajaxReturn(array('status'=>true,'rel'=>array('state'=>$val)),'JSON');
    }
}