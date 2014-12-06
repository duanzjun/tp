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

    /**
      * 列表框获取数型结构
    */
    public function getTreeOption($selected=0,$is_active=1)
    {
        $categories=$this->getAll($is_active);
        foreach($categories as $key=>$val){
            $array[$val['id']]=$val;
        }
        unset($categories);
        $tree=new \Org\Util\Tree;
        $tree->icon=array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp='&nbsp;&nbsp;&nbsp;';
        $tree->init($array);
        $str="<option value='\$id' \$selected>\$spacer\$cate_name</option>";
        return $tree->get_tree(0,$str,$selected);
    }
}