<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 10:50 AM
 */

namespace app\controller;


class AuthController
{
    public function auth($token)
    {
        return 'token is '.$token;
    }
}