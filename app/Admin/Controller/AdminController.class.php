<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller
{
    public function _initialize()
    {
        //后台用户权限检查
        if(C('USER_AUTH_ON') && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))){
            if(!\Org\Util\Rbac::AccessDecision()){
                //检查认真识别号
                if(!$_SESSION[C('USER_AUTH_KEY')]){
                    //跳转到认证网关
                    redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
                }
                //没有权限 抛出错误
                if(C('RBAC_ERROR_PAGE')){
                    //定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                }else{
                    if(C('GUEST_AUTH_ON')){
                        $this->assign('jumpUrl',PHP_FILE.C('USER_AUTH_GATEWAY'));
                    }
                    //提示错误信息
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }
    }

    /**
     * 后台菜单列表
    */
    public function get_menu()
    {
        $menu=array(
            ''
        );

        return $menu;
    }

    /**
     * 文件上传
    */
    public function upload()
    {
        $upload=new \Think\Upload();//实例化上传类
        $upload->maxSize=3145728; //设置附件上传大小
        $upload->exts=array('jpg','gif','png','jpeg');//设置附件上传类型
        $upload->rootPath=__STATIC__.'/uploads/';//设置附件上传根目录
        $upload->savePath='';//设置附件上传（子）目录

        //上传文件
        $info=$upload->upload();
        if(!$info){//上传错误提示信息
            $this->error($upload->getError());
        }else{
            return $file['savepath'].$file['savename'];
        }
    }

    public function add()
    {
        echo 'this is add';
        $this->display();
    }
}