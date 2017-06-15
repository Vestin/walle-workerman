<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 1:49 PM
 */

namespace app\event;


use Symfony\Component\EventDispatcher\Event;

class SystemAfterMessageHandleInitEvent extends Event
{

    const NAME = 'system.after_message_handle_init';

}