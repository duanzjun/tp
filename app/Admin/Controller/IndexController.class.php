<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController
{
    public function index()
    {
        var_dump($_SESSION);
        $this->display();
    }

    public function edit()
    {
        echo 'edit';
    }

    public function del()
    {
        echo 'del';
    }
}