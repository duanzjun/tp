<?php
namespace Home\Model;
use Think\Model;
class ArticlecategoryModel extends Model
{
    protected $tableName = 'article_category';


    /**
     * 获取所有分类数据
     * @param int $visibility 1显示分类
    */
    public function get_all($visibility=true)
    {
        if($visibility){
            $condition['visibility']=1;
        }
        $data=$this->where($condition)->order('sort DESC')->select();
        return $data;
    }
}