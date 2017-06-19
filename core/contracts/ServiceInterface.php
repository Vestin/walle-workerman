<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:35 PM
 */

namespace core\contracts;

/**
 * 服务注册
 * Interface ServiceInterface
 * @package sliver\contracts\ConfigInterface
 */
interface ServiceInterface
{
    /**
     * 注册
     * @return mixed
     */
    public function register();
}