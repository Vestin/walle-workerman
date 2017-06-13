<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use app\component\ControllerDispatcher;
use app\core\contracts\ServiceInterface;

class ControllerDispatcherService implements ServiceInterface
{

    /**
     * @return ControllerDispatcher
     */
    public function register()
    {
        return new ControllerDispatcher();
    }

}