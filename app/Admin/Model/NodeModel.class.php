<?php
namespace Admin\Model;
use Think\Model;
class NodeModel extends Model
{
    protected $tableName = 'node';

    var $_level=array(1=>'项目',2=>'模型',3=>'操作');
}