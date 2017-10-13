<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/10
 * Time: 下午4:05
 */

class Index extends BaseController
{
    public function __construct()
    {

    }

    public function index(){
        $a = array('a', 'b', 'c');
        $this->response_suc_msg($a);
    }
    private function gg(){
        echo 1;
    }
}