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
$router->post('sorted-set', ['uses' => 'RedisController@sortedSet', 'as' => 'redis.sortedSet', 'name' => '【redis：sorted set类型使用】']);
$router->post('hash', ['uses' => 'RedisController@hash', 'as' => 'redis.hash', 'name' => '【redis：hash类型使用】']);
$router->post('pub', ['uses' => 'RedisController@pub', 'as' => 'redis.pub', 'name' => '【redis：发布订阅模式使用 发布者】']);
$router->post('sub', ['uses' => 'RedisController@sub', 'as' => 'redis.sub', 'name' => '【redis：发布订阅模式使用 订阅者】']);
$router->post('transactions', ['uses' => 'RedisController@transactions', 'as' => 'redis.transactions', 'name' => '【redis：事务的使用】']);
$router->post('eval', ['uses' => 'RedisController@eval', 'as' => 'redis.eval', 'name' => '【redis：脚本的使用】']);
