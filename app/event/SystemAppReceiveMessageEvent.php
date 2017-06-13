<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 1:49 PM
 */

namespace app\event;


use Symfony\Component\EventDispatcher\Event;

class SystemAppReceiveMessageEvent extends Event
{

    const NAME = 'system.app.receive_message';

    protected $message;

    /**
     * SystemAppReceiveMessageEvent constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

}