<?php
namespace Admin\Controller;
use Think\Controller\RestController;
Class InfoController extends RestController
{
    protected $allowMethod    = array('get','post','put');
    protected $allowType      = array('html','xml','json');

   // Public function rest() {
   //   switch ($this->_method){
   //    case 'get': // get请求处理代码
   //         if ($this->_type == 'html'){
   //              echo 'html-------------';
   //         }elseif($this->_type == 'xml'){
   //              echo 'xml--------------';
   //         }
   //         break;
   //    case 'put': // put请求处理代码
   //         break;
   //    case 'post': // post请求处理代码
   //         break;
   //   }
   // }

    Public function read_get_html(){
        $result = $this->response('output id is '.$_GET['id'].' Info html data','html');
        return $result;
    }

    Public function read_get_xml()
    {
        $result = $this->response('output id is '.$_GET['id'].' Info XML data','xml');
        return $result;
    }

    Public function read_get_json()
    {
        $data = ['a'=>777777,'b'=>5555,'c'=>232323];
        $result = $this->response($data,'json');
        return $result;
    }

    Public function read_xml(){
        $data = ['a'=>111111,'b'=>22222,'c'=>9900];
        $result = $this->response($data,'xml');
        return $result;

    }

    Public function read_json(){
        $data = ['a'=>111111,'b'=>22222,'c'=>9900];
        $result = $this->response($data,'json');
        return $result;
    }
}