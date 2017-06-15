<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 8:25 PM
 */

namespace app\core;

use app\component\WalleRequest;
use app\component\WalleResponse;
use FastRoute\Dispatcher;
use Pimple\Container;
use Workerman\Connection\ConnectionInterface;

/**
 * Class MessageHandle
 * @package app\core
 *
 * @property WalleRequest $request
 * @property WalleResponse $response
 * @property ConnectionInterface $connection
 */
class MessageHandle extends WalleContainer
{

    protected $container;

    /**
     * MessageHandle constructor.
     * @param $connection
     * @param $data
     */
    public function __construct($connection, $data)
    {
        $this->container = new Container();
        $this->container['connection'] = $connection;
        $this->container['data'] = $data;
        $this->registerServices(app::$app->config['services']);
    }

    /**
     * app run
     */
    public function handle()
    {
        // 加载路由
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();
        $routeInfo = app::$app->dispatcher->dispatch($method, $uri);

        $params = $this->request->getParams();

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->response->send(['status' => 0, 'error_message' => 'NOT FOUND']);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->response->send(['status' => 0, 'error_message' => 'METHOD NOT ALLOWED']);
                break;
            case Dispatcher::FOUND:
                $params = $params + $routeInfo;
                app::$app->controllerDispatcher->dispatch($routeInfo[1],$params);
                break;
        }
    }

}