<?php
namespace Common\Model;
use Think\Model;
class CategoryModel extends Model
{
    protected $tableName = 'category';

    protected $_validate=array(
        array('cate_name','require','分类名称不能为空',self::EXISTS_VALIDATE),
        array('order_sort','number','排序只能为数值'),
    );

    /**
     * 获取所有分类数据
     * @param int $is_active 1显示分类
    */
    public function getAll($is_active=1)
    {
        if($is_active){
            $condition['is_active']=1;
        }
        $categories=$this->where($condition)->order('order_sort DESC')->select();
        return $categories;
    }
}