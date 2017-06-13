<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 10:34 AM
 */

namespace app\core;

use Pimple\Container;

/**
 * Class app
 * @package sliver
 */
class app extends WalleContainer
{
    /**
     * 服务容器
     * @var
     */
    protected $container;

    /**
     * app实例
     * @var self;
     */
    static public $app;

    private function __construct($config)
    {
        $this->container = new Container();
        $this->container['config'] = $config;
        $this->registerServices($this->config['basicServices']);
    }

    public function bootstrap()
    {
        //加载路由
        require $this->config['basicServicesConfig']['route']['routeFile'];
        require __DIR__.'/bootstrap.php';
    }

    static public function init($config)
    {
        if (self::$app) {
            throw new \Exception('Cannot init app twice');
        }
        self::$app = new self($config);
        return self::$app;
    }


}