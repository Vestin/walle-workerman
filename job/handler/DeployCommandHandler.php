<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 5:43 PM
 */

namespace job\handler;


use job\command\DeployCommand;
use Monolog\Logger;

class DeployCommandHandler
{

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct()
    {
        global $jobHandle;
        $this->logger = $jobHandle->logger;
    }

    protected $command;

    public function handle(DeployCommand $command)
    {
        $this->command = $command;
        $this->_makeVersion();
    }

    /**
     * 产生一个上线版本
     */
    private function _makeVersion()
    {
        $version = date("Ymd-His", time());
        $this->getTask()->link_id = $version;

        return $this->getTask()->save();
    }

    private function getTask()
    {
        return $this->command->getTask();
    }

    private function getFolder()
    {
        return $this->command->folder;
    }

}