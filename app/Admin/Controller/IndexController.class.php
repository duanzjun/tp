<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController
{
    public function index()
    {
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
        // $userinfo=$_SESSION['userinfo'];
        // $userinfo['role_name']=M('Role')->where('id='.$userinfo['role'])->getField('name');
        $this->assign('sys_info',$sys_info);
        // $this->assign('user',$userinfo);
        $this->display();
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
        $conf_mod=M('Config');
        $list = $conf_mod->find();
        if(IS_POST){
            $id = I('id',1,'intval');
            unset($_POST['id']);
            $conf_mod->where('config_id='.$id)->save($_POST);
            $this->success('设置成功',U('index/setting'));
        }else{
            $this->assign('setting',$list);
            $this->display();
        }
    }

}