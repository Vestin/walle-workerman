<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 7:38 PM
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use app\component\app;

$dbConfig = app::$app->config['db'];

$capsule = new Capsule();

$capsule->addConnection($dbConfig);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

//事件监听
$dispatcher = app::$app->eventDispatcher;
$events = app::$app->config['events'];
foreach ($events as $eventName => $handlers){
    foreach($handlers as $handler){
        if (is_array($handler)) {
            $dispatcher->addListener($eventName, [(new $handler[0]), $handler[1]]);
        }
        $dispatcher->addListener($eventName, [new $handler, 'handle']);
    }
}