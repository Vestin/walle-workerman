<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/16/17
 * Time: 12:41 PM
 */

require __DIR__."/../vendor/autoload.php";

use Workerman\Worker;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * 加载环境参数
 */
$env = new \Dotenv\Dotenv(__DIR__ . '/../');
$env->load();

/**
 * 加载配置信息
 */
//$conf = new \Noodlehaus\Config(__DIR__.'/config/config.php');

// 创建一个Worker监听2346端口，使用websocket协议通讯
$msg_recver = new Worker("Text://0.0.0.0:1236");

// 启动4个进程对外提供服务
$msg_recver->count = 4;

$msg_recver->queueId = 1991;

if (!extension_loaded('sysvmsg')) {
    echo "Please install sysvmsg extension.\n";
    exit;
}

/**
 * 初始化sysv消息队列
 * @param $msg_recver
 */
$msg_recver->onWorkerStart = function ($msg_recver) {
    $msg_recver->queue = msg_get_queue($msg_recver->queueId);
};

$msg_recver->onMessage = function ($connection, $message) use ($msg_recver) {
    $msgtype = 1;
    $errorcode = 500;
    echo $message.PHP_EOL;
    // @see http://php.net/manual/zh/function.msg-send.php
    if (extension_loaded('sysvmsg') && msg_send($msg_recver->queue, $msgtype, $message, true, true, $errorcode)) {
        return $connection->send('{"code":0, "msg":"success"}');
    } else {
        return $connection->send('{"code":' . $errorcode . ', "msg":"fail"}');
    }

};


// 运行worker
Worker::runAll();