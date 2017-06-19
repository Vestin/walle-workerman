<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/16/17
 * Time: 3:41 PM
 */

namespace job\exception;


use core\Command;
use core\UserException;

class CommandException extends UserException
{

    /**
     * @param Command $command
     * @return CommandException
     */
    static public function ConstructError($commandName)
    {
        return new self('Command :' . $commandName . ' construct error.');
    }

}