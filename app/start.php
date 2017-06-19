<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/9/17
 * Time: 5:23 PM
 */

include __DIR__."/../vendor/autoload.php";

use Workerman\Worker;
use Illuminate\Database\Capsule\Manager as Capsule;
use app\component\app;
use app\component\MessageHandle;

/**
 * 加载环境参数
 */
$env = new \Dotenv\Dotenv(__DIR__.'/../');
$env->load();

/**
 * 加载配置信息
 */
$conf = new \Noodlehaus\Config(__DIR__.'/config/config.php');

// 创建一个Worker监听2346端口，使用websocket协议通讯
$ws_worker = new Worker("tcp://0.0.0.0:2346");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

$ws_worker->onWorkerStart = function ($worker) use ($conf) {
    //实例化共用组件
    app::init($conf);
    app::$app->bootstrap();
};

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function (\Workerman\Connection\ConnectionInterface $connection, $data) use (
    $ws_worker
) {

    try {
        $systemAppReceiveMessageEvent = new \app\event\SystemReceiveMessageEvent($data);
        app::$app->eventDispatcher->dispatch($systemAppReceiveMessageEvent::NAME, $systemAppReceiveMessageEvent);

        //var_dump($ws_worker->id);
        global $messageHandler;
        $messageHandler = new MessageHandle($connection, $data);

        $systemAfterMessageHandleInit = new \app\event\SystemAfterMessageHandleInitEvent();
        app::$app->eventDispatcher->dispatch($systemAfterMessageHandleInit::NAME, $systemAfterMessageHandleInit);

        $messageHandler->handle();
    } catch (\app\exception\UserException $e) {
        //var_dump($e->getFile() . '|' . $e->getLine());
        $connection->send(json_encode(['status' => 0, 'code' => $e->getCode(), 'error_message' => $e->getMessage()]));
    } catch (Exception $e) {
        //var_dump($e->getFile() . '|' . $e->getLine());
        $connection->send(json_encode(['status' => 0, 'error_message' => $e->getMessage()]));
    }

    unset($messageHandler);

};

// 运行worker
Worker::runAll();