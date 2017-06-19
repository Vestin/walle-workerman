<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 8:25 PM
 */

namespace job\component;

use job\command\DeployCommand;
use job\command\ProjectConfigCheckCommand;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use model\Project;
use core\UserException;
use core\WalleContainer;
use FastRoute\Dispatcher;
use model\Task;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Workerman\Connection\ConnectionInterface;

/**
 * Class MessageHandle
 * @package core
 *
 * @property int $taskId
 * @property ConnectionInterface $connection
 */
class JobHandle extends WalleContainer
{

    protected $container;

    /**
     * MessageHandle constructor.
     * @param $connection
     * @param $data
     */
    public function __construct($taskId)
    {
        $this->container = new Container();
        $this->container['taskId'] = $taskId;
        $this->registerServices(app::$app->config['services']);
    }

    /**
     * app run
     */
    public function handle()
    {
        // 加载路由
        echo 'handle job' . PHP_EOL;

        var_dump($this->taskId);
        $taskModel = Task::find($this->taskId);
        if (!$taskModel) {
            throw new UserException('unknow taskId');
        }

        $this->container['task'] = $taskModel;

        $log = new Logger('app');
        $log->pushHandler(new StreamHandler('/tmp/mytest/runtime/task_' . $this->taskId . '/' . time() . '.log',
            Logger::INFO));
        $log->pushHandler(new StreamHandler('php://stdout',Logger::INFO));

        $this->container['logger'] = $log;
        $this->logger->info('log component bootstrap success.');

        // 任务失败或者审核通过时可发起上线
        if (!in_array($this->task->status, [Task::STATUS_PASS, Task::STATUS_FAILED])) {
            throw new UserException('deployment only done for once');
        }

        $handleMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new WalleLocator(),
            new WalleInflector());

        // 项目配置检测
        $commandBus = new CommandBus([
            new ExceptionHandleMiddleware($this->logger),
            new LogMiddleware($this->logger),
            $handleMiddleware,
        ]);

        $command = ProjectConfigCheckCommand::fromProjectId($this->task->project_id);
        $res = $commandBus->handle($command);
        if($res==false){
            // 命令执行失败
            $this->logger->error('Failed.'.json_encode($command->getError()));
            return false;
        }

        $deployCommand = DeployCommand::getMs();




        echo 'done' . PHP_EOL;
    }

}