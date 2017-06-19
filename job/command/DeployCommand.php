<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 5:21 PM
 */

namespace job\command;

use job\component\Folder;
use core\Command;
use job\component\Task;
use model\Task as TaskModel;
use model\Project;

class DeployCommand extends Command
{

    /**
     * @var Project
     */
    public $project;

    /**
     * @var \job\component\Task Task
     */
    public $task;

    /**
     * @var Folder
     */
    public $folder;

    /**
     * @var TaskModel
     */
    public $taskModel;

    /**
     * DeployCommand constructor.
     * @param Project $project
     * @param \job\component\Task $task
     * @param \job\component\Folder $folder
     */
    public function __construct(TaskModel $taskModel,Project $project,Task $task,Folder $folder)
    {
        $this->taskModel = $taskModel;
        $this->project = $project;
        $this->task = $task;
        $this->folder = $folder;
    }

    /**
     * @param TaskModel $taskModel
     * @return static
     */
    public function fromTask(TaskModel $taskModel)
    {
        global $jobHandle;
        return new static($taskModel,new Task($project,$jobHandle->logger),new Folder($project,$jobHandle->logger));
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return \job\component\Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }




}