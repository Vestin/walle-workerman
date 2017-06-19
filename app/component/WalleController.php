<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/15/17
 * Time: 11:41 PM
 */

namespace app\component;


class WalleController
{

    public $messageHandle;

    /**
     * WalleController constructor.
     * @param $messageHandle
     */
    public function __construct()
    {
        global $messageHandler;
        $this->messageHandle = $messageHandler;
    }

}