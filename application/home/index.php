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
    public function gg(){
        $database = new BaseModel();
        $a = $database->select("tp_admin", [
            "user_name",
            "email"
        ], [
            "admin_id" => 10
        ]);
        var_dump($a);
    }
}