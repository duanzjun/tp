<?php
namespace Admin\Controller;
use Think\Controller;

class TestController extends Controller
{
    public function index()
    {
        echo 'this is test index';
    }

    public function edit()
    {
        echo 'this is test edit';
    }
}