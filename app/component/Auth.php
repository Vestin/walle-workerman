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
        var_dump(1);
        global $messageHandler;
        $requestParams = $messageHandler->request->getParams();
        var_dump($requestParams);
        if(!isset($requestParams['token']) || empty($requestParams['token'])){
            var_dump(12);
            throw AuthException::incorrectToken();
        }
        var_dump(3);
        $user = User::findByToken($requestParams['token']);
        var_dump($user);
        var_dump(4);
        if(!$user){
            throw AuthException::incorrectToken();
        }
    }

}