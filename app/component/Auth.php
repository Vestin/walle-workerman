<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/15/17
 * Time: 5:07 PM
 */

namespace app\component;

use app\event\SystemAfterMessageHandleInitEvent;
use app\exception\AuthException;
use app\model\basic\User;
use Symfony\Component\EventDispatcher\Event;

class Auth implements EventListenerInterface
{

    /**
     * @param SystemAfterMessageHandleInitEvent $event
     */
    public function handle(Event $event)
    {
        global $messageHandler;
        $requestParams = $messageHandler->request->getParams();
        if(!isset($requestParams['token']) || empty($requestParams['token'])){
            throw AuthException::incorrectToken();
        }
        $user = User::findByToken($requestParams['token']);
        if(!$user){
            throw AuthException::incorrectToken();
        }
    }

}