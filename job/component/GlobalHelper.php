<?php
/* *****************************************************************
 * @Author: wushuiyong
 * @Created Time : 一  7/20 15:52:01 2015
 *
 * @File Name: components/Controller.php
 * @Description:
 * *****************************************************************/

namespace job\component;

class GlobalHelper {

    /**
     * 字符串转换成数组
     *
     * @param $string
     * @param $delimiter
     * @return array
     */
    public static function str2arr($string, $delimiter = PHP_EOL) {

        $items = explode($delimiter, $string);

        foreach ($items as $key => &$item) {

            // 查找 # 的位置
            $pos = strpos($item, '#');

            if ($pos === 0) {
                // # 开头, 整行注释
                unset($items[$key]);
                // 直接到下一个
                continue;
            }

            if ($pos > 0) {
                // # 在中间, 后面一段注释
                $item = substr($item, 0, $pos);
            }

            $item = trim($item);
            if (empty($item)) {
                unset($items[$key]);
            }

        }

        return $items;
    }

    /**
     * 转换成utf8
     * @param $text
     * @return string
     */
    public static function convert2Utf8($text) {
        $encoding = mb_detect_encoding($text, mb_detect_order(), false);
        if ($encoding == "UTF-8") {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }
        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);

        return $out;
    }

}
