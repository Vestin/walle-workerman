<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 5/9/17
 * Time: 4:33 PM
 */

namespace app\service;

use app\component\WalleResponse;
use app\core\contracts\ServiceInterface;

class ResponseService implements ServiceInterface
{

    /**
     * @return WalleResponse
     */
    public function register()
    {
        global $messageHandler;
        return new WalleResponse($messageHandler->connection);
    }

}