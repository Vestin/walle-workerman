<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/16/17
 * Time: 5:45 PM
 */

namespace job\component;


use League\Tactician\Middleware;

class LogMiddleware implements Middleware
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
        $this->log->warning('Execute commandBus Command:'. get_class($command));
        $returnValue = $next($command);
        $this->log->warning('Execute commandBus Command:'. get_class($command).' done');
        return $returnValue;
    }
}