<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/11
 * Time: 上午9:19
 */

class Base_controller
{
    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
    }

    protected function pack_input($index, $default = '', $xss_clean = true){
        if(!isset($_POST[$index]) && !isset($_GET[$index])){
            return null;
        }
        $data = isset($_POST[$index]) ? $_POST[$index] : $_GET[$index];
        $data_deal = $this->data_deal($data, $xss_clean);
        if($data_deal === '' && $default){
            return $default;
        }
        return $data_deal;
    }

    protected function data_deal($data, $xss_clean){
        if($xss_clean){
            static $htmlpurifier = [];
            if(!$htmlpurifier){
                $htmlpurifier['config'] = \HTMLPurifier_Config::createDefault();
                $htmlpurifier['purifier'] = new \HTMLPurifier($htmlpurifier['config']);
            }
            $data = $htmlpurifier['purifier']->purify($data);
        }

        if (is_numeric($data)) {
            return $data;
        }

        if (is_json($data)) {
            return $data;
        }

        if (is_string($data)) {
            return addslashes(trim($data));
        }

        return $data;
    }

    protected function response_error_msg($data = null, $message = "参数错误，请检查输入！")
    {
        exit(json_encode(array('result' => false, 'message' => $message, 'data' => $data)));
    }

    protected function response_suc_msg($data = null, $message = 'suc')
    {
        exit(json_encode(array('result' => true, 'message' => $message, 'data' => $data)));
    }

}