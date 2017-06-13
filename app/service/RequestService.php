<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use app\component\WalleRequest;
use app\core\app;
use app\core\contracts\ServiceInterface;
use app\core\MessageHandle;

class RequestService implements ServiceInterface
{

    /**
     * @return WalleRequest
     */
    public function register()
    {
        global $messageHandler;
        return new WalleRequest($messageHandler->data);
    }

}