<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/17
 * Time: 下午5:20
 */

class test
{
    public function tt(){
        $client = new Redis_service();
        $client->set('crr', date('Y-m-d H:i:s'));
    }
}