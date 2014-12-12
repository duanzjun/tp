<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController
{
    public function index()
    {
        $menu_mod=D('Menu');
        //顶部菜单列表
        $topmenu=$menu_mod->where('status=1 AND display=1 AND pid=0')->order('sort ASC')->select();
        $this->assign('topmenu',$topmenu);
        $this->display();
    }

    public function panel()
    {
        $sys_info=array(
            'os'=>PHP_OS,
            'server_software'=>strpos($_SERVER['SERVER_SOFTWARE'], 'PHP')===false ? $_SERVER['SERVER_SOFTWARE'].' PHP/'.phpversion() : $_SERVER['SERVER_SOFTWARE'],
            'upload_max_filesize'=>@ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown',
            'time' => date("Y-m-d H:i:s", time()), //获取服务器时间
            'pc' => $_SERVER['SERVER_NAME'], //当前主机名
            'osname' => php_uname(), //获取系统类型及版本号
            'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], //获取服务器语言
            'port' => $_SERVER['SERVER_PORT'], //获取服务器Web端口
            'max_upload' => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            'max_ex_time' => ini_get("max_execution_time")."秒", //脚本最大执行时间
            'mysql_version' => $this->_mysql_version(),
            'mysql_size' => $this->_mysql_db_size(),
        );
        $userinfo=$_SESSION['userinfo'];
        $userinfo['role_name']=M('Role')->where('id='.$userinfo['role'])->getField('name');
        $this->assign('sys_info',$sys_info);
        $this->assign('user',$userinfo);
        $this->display();
    }

    /**
     * 左侧菜单列表
    */
    public function left_menu()
    {
        $menuid=I('menuid',0,'intval');
        $pid=I('pid',0,'intval');
        $map=array(
            'display'=>1,
            'status'=>1
        );
        $map['pid']=!empty($menuid) ?  $map['pid']=$menuid : 1;
        $submenu=D('Menu')->where($map)->order('sort ASC')->select();
        $submenu=$this->menuPath($submenu);
        $this->assign('submenu',$submenu);
        $this->display('Public/sidebar');
    }

    /**
     * 获取mysql版本
    */
    private function _mysql_version()
    {
        $_model=new \Think\Model();
        $version=$_model->query("select version() as ver");
        return $version[0]['ver'];
    }

    /**
     * MYSQL大小
    */
    private function _mysql_db_size()
    {
        $_model=new \Think\Model();
        $sql = "SHOW TABLE STATUS FROM ".C('DB_NAME');
        $tblPrefix = C('DB_PREFIX');
        if($tblPrefix != null) {
            $sql .= " LIKE '{$tblPrefix}%'";
        }
        $row = $_model->query($sql);
        $size = 0;
        foreach($row as $value) {
            $size += $value["Data_length"] + $value["Index_length"];
        }
        return round(($size/1048576),2).'M';
    }

    /**
     * 网站基本信息设置
    */
    public function setting()
    {
        echo define('__STATIC__');
        $setting_mod=M('Setting');
        $list=$setting_mod->where('k="base_setting"')->find();
        if(IS_POST){
            $data=array('k'=>'base_setting','v'=>json_encode($_POST));
            $file=$this->upload();

            if(!empty($list)){
                $setting_mod->where('k="base_setting"')->save($data);
            }else{
                $setting_mod->add($data);
            }
            $this->success('设置成功',U('index/setting'));
        }else{
            if(!empty($list)){
                $list=json_decode($list['v'],true);
                $this->assign('setting',$list);
            }
            $this->display();
        }
    }
}