<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller
{
    function _initialize()
    {
        // // $rbac = new \Org\Util\Rbac;
        // import('@.ORG.Util.Rbac');
        // // import('Util.Couter');

        // $map['account']='demo';
        // $authInfo=Rbac::authenticate($map);
        // // trace($authInfo);
    }

    function index()
    {
        $this->display();
    }
}