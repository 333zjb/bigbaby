<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/19
 * Time: 上午10:32
 */

class First_test extends \PHPUnit\Framework\TestCase
{
    public function testModel(){
        $a = new Base_model();
        $this->assertTrue(is_object($a)==true);
    }
}