<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 2:57 PM
 */

namespace app\component;


use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;

class WalleLocator implements HandlerLocator
{

    public function getHandlerForCommand($commandName)
    {
        $className = get_class($commandName);
        $className = substr($className,14);
        return "app\\handler\\".$className."Handler";
    }
}