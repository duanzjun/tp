<?php
namespace Admin\Model;
use Think\Model;
class NewsModel extends Model
{
    protected $tableName = 'News';
    protected $_auto = array(
        array('addtime','time',self::MODEL_INSERT,'function'),
        array('updatetime','time',self::MODEL_BOTH,'function'),
        array('content','htmlspecialchars_decode',self::MODEL_BOTH,'function')
    );
}