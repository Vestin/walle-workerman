<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use app\component\app;
use FastRoute\Dispatcher;
use core\contracts\ServiceInterface;

class DispatcherService implements ServiceInterface
{

    /**
     * @return Dispatcher
     */
    public function register()
    {
        $options = app::$app->config['basicServicesConfig']['route'];
        return new $options['dispatcher'](app::$app->route->getData());
    }

}