<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/18
 * Time: 下午4:43
 */

class Resque_service extends Resque
{
    public function create_job($args, $job_class){
        parent::setBackend('127.0.0.1:6379');
        return parent::enqueue('default', $job_class, $args, true);
    }
}