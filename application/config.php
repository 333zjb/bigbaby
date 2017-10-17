<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/13
 * Time: 下午6:12
 */
$config['database'] = [
    'database_type' => 'mysql',
    'database_name' => 'bigbaby',
    'server' => '127.0.0.1',
    'username' => 'root',
    'password' => '63d7b25c0e',
    'charset' => 'utf8'
];

$config['redis'] = [
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
    'database'  =>  3
];