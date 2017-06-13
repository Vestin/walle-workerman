<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 4:06 PM
 */


$r = \app\core\app::$app->route;

$r->addRoute('GET', '/auth', 'AuthController@auth');
$r->addRoute('GET', '/callback', function(){
    return 'callback ok';
});