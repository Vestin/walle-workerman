<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/16/17
 * Time: 12:41 PM
 */

require __DIR__ . "/../vendor/autoload.php";

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
$conf = new \Noodlehaus\Config(__DIR__ . '/config/config.php');

// 创建一个Worker监听2346端口，使用websocket协议通讯
$job = new Worker();
// 启动用户
$job->user = 'www-data';
// 启动4个进程对外提供服务
$job->count = 4;

$job->queueId = 1991;

if (!extension_loaded('sysvmsg')) {
    echo "Please install sysvmsg extension.\n";
    exit;
}

/**
 * 初始化sysv消息队列
 * @param $job
 */
$job->onWorkerStart = function ($job) use ($conf) {

    $app = \job\component\app::init($conf);
    $app->bootstrap();

    $job->queue = msg_get_queue($job->queueId);
    \Workerman\Lib\Timer::add(1, function () use ($job,$conf) {
        if (extension_loaded('sysvmsg')) {
            // 循环取数据
            while (1) {
                $desiredmsgtype = 1;
                $msgtype = 0;
                $message = '';
                $maxsize = 65535;
                // 从队列中获取消息 @see http://php.net/manual/zh/function.msg-receive.php
                @msg_receive($job->queue, $desiredmsgtype, $msgtype, $maxsize, $message, true, MSG_IPC_NOWAIT);
                if (!$message) {
                    return;
                }
                // 假设消息数据为json，格式类似{"class":"class_name", "method":"method_name", "args":[]}
                //$message = json_decode($message, true);
                echo $message . 'recive by job'.PHP_EOL;
                $taskId = json_decode($message,true)['taskId'];

                global $jobHandle;
                //handle job
                $jobHandle = new \job\component\JobHandle($taskId);
                try{
                    $jobHandle->handle();
                }catch (\core\UserException $e){
                    \job\component\app::$app->log->error($e->getMessage().' in file:'.$e->getFile().' in line:'.$e->getLine());
                }

            }
        }

    });
};


// 运行worker
Worker::runAll();