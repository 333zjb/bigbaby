<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/10
 * Time: 下午4:05
 */

class Index extends Base_controller
{

    public function ss(){
       echo date('Y-m-d H:i:s');
    }
    public function gg(){
        $database = new Base_model();
        $a = $database->select("tp_admin", [
            "user_name",
            "email"
        ], [
            "admin_id" => 10
        ]);
        var_dump($a);
    }
    public function testredis(){
        $redis = new \Predis\Client(config('redis'));
        $redis->set('zjb', 'big');
    }
    public function get(){
        $redis = new Redis_service();
        var_dump($redis->get('zjb'));
    }
    public function testResque(){
        $args['name'] = $this->pack_input('name');
        $args['age'] = $this->pack_input('age');
        $a = new Resque_service();
        $res = $a->create_job($args,'Fk_job');
        $this->response_suc_msg($res);
    }
}