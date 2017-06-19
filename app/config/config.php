<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 3:33 PM
 */

return [
    "version" => '1.0.0',
    "name" => 'app',
    "db" => require '_db.php',
    "user" => getenv("user"),
    "basicServices" => [
        'log' => \app\service\LogService::class,
        'route' => \app\service\RouteService::class,
        'dispatcher' => \app\service\DispatcherService::class,
        'controllerDispatcher' => \app\service\ControllerDispatcherService::class,
        'eventDispatcher' => \app\service\EventDispatcherService::class,
    ],
    "basicServicesConfig" => [
        'route' => [
            'routeParser' => 'FastRoute\\RouteParser\\Std',
            'dataGenerator' => 'FastRoute\\DataGenerator\\GroupCountBased',
            'dispatcher' => 'FastRoute\\Dispatcher\\GroupCountBased',
            'routeCollector' => 'FastRoute\\RouteCollector',
            'routeFile' => __DIR__ . '/../route.php',
        ]
    ],
    "services" => [
        'request' => \app\service\RequestService::class,
        'response' => \app\service\ResponseService::class,
    ],
    "events" => require '_events.php',
];