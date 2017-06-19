<?php
/* *****************************************************************
 * @Author: wushuiyong
 * @Created Time : 日 10/18 15:41:42 2015
 *
 * @File Name: components/Repo.php
 * @Description:
 * *****************************************************************/

namespace job\component;

use model\Project;
use Monolog\Logger;

class Repo
{

    /**
     * 获取版本管理句柄
     *
     * @param Project $project
     * @param Logger $log
     * @return Git|Svn
     * @throws \Exception
     */
    public static function getRevisionByProject($project,$log)
    {
        switch ($project->repo_type) {
            case Project::REPO_GIT:
                return new Git($project,$log);
            case Project::REPO_SVN:
                return new Svn($project,$log);
            default:
                throw new \Exception('unknown scm');
                break;
        }
    }

}