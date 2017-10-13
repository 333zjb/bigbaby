<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/9
 * Time: 下午4:13
 */

//缓冲区内容压缩输出
//if (extension_loaded('zlib')){
//    ob_end_clean();
//    ob_start('ob_gzhandler');
//}

// 检测PHP环境
//if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');

//报告运行时错误
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
error_reporting(E_ALL);

//定义常量
define('APP_PATH', 'application/');
define('BASE_PATH', 'base/');
define('THIRD_PATH', 'thirdparty/');
define('EXT', '.php');

//自动加载类
function auto_model_class($class_b)
{
    $class = strtolower($class_b);
    $paths = array('application/model/', 'thirdparty/');
    foreach ($paths as $path) {
        $tmp_file = $path . $class;
        if (is_file($tmp_file . EXT)) {
            require $tmp_file . EXT;
            return;
        }
    }
}

spl_autoload_register('auto_model_class');

//手动加载必要文件
require BASE_PATH . 'basecontroller.php';
require APP_PATH . 'helper/base_helper.php';

//路由实现(server_name(/index.php)(/divide_group)/controller/method/param1/v1/param2/v2...)
$divide_group = array('admin', 'home');//分组
$index_php = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);

$has_get = strpos($_SERVER['REQUEST_URI'],'?');
$has_get_bool = ( $has_get !== false);
$tmp_request_uri = $has_get_bool ? substr($_SERVER['REQUEST_URI'], 0 , $has_get) : $_SERVER['REQUEST_URI'];

$get_index = trim($tmp_request_uri,'/');
$get_index = str_replace('index.php', 'index_php', $get_index);
unset($_GET[$get_index]);

$behind_string = str_replace($index_php, '', $tmp_request_uri);
$behind_string = trim($behind_string,'/');
$behind_array = $behind_string === '' ? array() : explode('/', $behind_string);
$behind_array_length = count($behind_array);
$request_param = array(
    'divide_group'=>'home',
    'controller'=>'index',
    'method'=>'index'
);
if($behind_array_length>0){
    $had_divide_group = in_array($behind_array[0], $divide_group);
    $request_param['divide_group'] = $had_divide_group ? $behind_array[0] : $request_param['divide_group'];

    if($behind_array_length>1){
        $request_param['controller'] = $had_divide_group ? $behind_array[1] : $behind_array[0];
    }else{
        $request_param['controller'] = $had_divide_group ? $request_param['controller'] : $behind_array[0];
    }

    if($behind_array_length>2){
        $request_param['method'] = $had_divide_group ? $behind_array[2] : $behind_array[1];
    }else{
        $tmp_method = isset($behind_array[1]) ? $behind_array[1] : $request_param['method'];
        $request_param['method'] = $had_divide_group ? $request_param['method'] : $tmp_method;
    }
}
$request_class_path = APP_PATH . $request_param['divide_group'] . '/' . $request_param['controller'] .  EXT;
if(file_exists($request_class_path)) {
    require $request_class_path;
    $request_controller = new $request_param['controller']();
    $tmp_method = $request_param['method'];
    if(method_exists($request_controller, $tmp_method) && is_callable(array($request_controller, $tmp_method))){
        $request_controller->$tmp_method();
    }else{
        include './error_404.html';
    }
}else{
    include './error_404.html';
}
