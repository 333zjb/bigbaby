<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/18
 * Time: 下午5:10
 */
//该文件用于 php-resque创建work

//引进自动加载
require __DIR__ . '/bootstrap.php';
//用php-cli实现的work进程
require __DIR__ . '/vendor/chrisboulton/php-resque/resque.php';