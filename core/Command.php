<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 10:18 AM
 */

namespace core;

/**
 * Class Command
 * @package core
 */
abstract class Command
{

    public $error = [];

    public function getError()
    {
        return $this->error;
    }

    public function setError(Array $error)
    {
        $this->error = $error;
    }

}