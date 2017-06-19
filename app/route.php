<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 4:06 PM
 */
use app\component\app;

$r = app::$app->route;

$r->addRoute('GET', '/auth', 'AuthController@auth');
$r->addRoute('GET', '/callback', function(){
    return 'callback ok';
});
$r->addRoute('POST', '/auto-task' , 'TaskController@createAutoTask');