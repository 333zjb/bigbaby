<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/17
 * Time: 上午10:53
 */

class Redis_service extends \Predis\Client
{
    public function __construct()
    {
        parent::__construct(config('redis'));
    }

}