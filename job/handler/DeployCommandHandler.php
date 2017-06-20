<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 5:43 PM
 */

namespace job\handler;


use job\command\DeployCommand;
use job\component\Repo;
use job\component\Task;
use job\exception\CommandException;
use model\Record;
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

    /**
     * @var DeployCommand
     */
    protected $command;

    public function handle(DeployCommand $command)
    {
        $this->command = $command;
        /**
         * 1. 上线任务，生成上线版本
         * 2. 检查目录和权限，工作空间的准备,每一个版本都单独开辟一个工作空间，防止代码污染
         * 3. 部署前置触发任务,在部署代码之前的准备工作，如git的一些前置检查、vendor的安装（更新）
         * 4. 更新代码文件
         * 5. 部署后置触发任务
         * 6. 传输文件/目录到指定目标机器
         * 7. 执行远程服务器任务集合 /pre-release task /link / post-release task
         * 8. 只保留最大版本数，其余删除过老版本
         * 9. 收尾工作，清除宿主机的临时部署空间
         */
        try {
            $this->_makeVersion();
            $this->_initWorkspace();
            $this->_preDeploy();
            $this->_revisionUpdate();
            $this->_postDeploy();
            $this->_transmission();
            $this->_updateRemoteServers($this->getTaskModel()->link_id,
                $this->getTaskModel()->project->post_release_delay);
            $this->_cleanRemoteReleaseVersion();
            $this->_cleanUpLocal($this->getTaskModel()->link_id);

            /** 至此已经发布版本到线上了，需要做一些记录工作 */

            // 记录此次上线的版本（软链号）和上线之前的版本
            ///对于回滚的任务不记录线上版本
            $this->getTaskModel()->ex_link_id = $this->getTaskModel()->project->version;
            // 第一次上线的任务不能回滚、回滚的任务不能再回滚
            if ($this->getTaskModel()->id == 1) {
                $this->getTaskModel()->enable_rollback = \model\Task::ROLLBACK_FALSE;
            }
            $this->getTaskModel()->status = \model\Task::STATUS_DONE;
            $this->getTaskModel()->save();

            // 可回滚的版本设置
            $this->_enableRollBack();

            // 记录当前线上版本（软链）回滚则是回滚的版本，上线为新版本
            $this->getTaskModel()->project->version = $this->getTaskModel()->link_id;
            $this->getTaskModel()->project->save();

        } catch (CommandException $e) {
            $this->getTaskModel()->status = \model\Task::STATUS_FAILED;
            $this->getTaskModel()->save();
            // 清理本地部署空间
            $this->_cleanUpLocal($this->getTaskModel()->link_id);

            throw $e;
        }
    }

    /**
     * 产生一个上线版本
     */
    private function _makeVersion()
    {
        $version = date("Ymd-His", time());
        $this->getTask()->link_id = $version;

        return $this->getTaskModel()->save();
    }

    private function getTaskModel()
    {
        return $this->command->getTaskModel();
    }

    private function getTask()
    {
        return $this->command->getTask();
    }

    private function getFolder()
    {
        return $this->command->getFolder();
    }

    /**
     * 检查目录和权限，工作空间的准备
     * 每一个版本都单独开辟一个工作空间，防止代码污染
     *
     * @return void
     * @throws \Exception
     */
    private function _initWorkspace()
    {
        $sTime = Record::getMs();
        // 本地宿主机工作区初始化
        $this->getFolder()->initLocalWorkspace($this->getTaskModel());

        // 远程目标目录检查，并且生成版本目录
        $ret = $this->getFolder()->initRemoteVersion($this->getTaskModel()->link_id);
        // 记录执行时间
        $duration = Record::getMs() - $sTime;
        Record::saveRecord($this->getFolder(), $this->getTaskModel()->id, Record::ACTION_PERMSSION, $duration);

        if (!$ret) {
            throw new CommandException('init deployment workspace error');
        }
    }

    /**
     * 更新代码文件
     *
     * @return void
     * @throws \Exception
     */
    private function _revisionUpdate()
    {
        // 更新代码文件
        $revision = Repo::getRevisionByProject($this->getTaskModel()->project, $this->logger);
        $sTime = Record::getMs();
        $ret = $revision->updateToVersion($this->getTaskModel()); // 更新到指定版本
        // 记录执行时间
        $duration = Record::getMs() - $sTime;
        Record::saveRecord($revision, $this->getTaskModel()->id, Record::ACTION_CLONE, $duration);

        if (!$ret) {
            throw new CommandException('update code error');
        }
    }

    /**
     * 部署前置触发任务
     * 在部署代码之前的准备工作，如git的一些前置检查、vendor的安装（更新）
     *
     * @return bool
     * @throws \Exception
     */
    private function _preDeploy()
    {
        $sTime = Record::getMs();
        $ret = $this->getTask()->preDeploy($this->getTaskModel()->link_id);
        // 记录执行时间
        $duration = Record::getMs() - $sTime;
        Record::saveRecord($this->getTask(), $this->getTaskModel()->id, Record::ACTION_PRE_DEPLOY, $duration);

        if (!$ret) {
            throw new CommandException('pre deploy task error');
        }

        return true;
    }


    /**
     * 部署后置触发任务
     * git代码检出之后，可能做一些调整处理，如vendor拷贝，配置环境适配（mv config-test.php config.php）
     *
     * @return void
     * @throws CommandException
     */
    private function _postDeploy()
    {
        $sTime = Record::getMs();
        $ret = $this->getTask()->postDeploy($this->getTaskModel()->link_id);
        // 记录执行时间
        $duration = Record::getMs() - $sTime;
        Record::saveRecord($this->getTask(), $this->getTaskModel()->id, Record::ACTION_POST_DEPLOY, $duration);

        if (!$ret) {
            throw new CommandException('post deploy task error');
        }

    }

    /**
     * 传输文件/目录到指定目标机器
     *
     * @return void
     * @throws CommandException
     */
    private function _transmission()
    {

        $sTime = Record::getMs();
        // 循环 scp
        $this->getFolder()->scpCopyFiles($this->getTaskModel()->project, $this->getTaskModel());

        // 记录执行时间
        $duration = Record::getMs() - $sTime;

        Record::saveRecord($this->getFolder(), $this->getTaskModel()->id, Record::ACTION_SYNC, $duration);
    }

    /**
     * 执行远程服务器任务集合
     * 对于目标机器更多的时候是一台机器完成一组命令，而不是每条命令逐台机器执行
     *
     * @param string $version
     * @param integer $delay 每台机器延迟执行post_release任务间隔, 不推荐使用, 仅当业务无法平滑重启时使用
     * @throws \Exception
     */
    private function _updateRemoteServers($version, $delay = 0)
    {
        $cmd = [];
        // pre-release task
        if (($preRelease = $this->getTask()->getRemoteTaskCommand($this->getTaskModel()->project->pre_release,
            $version))
        ) {
            $cmd[] = $preRelease;
        }
        // link
        if (($linkCmd = $this->getFolder()->getLinkCommand($version))) {
            $cmd[] = $linkCmd;
        }
        // post-release task
        if (($postRelease = $this->getTask()->getRemoteTaskCommand($this->getTaskModel()->project->post_release,
            $version))
        ) {
            $cmd[] = $postRelease;
        }

        $sTime = Record::getMs();
        // run the task package
        $ret = $this->getTask()->runRemoteTaskCommandPackage($cmd, $delay);
        // 记录执行时间
        $duration = Record::getMs() - $sTime;
        Record::saveRecord($this->getTask(), $this->getTaskModel()->id, Record::ACTION_UPDATE_REMOTE, $duration);
        if (!$ret) {
            throw new CommandException('update servers error');
        }

        return true;
    }

    /**
     * 可回滚的版本设置
     *
     * @return int
     */
    private function _enableRollBack()
    {
        $offset = \model\Task::where([
            ['status', '=', \model\Task::STATUS_DONE],
            ['project_id', '=', $this->getTaskModel()->project_id]
        ])
            ->orderBy('id', 'desc')
            ->first();
        if (!$offset) {
            return true;
        }

        return \model\Task::where('id', '<=', $offset->id)->update(['enable_rollback' => \model\Task::ROLLBACK_FALSE]);
    }

    /**
     * 只保留最大版本数，其余删除过老版本
     */
    private function _cleanRemoteReleaseVersion()
    {
        return $this->getTask()->cleanUpReleasesVersion();
    }

    /**
     * 执行远程服务器任务集合回滚，只操作pre-release、link、post-release任务
     *
     * @param $version
     * @throws \Exception
     */
    public function _rollback($version)
    {
        return $this->_updateRemoteServers($version);
    }

    /**
     * 收尾工作，清除宿主机的临时部署空间
     */
    private function _cleanUpLocal($version = null)
    {
        // 创建链接指向
        $this->getFolder()->cleanUpLocal($version);

        return true;
    }
}