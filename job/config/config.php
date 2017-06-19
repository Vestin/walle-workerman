<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 3:33 PM
 */

return [
    "version" => '1.0.0',
    "name" => 'job',
    "db" => require '_db.php',
    "user" => getenv("user"),
    "basicServices" => [
        'log' => \job\service\LogService::class,
        'eventDispatcher' => \job\service\EventDispatcherService::class,
    ],
    "services" => [
        'log' => \job\service\TaskLogService::class,
    ],
    "events" => require '_events.php',
];