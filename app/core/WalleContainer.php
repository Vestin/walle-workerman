<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 8:31 PM
 */

namespace app\core;

use Pimple\Container;
use app\core\contracts\ServiceFactoryInterface;
use app\core\contracts\ServiceInterface;

/**
 * Class WalleContainer
 * @property Container $container
 * @package app\core
 */
class WalleContainer implements WalleContainerInterface
{

    protected $container;

    /**
     * 根据数组批量注册服务
     * @param array $serviceProvidersArray
     */
    public function registerServices(array $serviceProvidersArray)
    {
        foreach ($serviceProvidersArray as $serviceName => $serviceProviderClass) {
            $this->registerService($serviceName, $serviceProviderClass);
        }
    }

    /**
     * 注册服务
     * @param $serviceName string 服务名
     * @param $serviceProviderClass string 服务注册类
     * @throws \Exception
     */
    public function registerService($serviceName, $serviceProviderClass)
    {
        $provider = new $serviceProviderClass();
        if ($provider instanceof ServiceInterface) {
            $this->container[$serviceName] = function () use ($provider) {
                return $provider->register();
            };
        } elseif ($provider instanceof ServiceFactoryInterface) {
            $this->container[$serviceName] = $this->container->factory(function () use ($provider) {
                return $provider->register();
            });
        } else {
            throw new \Exception('ServiceProvider must implement vestin\sliver\contracts\ServiceInterface or ServiceFactoryInterface');
        }
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return $this->container[$name];
        }
    }

}