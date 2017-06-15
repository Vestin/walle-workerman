<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/15/17
 * Time: 5:30 PM
 */

namespace app\exception;


class AuthException extends UserException
{

    /**
     * all auth error is 401 error
     * @var int
     */
    protected $code = 401;

    /**
     * token error
     */
    static public function incorrectToken(){
        return new static('incorrect token');
    }

}