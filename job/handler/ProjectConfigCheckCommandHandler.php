<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 4/18/17
 * Time: 11:33 AM
 */

namespace job\handler;


use job\checker\detectConf\HostFolderPermissionChecker;
use job\checker\detectConf\HostGitSshTrustChecker;
use job\checker\detectConf\ProjectReleasePathChecker;
use job\checker\detectConf\RemotePhpReleaseFolderPermissionChecker;
use job\checker\detectConf\RemotePhpSshTrustChecker;
use job\command\ProjectConfigCheckCommand;
use job\component\WalleAllCheckDispatcher;
use job\exception\CommandException;
use Vestin\Checker\CheckerBus;
use Vestin\Checker\Dispatchers\AllCheckDispatcher;

class ProjectConfigCheckCommandHandler
{

    /**
     * handle
     * @param ProjectConfigCheckCommand $command
     */
    public function handle(ProjectConfigCheckCommand $command)
    {
        $checkBus = new CheckerBus(new WalleAllCheckDispatcher());

        // 1.检测宿主机检出目录是否可读写
        $hostFolderPermissionChecker = HostFolderPermissionChecker::byProject($command->getProject());
        // 2.检测宿主机ssh是否加入git信任
        $hostGitSshTrustChecker = HostGitSshTrustChecker::byProject($command->getProject());
        // 4.检测php用户是否加入目标机ssh信任
        $remotePhpSshTrustChecker = RemotePhpSshTrustChecker::byProject($command->getProject());
        // 6.检测php用户是否具有目标机release目录读写权限
        $remotePhpReleaseFolderPermissionChecker = RemotePhpReleaseFolderPermissionChecker::byProject($command->getProject());
        // 7.配置部署路径必须为绝对路径
        $projectReleasePathChecker = ProjectReleasePathChecker::byProject($command->getProject());
        // 添加checkers
        $checkBus->addChecker($hostFolderPermissionChecker)
            ->addChecker($hostGitSshTrustChecker)
            ->addChecker($remotePhpSshTrustChecker)
            ->addChecker($remotePhpReleaseFolderPermissionChecker)
            ->addChecker($projectReleasePathChecker);
        if (!$checkBus->check()) {
            $command->setError($checkBus->getError());
            throw new CommandException('check error');
        }
    }
}