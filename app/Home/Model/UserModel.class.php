<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model
{
    protected $tableName = 'user';

    //数据表字段自动填充
    protected $_auto=array(
        array('create_time','time',self::MODEL_INSERT,'function')
    )
}
