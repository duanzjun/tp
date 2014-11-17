<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends AdminController
{
    public function index()
    {
        $this->display();
    }

    /**
     * 验证用户登录
    */
    public function checkLogin()
    {
        $username=I('post.username','','trim');
        $password=I('post.password','','trim');

        $map=array(
            'username'=>$username,
            'status'=>1
        );
        $authInfo=\Org\Util\Rbac::authenticate($map);
        if(false===$authInfo)
            $this->error('帐号不存在或已禁用！');
        else{
            if($authInfo['password']!=md5($password))
                $this->error('密码错误');
            session(C('USER_AUTH_KEY'),$authInfo['id']);
            session('userinfo',$authInfo);
            // session('userid',$authInfo['id']);
            // session('username',$authInfo['username']);
            // session('roleid',$authInfo['role']);
            if($authInfo['username']==C('SPECIAL_USER'))
                session(C('ADMIN_AUTH_KEY'),true);

            //保存登录信息
            $user_mod=M(C('USER_AUTH_MODEL'));
            $data=array(
                'last_login_time'=>time(),
                'last_login_ip'=>get_client_ip(),
            );
            $user_mod->where('id='.$authInfo)->save($data);

            //缓存访问权限
            \Org\Util\Rbac::saveAccessList();
            redirect(U('index/index'));
        }
    }

    /**
     * 用户登出
    */
    public function logout()
    {
        if(session('?'.C('USER_AUTH_KEY'))){
            session('[destroy]'); //销毁session
            redirect(U('admin/login'));
        }else{
            $this->error('已经登出！');
        }
    }
}