<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use app\component\app;
use FastRoute\RouteCollector;
use core\contracts\ServiceInterface;

class RouteService implements ServiceInterface
{

    /**
     * @return RouteCollector
     */
    public function register()
    {
        $options = app::$app->config['basicServicesConfig']['route'];
        return new $options['routeCollector'](
            new $options['routeParser'], new $options['dataGenerator']
        );
    }

}