<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/18
 * Time: 下午5:10
 */
date_default_timezone_set('Asia/Shanghai');
//定义常量
define('APP_PATH', __DIR__ . '/application/');
define('BASE_PATH', __DIR__ . '/base/');
define('EXT', '.php');

//自动加载类
function auto_model_class($class_b)
{
    $class = strtolower($class_b);
    $paths = array('model/', 'service/', 'job/');
    foreach ($paths as $path) {
        $tmp_file = APP_PATH . $path . $class;
        if (is_file($tmp_file . EXT)) {
            require $tmp_file . EXT;
            return;
        }
    }
}

spl_autoload_register('auto_model_class');

//手动加载必要文件
require __DIR__ . '/vendor/autoload.php';
require BASE_PATH . 'base_controller.php';
require BASE_PATH . 'base_model.php';
require APP_PATH . 'helper/base_helper.php';

require __DIR__ . '/vendor/chrisboulton/php-resque/resque.php';