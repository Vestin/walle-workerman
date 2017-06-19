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
 * // 检测宿主机检出目录是否可读写
 * Class HostFolderPermissionChecker
 * @package job\checker\detectConf
 */
class HostFolderPermissionChecker implements CheckerInterface
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
        $codeBaseDir = $this->project->getDeployFromDir();
        $isWritable = is_dir($codeBaseDir) ? is_writable($codeBaseDir) : @mkdir($codeBaseDir, 0755, true);
        if (!$isWritable) {
            throw new CheckNotPassException('hosted server is not writable error ,user :' . getenv('USER') . ' path:' . $this->project->deploy_from);
        }
    }
}