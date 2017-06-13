<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use app\core\contracts\ServiceInterface;

class LogService implements ServiceInterface
{

    public function register()
    {
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler('../runtime/app.log'),Logger::WARNING);
        return $log;
    }

}