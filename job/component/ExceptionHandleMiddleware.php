<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/16/17
 * Time: 5:45 PM
 */

namespace job\component;


use job\exception\CommandException;
use League\Tactician\Middleware;

class ExceptionHandleMiddleware implements Middleware
{

    protected $log;

    /**
     * LogMiddleware constructor.
     * @param $log
     */
    public function __construct($log)
    {
        $this->log = $log;
    }


    public function execute($command, callable $next)
    {
        try{
            $returnValue = $next($command);
            return true;
        }catch (CommandException $e){
            $this->log->warning('Exec command Error'.get_class($command));
            $this->log->warning('message:'.$e->getMessage());
            $this->log->warning('File:'.$e->getFile()."Line:".$e->getLine());
            return false;
        }
    }
}