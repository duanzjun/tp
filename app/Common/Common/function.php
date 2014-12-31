<?php
/**
  * 分类列表框获取数型结构
  * @param $model 模型
  * @param $selected 当前分类ID
  * @param $is_active 是否显示激活
  * @param $field 字段列表
*/
function getTreeOption($model,$selected=0,$is_active=1,$field=array())
{
    $lists=D($model)->getAll($is_active);
    foreach($lists as $key=>$val){
        $field['name'] && $val['name']=$val[$field['name']];
        $array[$val['id']]=$val;
    }
    unset($lists);
    $tree=new \Org\Util\Tree;
    $tree->icon=array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
    $tree->nbsp='&nbsp;&nbsp;&nbsp;';
    $tree->init($array);
    $str="<option value='\$id' \$selected>\$spacer\$name</option>";
    return $tree->get_tree(0,$str,$selected);
}

/**
 * 表单单选按钮共用数据
*/
function redio_txt($type='')
{
    switch ($type){
      case 'open_close':
        return array(
            array('key'=>0,'val'=>'关闭'),
            array('key'=>1,'val'=>'开启')
        );
        break;
      default:
        return array(
            array('key'=>0,'val'=>'否'),
            array('key'=>1,'val'=>'是')
        );
        break;
    }
}