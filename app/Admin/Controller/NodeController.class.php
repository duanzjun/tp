<?php
namespace Admin\Controller;
use Think\Controller;
class NodeController extends Controller
{
    public function index()
    {
        $node_mod=M('Node');
        $lists=$node_mod->select();
        $tree=new \Org\Util\Tree;
        $node_array=array();
        foreach($lists as $k=>$n){
            $n['edit']='<a href="#">编辑</a>';
            $n['del']='<a href="#">删除</a>';
            $n['submenu']='<a href="#">添加子菜单</a>';
            $node_array[]=$n;
        }
        $str="<tr><td><input type='checkbox' value='checkid' /></td><td>\$spacer \$title (\$name)</td>
                <td>\$level</td>
                <td>\$status</td>
                <td>\$display</td>
                <td>
                    \$submenu | \$edit | \$del
                </td></tr>";
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($node_array);
        $html_tree = $tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);
        $this->display();
    }

    public function add()
    {
        $node_mod=M('Node');
        $lists=$node_mod->select();
        $tree=new \Org\Util\Tree;
        $str="<option value='\$id'>\$spacer\$title</option>";
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $tree->init($lists);
        $html_tree = $tree->get_tree(0, $str);
        $this->assign('html_tree',$html_tree);
        $this->display();
    }
}