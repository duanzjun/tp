<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model
{
    protected $_auto=array(
        array('create_time','time',MODEL_INSERT,'function')
    );

    protected $_validate=array(
        array('username','require','用户名不能为空'),
        array('password','require','密码不能为空')
    );

    var $_status=array(
        array('key'=>0,'val'=>'关闭'),
        array('key'=>1,'val'=>'开启')
    );
}