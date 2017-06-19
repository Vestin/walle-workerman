<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 8:31 PM
 */

namespace core;


/**
 * Class app
 * @package sliver
 */
interface WalleContainerInterface
{
    /**
     * 根据数组批量注册服务
     * @param array $serviceProvidersArray
     */
    public function registerServices(array $serviceProvidersArray);

    /**
     * 注册服务
     * @param $serviceName string 服务名
     * @param $serviceProviderClass string 服务注册类
     * @throws \Exception
     */
    public function registerService($serviceName, $serviceProviderClass);

    public function __get($name);
}