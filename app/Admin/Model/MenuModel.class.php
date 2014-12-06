<?php
namespace Admin\Model;
use Think\Model;
class MenuModel extends Model
{
    protected $tableName = 'menu';

    /**
     * 获取所有分类数据
     * @param int $is_active 1显示分类
    */
    public function getAll($status=1)
    {
        if($status){
            $condition['status']=1;
        }
        $categories=$this->where($condition)->order('sort DESC')->select();
        return $categories;
    }

    /**
     * 获取子菜单
     * @param intval pid 父级ID
     * @param intval status 是否显示分类
     * @param intval display 是否显示后台菜单
    */
    public function subAll($pid,$status=1,$display=0)
    {
        $map['pid']=$pid;
        $map['status']=$status;
        $map['display']=$display;
        $submenu=$this->where($map)->select();
        return $submenu;
    }
}