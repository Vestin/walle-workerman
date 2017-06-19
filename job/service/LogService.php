<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace job\service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use core\contracts\ServiceInterface;

class LogService implements ServiceInterface
{

    public function register()
    {
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler('/tmp/mytest/runtime/app.log',Logger::WARNING));
        return $log;
    }

}