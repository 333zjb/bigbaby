<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/13
 * Time: 下午5:23
 */

class Base_model extends \Medoo\Medoo
{
    public function __construct()
    {
        $database_conf = config('database');
        parent::__construct($database_conf);
    }
}