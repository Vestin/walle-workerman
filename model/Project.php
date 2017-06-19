<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "conf".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $level
 * @property integer $status
 * @property string $version
 * @property string $repo_url
 * @property string $repo_username
 * @property string $repo_password
 * @property string $repo_mode
 * @property string $repo_type
 * @property string $deploy_from
 * @property string $excludes
 * @property string $release_user
 * @property string $release_to
 * @property string $release_library
 * @property string $hosts
 * @property string $pre_deploy
 * @property string $post_deploy
 * @property string $pre_release
 * @property string $post_release
 * @property string $post_release_delay
 * @property integer $audit
 * @property integer $ansible
 * @property integer $keep_version_num
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Project extends Model
{

    // 有效状态
    const STATUS_VALID = 1;

    // 测试环境
    const LEVEL_TEST = 1;

    // 仿真环境
    const LEVEL_SIMU = 2;

    // 线上环境
    const LEVEL_PROD = 3;

    const AUDIT_YES = 1;

    const AUDIT_NO = 2;

    const REPO_MODE_BRANCH = 'branch';

    const REPO_MODE_TAG = 'tag';

    const REPO_MODE_NONTRUNK = 'nontrunk';

    const REPO_GIT = 'git';

    const REPO_SVN = 'svn';

    public static $CONF;

    public static $LEVEL = [
        self::LEVEL_TEST => 'test',
        self::LEVEL_SIMU => 'simu',
        self::LEVEL_PROD => 'prod',
    ];

    protected $table = 'project';

    public static function getProjectById($id)
    {
        return static::find($id);
    }

    /**
     * 获取当前进程的项目配置
     *
     * @param $id
     * @return self
     */
    public static function getConf($id = null)
    {
        if (empty(static::$CONF)) {
            static::$CONF = static::find($id);
        }
        return static::$CONF;
    }

    /**
     * 根据git地址获取项目名字
     *
     * @param $gitUrl
     * @return mixed
     */
    public function getGitProjectName($gitUrl)
    {
        if (preg_match('#.*/(.*?)\.git#', $gitUrl, $match)) {
            return $match[1];
        }

        return basename($gitUrl);
    }

    /**
     * 拼接宿主机的部署隔离工作空间
     * {deploy_from}/{env}/{project}-YYmmdd-HHiiss
     *
     * @return string
     */
    public function getDeployWorkspace($version)
    {
        $from = $this->deploy_from;
        $env = isset(static::$LEVEL[$this->level]) ? static::$LEVEL[$this->level] : 'unknow';
        $project = $this->getGitProjectName($this->repo_url);

        return sprintf("%s/%s/%s-%s", rtrim($from, '/'), rtrim($env, '/'), $project, $version);
    }

    /**
     * 获取 ansible 宿主机tar文件路径
     *
     * {deploy_from}/{env}/{project}-YYmmdd-HHiiss.tar.gz
     *
     * @param $version
     * @return string
     */
    public function getDeployPackagePath($version)
    {

        return sprintf('%s.tar.gz', $this->getDeployWorkspace($version));
    }

    /**
     * 拼接宿主机的仓库目录
     * {deploy_from}/{env}/{project}
     *
     * @return string
     */
    public function getDeployFromDir()
    {
        $from = $this->deploy_from;
        $env = isset(static::$LEVEL[$this->level]) ? static::$LEVEL[$this->level] : 'unknow';
        $project = $this->getGitProjectName($this->repo_url);

        return sprintf("%s/%s/%s", rtrim($from, '/'), rtrim($env, '/'), $project);
    }

    /**
     * 拼接宿主机的SVN仓库目录(带branches/tags目录)
     *
     * @param string $branchName
     * @return string
     */
    public function getSvnDeployBranchFromDir($branchName = 'trunk')
    {

        $deployFromDir = $this->getDeployFromDir();
        if ($branchName == '') {
            $branchFromDir = $deployFromDir;
        } elseif ($branchName == 'trunk') {
            $branchFromDir = sprintf('%s/trunk', $deployFromDir);
        } elseif ($this->repo_mode == static::REPO_MODE_BRANCH) {
            $branchFromDir = sprintf('%s/branches/%s', $deployFromDir, $branchName);
        } elseif ($this->repo_mode == static::REPO_MODE_TAG) {
            $branchFromDir = sprintf('%s/tags/%s', $deployFromDir, $branchName);
        }

        return $branchFromDir;
    }

    /**
     * 获取目标机要发布的目录
     * {webroot}
     *
     * @param $version
     * @return string
     */
    public static function getTargetWorkspace()
    {
        return rtrim(static::$CONF->release_to, '/');
    }

    /**
     * 拼接目标机要发布的目录
     * {release_library}/{project}/{version}
     *
     * @param $version
     * @return string
     */
    public function getReleaseVersionDir($version = '')
    {
        return sprintf('%s/%s/%s', rtrim($this->release_library, '/'),
            $this->getGitProjectName($this->repo_url), $version);
    }

    /**
     * 拼接目标机要发布的打包文件路径
     * {release_library}/{project}/{version}.tar.gz
     *
     * @param string $version
     * @return string
     */
    public function getReleaseVersionPackage($version = '')
    {

        return sprintf('%s.tar.gz', $this->getReleaseVersionDir($version));
    }

    /**
     * 获取当前进程配置的目标机器host列表
     */
    public function getHosts()
    {
        return \job\component\GlobalHelper::str2arr(static::$CONF->hosts);
    }

    /**
     * 获取当前进程配置的ansible状态
     *
     * @return boolean
     */
    public static function getAnsibleStatus()
    {
        return (bool)static::$CONF->ansible;
    }

    /**
     * 获取当前进程配置的ansible hosts文件路径
     *
     * {ansible_hosts.dir}/project_{projectId}
     *
     * @param integer $projectId 可以传入指定的id
     * @return string
     */
    public static function getAnsibleHostsFile($projectId = 0)
    {
        if (!$projectId) {
            $projectId = static::$CONF->id;
        }
        return sprintf('%s/project_%d', rtrim(yii::$app->params['ansible_hosts.dir'], '/'), $projectId);
    }

}
