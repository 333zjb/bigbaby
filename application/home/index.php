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
        $a = $this->pack_input('a', '', true);
        echo $a;
    }
    private function gg(){
        echo 1;
    }
}