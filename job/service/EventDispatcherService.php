<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace job\service;

use core\contracts\ServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherService implements ServiceInterface
{

    /**
     * @return
     */
    public function register()
    {
        return new EventDispatcher();
    }

}