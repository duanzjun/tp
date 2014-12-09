<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller
{
    var $_menu=array();
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
     *后台顶部菜单
    */
    public function topMenu($menuid)
    {
        $topmenu=M('Menu')->where('pid=0')->order('sort ASC')->select();
        $topmenu=$this->menuPath($topmenu);
        $this->assign('topmenu',$topmenu);
    }

    /**
     * 侧边菜单列表
    */
    public function subMenu()
    {
        $menuid=I('menuid',0,'intval');
        // $this->assign('submenu',$this->_menu($menuid));


        // $map['controller']=strtolower(CONTROLLER_NAME);
        // $map['action']=ACTION_NAME;
        // $menu_mod=D('Menu');
        // $curmenu=$menu_mod->where($map)->find();
        // if(empty($curmenu)) return ;
        // $pid=$curmenu['pid']>0 ? $curmenu['pid'] : $curmenu['id'];
        // $submenu=$menu_mod->subAll($pid,1,1);
        // $submenu=$this->menuPath($submenu);

        // $this->assign('curmenu',$curmenu);
        // $this->assign('submenu',$submenu);
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
        $upload->rootPath='d/uploads/';//设置附件上传根目录
        $upload->savePath='';//设置附件上传（子）目录

        //上传文件
        $info=$upload->upload();
        if(!$info){//上传错误提示信息
            $this->error($upload->getError());
        }else{
            return $file['savepath'].$file['savename'];
        }
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