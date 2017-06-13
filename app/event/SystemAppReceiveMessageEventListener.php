<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 2:04 PM
 */

namespace app\event;

use app\component\EventListenerInterface;
use Symfony\Component\EventDispatcher\Event;

class SystemAppReceiveMessageEventListener implements EventListenerInterface
{

    /**
     * @param SystemAppReceiveMessageEvent $event
     */
    public function handle(Event $event)
    {
        $message = $event->getMessage();
        echo 'message is'.$message.PHP_EOL;
    }

}