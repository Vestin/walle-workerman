<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 4/18/17
 * Time: 2:23 PM
 */

namespace job\checker\detectConf;


use job\component\Repo;
use model\Project;
use Vestin\Checker\CheckerInterface;
use Vestin\Checker\CheckNotPassException;

/**
 * 检测宿主机ssh是否加入git信任
 * Class HostGitSshTrustChecker
 * @package job\checker\detectConf
 */
class HostGitSshTrustChecker implements CheckerInterface
{

    /**
     * @var Project
     */
    private $project;

    /**
     * @var
     */
    private $logger;

    private function __construct(Project $project,$logger)
    {
        $this->project = $project;
        $this->logger = $logger;
    }

    static public function byProject(Project $project)
    {
        global $jobHandle;
        return new static($project,$jobHandle->logger);
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
        $revision = Repo::getRevisionByProject($this->project,$this->logger);
        $ret = $revision->updateRepo();
        if (!$ret) {
            $error = $this->project->repo_type == Project::REPO_GIT ? getenv("USER").'ssh-key to git': 'correct username passwd';
            throw new CheckNotPassException('hosted server ssh error'.$error);
        }
    }
}