<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 12:09 PM
 */

namespace app\core\contracts;


interface ConfigInterface
{
    /**
     * 获取全部配置
     * @return mixed
     */
    public function all();
}