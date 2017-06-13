<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 2:05 PM
 */

namespace app\component;


use Symfony\Component\EventDispatcher\Event;

interface EventListenerInterface
{
    public function handle(Event $event);
}