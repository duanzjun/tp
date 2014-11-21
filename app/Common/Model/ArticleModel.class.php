<?php
namespace Common\Model;
use Think\Model;
class ArticleModel extends Model
{
    protected $tableName = 'article';

    protected $_auto=array(
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_UPDATE,'function'),
        array('content','htmlspecialchars_decode',self::MODEL_BOTH,'function')
    );

    protected $_validate=array(
        array('cate_id','cateCheck','请选择分类',self::EXISTS_VALIDATE,'callback')
    );

    protected function cateCheck($data)
    {
        return intval($data)>0 ? true : false;
    }
}