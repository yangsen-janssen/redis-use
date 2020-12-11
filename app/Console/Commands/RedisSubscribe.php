<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribe extends Command
{
    /**
     * 控制台命令的名称和签名。
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * 执行控制台命令。
     *
     * @return mixed
     */
    public function handle()
    {
        // 用于订阅给定的一个或多个频道的信息
//        Redis::subscribe(['test-channel'], function ($message, $channel) {
//            if ($channel == 'test-channel') {
//                echo 'test-channel';
//            } else {
//                echo $channel . ':' . $message;
//            }
//        });
        // 订阅一个或多个符合给定模式的频道
        Redis::psubscribe(['test*'], function ($message, $channel) {
            $data = Redis::set($channel, $message);
            echo $channel . ':' . $message . '；设置结果:' . $data;
        });
    }
}
