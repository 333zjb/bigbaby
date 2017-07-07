<?php

/**
 * Created by PhpStorm.
 * User: xukong
 * Date: 2017/6/16
 * Time: 10:11
 *还需要完善 $this->找不到。
 */
class get_oop_relation
{

     public function get_relation(){
        //获取controller下所有类名
        $handler = opendir('./application/controllers/');
        $className = [];
        while( ($filename = readdir($handler)) !== false ) {
            if($filename != "." && $filename != ".."&&strrpos($filename, '.php')>0){
                $len = strrpos($filename, '.php');
                $class_name = substr($filename, 0, $len);
                $className[] = $class_name;
            }
        }

        closedir($handler);
        $data = [];
        //获取类中代码
        foreach ($className as $v) {

            $data[$v] = array();
            require_once ('./application/controllers/'.$v.'.php');
            $class =  new ReflectionClass($v);
            $methodname = $class->getMethods();
            $path = $class->getFileName();
            $lines = @file($path);

            foreach ($lines as $sec){
                //获取当前类的方法名
                foreach ($methodname as $methodobj){
                    if($methodobj->class==$v||$methodobj->class==ucfirst($v)){
                        $method = $methodobj->name;
                        if(strrpos($sec, 'function '.$method)!==false){
                            $data[$v][$method] = array();
                            $data[$v][$method]= $this->find_next($v, $method);
                        }
                    }
                }
            }
        }
        echo "<pre>";
        print_r($data);
    }
    //函数体里的递归
    public function find_next($classN, $fucN){
        $a=file_exists('./application/controllers/'.$classN.'.php');
        $b=file_exists('./application/services/'.$classN.'.php');
        $c=file_exists('./application/models/'.$classN.'.php');
        $data = [];
        if($a||$b||$c) {
            $class = new ReflectionClass($classN);
            $methodname = $class->getMethods();
            $path = $class->getFileName();
            $lines = @file($path);
            $current_method = '';
            $current_fuck = [];
            foreach ($lines as $sec) {
                foreach ($methodname as $methodobj) {
                    if ($methodobj->class == $classN || $methodobj->class == ucfirst($classN)) {
                        $method = $methodobj->name;
                        if (strrpos($sec, 'function ' . $method) !== false) {
                            $current_method = $method;
                        }
                    }
                }
                //找类名
                if ($current_method == $fucN) {
                    if (strrpos($sec, ' new ') !== false) {
                        $start = strrpos($sec, 'new ') + 4;
                        if (strpos($sec, '();') > $start) {
                            $len = strpos($sec, '();') - $start;
                            $tmpname = substr($sec, $start, $len);
                            //删除首尾空格
                            $tmpname = trim($tmpname);
                            if ($tmpname == 'PHPExcel') {
                                continue;
                            }
                            //获取这个类的所有方法
                            $a=file_exists('./application/controllers/'.$tmpname.'.php');
                            $b=file_exists('./application/services/'.$tmpname.'.php');
                            $c=file_exists('./application/models/'.$tmpname.'.php');
                            if($a||$b||$c){
                                $tmpClass = new ReflectionClass($tmpname);
                                $tmpMethoName = $tmpClass->getMethods();
                                $tmpMethoArr = [];
                                $tmpMethoArr[$tmpname] = [];
                                foreach ($tmpMethoName as $mObj){
                                    /*if($mObj->class==$tmpname||$mObj->class==ucfirst($tmpname)){*/
                                        $tmpMethoArr[$tmpname][] = $mObj->name;
                                   /* }*/
                                }
                                $current_fuck[] = $tmpMethoArr;
                            }
                        }
                    }
                    //根据类名方法名 递归查找
                    foreach ($current_fuck as $fuc){
                        foreach ($fuc as $class_name=>$fuc_names){
                            foreach ($fuc_names as $fuc_name){
                                if(strrpos($sec, '->'.$fuc_name)!==false){
                                    $data[$class_name] = [];
                                    $data[$class_name][$fuc_name] = $this->find_next($class_name, $fuc_name);
                                }
                            }
                        }
                    }
                }
            }

        }
        return $data;
    }
}