<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/11
 * Time: 上午9:19
 */

class BaseController
{
    protected function pack_input($index, $default = '', $xss_clean = FALSE){
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
            $security = new Security();
            $data = $security->getXssSafeParams($data);
        }
        if (is_numeric($data)) {
            return $data;
        }

        if (is_json($data)) {
            return $data;
        }

        if (is_string($data)) {
            return addslashes(strip_tags(trim($data)));
        }

        return $data;
    }

}