<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/19
 * Time: 上午10:32
 */

class First_test extends \PHPUnit\Framework\TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct(null, [
            [1,1,2],
            [2,1,2]
        ]);
    }

    public function testdata(){
        $a = $this->getProvidedData();
        foreach ($a as $v){
            $this->assertEquals($v[2], $v[0]+$v[1]);
        }

    }
    public function testModel(){
        $a = new Base_model();
        $this->assertTrue(is_object($a)==true);
    }
    public function testResque(){
        $b = new Resque_service();
        $args = ['name'=>'kkk', 'age'=>16];
        $r = $b->create_job($args, 'Fk_job');
        $this->assertNotEmpty($r);
    }


}