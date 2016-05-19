<?php
namespace Admin\Model;
use Think\Model;
class CategoriesModel extends Model
{
    protected $tableName = 'Categories';
    var $_module = array(
        0 => '新闻模型',
        1 => '产品模型',
        2 => '下载模型',
        3 => '图片模型',
        4 => '视频模型'
    );
    var $_type = array(
        0 => '分类目录',
        1 => '单网页',
        2 => '外部链接'
    );
    var $_nav = array(
        0 => '不显示',
        1 => '头部导航',
        2 => '尾部导航',
        3 => '都显示'
    );

    public function getAll()
    {
        $lists = $this->where('isshow = 1')->order('pid ASC')->select();
        $data = array();
        foreach($lists as $v){
            if($v['pid']==0 && !isset($data[$v['pid']])){
                $data[$v['id']] = $v;
            }else{
                $data[$v['pid']]['child'][$v['id']] = $v;
            }
        }
        return $data;
    }
}