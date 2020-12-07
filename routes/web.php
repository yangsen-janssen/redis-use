<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('string', ['uses' => 'RedisController@string', 'as' => 'redis.string', 'name' => '【redis：string类型使用】']);
$router->post('list', ['uses' => 'RedisController@list', 'as' => 'redis.list', 'name' => '【redis：list类型使用】']);
$router->post('set', ['uses' => 'RedisController@set', 'as' => 'redis.set', 'name' => '【redis：set类型使用】']);
