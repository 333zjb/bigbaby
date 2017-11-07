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
//自动加载文件
require __DIR__ . '/bootstrap.php';


//路由实现(server_name(/index.php)(/divide_group)/controller/method)
$request_param = array(
    'divide_group' => 'home',
    'controller' => 'index',
    'method' => 'index'
);
if(isset($_SERVER['REQUEST_URI'])) {
//    if ($_SERVER['REQUEST_URI'] === '/favicon.ico') {
//        //由于目前是所有请求都重定向到index.php。所以访问favicon文件需要这样
//        echo file_get_contents('./favicon.ico');
//        die();
//    }

    $divide_group = array('admin', 'home');//分组
    //为了兼容windows （__DIR__里的目录分隔符在windows下和$_SERVER['']里的不一样）
    $file_match = str_replace('\\', '/', __FILE__);
    $dir_match = str_replace('\\', '/', __DIR__);
    $index_php = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_match);

    $has_get = strpos($_SERVER['REQUEST_URI'], '?');
    $has_get_bool = ($has_get !== false);
    $tmp_request_uri = $has_get_bool ? substr($_SERVER['REQUEST_URI'], 0, $has_get) : $_SERVER['REQUEST_URI'];

    $get_index = trim($tmp_request_uri, '/');
    $get_index = str_replace('index.php', 'index_php', $get_index);
    unset($_GET[$get_index]);

    $behind_string = str_replace($index_php, '', $tmp_request_uri);
    $behind_string = trim($behind_string, '/');
    $behind_array = $behind_string === '' ? array() : explode('/', $behind_string);
    //如果有项目名的话，去除项目名(兼容站点设置为上一级目录情况的访问)
    if($_SERVER['DOCUMENT_ROOT'] !== $dir_match){
        $project_name = str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $dir_match);
        if($project_name === $behind_array[0]){
            array_shift($behind_array);
        }
    }
    $behind_array_length = count($behind_array);

    if ($behind_array_length > 0) {
        $had_divide_group = in_array($behind_array[0], $divide_group);
        $request_param['divide_group'] = $had_divide_group ? $behind_array[0] : $request_param['divide_group'];

        if ($behind_array_length > 1) {
            $request_param['controller'] = $had_divide_group ? $behind_array[1] : $behind_array[0];
        } else {
            $request_param['controller'] = $had_divide_group ? $request_param['controller'] : $behind_array[0];
        }

        if ($behind_array_length > 2) {
            $request_param['method'] = $had_divide_group ? $behind_array[2] : $behind_array[1];
        } else {
            $tmp_method = isset($behind_array[1]) ? $behind_array[1] : $request_param['method'];
            $request_param['method'] = $had_divide_group ? $request_param['method'] : $tmp_method;
        }
    }
}else{
    $request_param['divide_group'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : $request_param['divide_group'];
    $request_param['controller'] = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : $request_param['controller'];
    $request_param['method'] = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : $request_param['method'];
}
$request_class_path = APP_PATH . $request_param['divide_group'] . '/' . $request_param['controller'] . EXT;
if (file_exists($request_class_path)) {
    require $request_class_path;
    $request_controller = new $request_param['controller']();
    $tmp_method = $request_param['method'];
    if (method_exists($request_controller, $tmp_method) && is_callable(array($request_controller, $tmp_method))) {
        $request_controller->$tmp_method();
    } else {
        include __DIR__ . '/error_404.html';
    }
} else {
    include __DIR__ . '/error_404.html';
}
