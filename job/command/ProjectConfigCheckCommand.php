<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 10:17 AM
 */

namespace job\command;


use core\Command;
use job\exception\CommandException;
use model\Project;

class ProjectConfigCheckCommand extends Command
{

    /**
     * @var Project
     */
    public $project;

    /**
     * ProjectConfigCheckCommand constructor.
     * @param $projectId
     * @param $projectInfo
     */
    private function __construct($projectId, $project)
    {
        $this->project = $project;
    }

    /**
     * @param $projectId
     * @return static
     * @throws CommandException
     */
    static public function fromProjectId($projectId)
    {
        $project = Project::getConf($projectId);
        if (!$project) {
            throw CommandException::ConstructError(self::class);
        }

        return new static($projectId, $project);
    }

    public function getProject()
    {
        return $this->project;
    }

}