<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 10:07 AM
 */

namespace job\component;


use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

class WalleInflector implements MethodNameInflector
{

    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * $handler->handle();
     *
     * @return string
     */
    public function inflect($command, $commandHandler)
    {
        return 'handle';
    }
}