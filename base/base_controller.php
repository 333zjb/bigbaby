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
    //导出csv格式文件 $data数据 $title_arr标题 $file_name文件名
    protected function exportCsv($data,$title_arr,$file_name=''){
        ini_set("max_execution_time", "3600");

        $csv_data = '';
        /** 标题 */
        $nums = count($title_arr);

        for ($i = 0; $i < $nums; ++$i) {

            if($i==($nums-1)){
                $csv_data .= '"' . $title_arr[$i] . "\"\r\n";
            }else{
                $csv_data .= '"' . $title_arr[$i] . '",';
            }
        }

        foreach ($data as $k => $row) {
            foreach ($row as $key => $r){
                // $row[$key] = str_replace("\"", "\"\"", $r);
                if(is_numeric($row[$key]) && strlen($row[$key]) > 10){
                    $csv_data .= $row[$key] . "\t,";//防止导出为科学计数
                }else {
                    $csv_data .= '"' . $row[$key] . '",';
                }
            }

            $csv_data .= "\r\n";
            unset($data[$k]);
        }

        $csv_data = mb_convert_encoding($csv_data, "gbk", "UTF-8");

        $file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : mb_convert_encoding($file_name, "gbk", "UTF-8");

        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) { // 解决IE浏览器输出中文名乱码的bug
            $file_name = urlencode($file_name);
            $file_name = str_replace('+', '%20', $file_name);
        }

        $file_name = $file_name . '.csv';

        header('Content-Type: application/download');
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $file_name);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        echo $csv_data;
        exit();
    }
}