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
 * 分类列表生成树型结构
 * @param array $list 分类列表数据
 * @param int $root 生成当前ID下的树型分类
 * @param string $id 分类ID字段
 * @param string $pid 父级ID字段
 * @param string $child 子级名
*/
function list_to_tree($list, $root = 0, $pk='id', $pid = 'pid', $child = '_child') {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 表单单选按钮共用数据
*/
function radio_txt($type='')
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