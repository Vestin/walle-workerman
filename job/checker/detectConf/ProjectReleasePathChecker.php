<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 4/18/17
 * Time: 1:47 PM
 */

namespace job\checker\detectConf;


use model\Project;
use Vestin\Checker\CheckerInterface;
use Vestin\Checker\CheckNotPassException;

/**
 * 部署路径必须为绝对路径
 * Class HostFolderPermissionChecker
 * @package job\checker\detectConf
 */
class ProjectReleasePathChecker implements CheckerInterface
{

    /**
     * @var Project
     */
    private $project;

    private function __construct(Project $project)
    {
        $this->project = $project;
    }

    static public function byProject(Project $project)
    {
        return new static($project);
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
        $needAbsoluteDir = [
            'deploy from' => $this->project->deploy_from,
            'webroot' => $this->project->release_to,
            'releases' => $this->project->release_library,
        ];
        $success = true;
        $error = '';
        foreach ($needAbsoluteDir as $tips => $dir) {
            if (0 !== strpos($dir, '/')) {
                $success = false;
                $error .= 'config dir must absolute ; ' . sprintf('%s:%s', $tips, $dir) . ';';
            }
        }
        if (!$success) {
            throw new CheckNotPassException($error);
        }
    }
}