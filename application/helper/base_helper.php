<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/11
 * Time: 下午5:18
 */
function is_json($str)
{
    if (empty($str) || !is_string($str)) {
        return false;
    }
    $f = @json_decode($str, true);
    if (is_array($f)) {
        return true;
    } else {
        return false;
    }
}

function config($name){
    static $config = array();//利用static来实现只require一次

    if(!isset($config[$name]) && !$config){
        require APP_PATH . 'config.php';

        if(!isset($config[$name])){
            return null;
        }
    }

    return $config[$name];
}

