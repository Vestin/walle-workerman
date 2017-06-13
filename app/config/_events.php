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
    "system.app.receive_message" => [
        \app\event\SystemAppReceiveMessageEventListener::class,
    ]
];