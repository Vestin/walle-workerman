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

class TaskLogService implements ServiceInterface
{

    public function register()
    {
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler('../runtime/app.log'),Logger::WARNING);
        return $log;
    }

}