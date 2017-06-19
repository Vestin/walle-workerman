<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 4/18/17
 * Time: 2:23 PM
 */

namespace job\checker\detectConf;


use job\component\Repo;
use job\component\Task;
use model\Project;
use Vestin\Checker\CheckerInterface;
use Vestin\Checker\CheckNotPassException;

/**
 * 检测php用户是否加入目标机ssh信任
 * Class HostGitSshTrustChecker
 * @package job\checker\detectConf
 */
class RemotePhpSshTrustChecker implements CheckerInterface
{

    /**
     * @var Project
     */
    private $project;

    /**
     * @var
     */
    private $logger;

    private function __construct(Project $project, $logger)
    {
        $this->project = $project;
        $this->logger = $logger;
    }

    static public function byProject(Project $project)
    {
        global $jobHandle;
        return new static($project, $jobHandle->logger);
    }

    /**
     * 检查工作
     * 检查通过，不做任何处理
     * 检查失败，抛出检查失败异常
     * @throws \Vestin\Checker\CheckNotPassException
     * @return void
     */
    public function check()
    {
        $task = new Task($this->project, $this->logger);
        $command = 'id';
        $ret = $task->runRemoteTaskCommandPackage([$command]);
        if (!$ret) {
            $error = 'target server ssh error , local_user:' . getenv('USER') . ' remote_user:' . $this->project->release_user . ' path:' . $this->project->release_to;
            throw new CheckNotPassException($error);
        }
    }
}