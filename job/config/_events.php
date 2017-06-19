<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 12:31 PM
 */

/**
 * [
'events' =>[
    \app\component\EventListenerInterface::class,
    [
        'listenerClassName',
        'handleMethod'
    ],
    [
        'listenerClassName2',
        'handleMethod2'
    ]
    ]
];
 */

return [
    \app\event\SystemReceiveMessageEvent::NAME => [
        \app\event\SystemReceiveMessageEventListener::class,
    ],
    \app\event\SystemAfterMessageHandleInitEvent::NAME =>[
        \app\component\Auth::class
    ]
];