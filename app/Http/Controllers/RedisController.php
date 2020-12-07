<?php

namespace App\Http\Controllers;


use App\Utils\Responser;
use Illuminate\Support\Facades\Redis;

/**
 * redis使用
 * Class RedisController
 * @package App\Http\Controllers
 */
class RedisController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 字符串使用
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function string()
    {
        // 设置字符串永久存储
        $setString = Redis::set('user', '测试人');
        // 设置字符串存储20秒
        $setExString = Redis::setEx('user', 20, '测试人');
        // 在指定的 key 不存在时，为 key 设置指定的值, 覆盖设置失败返回0, 成功返回1
        $setNxString = Redis::setNx('user2', '测试人3');
        // 获取字符串有效时间
        $getTtl = Redis::ttl('user');
        // 获取字符串数据
        $setString = Redis::get('user');
        // 获取字符串指定位置 中文是2.5个字符
        $getrange = Redis::getrange('user', 0, 2.5);
        // 返回给定 key 的旧值。 当 key 没有旧值时，即 key 不存在时，返回 false , $getSetString此时为测试人
        $getSetString = Redis::getset('user', '测试人2');
        //自增
        $setNum = Redis::set('num', 10);
        $incrNum = Redis::incr('num', 2);
        $getNum = Redis::get('num');
        //自减
        $decrNum = Redis::decr('num', 2);
        // 查询user为开头的所有key
        $keysArray = Redis::keys('user*');
        // 返回所有(一个或多个)给定 key 的值。 如果给定的 key 里面，有某个 key 不存在,返回null
        $mgetArray = Redis::mget($keysArray);
        return success();
    }

    /**
     * list的使用
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        // 将一个或多个值插入到列表头部。 如果 key 不存在，一个空列表会被创建并执行 LPUSH 操作。 当 key 存在但不是列表类型时，返回一个错误
        $lpush = Redis::lpush('list4', 'aa');
        $lpush = Redis::lpush('list4', 'bb');
        // 将一个值插入到已存在的列表头部，当且仅当 key 存在并且是一个列表。
        $lpushx = Redis::lpushx('list9', 'cc');
        // 用于将一个或多个值插入到列表的尾部(最右边)。
        $rpush = Redis::rpush('list4', 'hh');
        // 将一个值插入到已存在的列表尾部(最右边)，当且仅当 key 存在并且是一个列表。
        $rpushx = Redis::rpushx('list9', 'cc');
        // 返回列表的长度 如果列表 key 不存在，则 key 被解释为一个空列表，返回 0 。 如果 key 不是列表类型，返回一个错误
        $listLen = Redis::llen('list1');
        // 获取列表指定范围内的元素 (此处查询的全部)
        $listData = Redis::lrange('list1', 0, $listLen - 1);
        // 通过索引获取列表中的元素 (查询一个)
        $oneData = Redis::lindex('list1', 0);
        // 移出并获取列表的第一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。没有的话等待5秒后返回null
        $blpop = Redis::blpop('list1', 5);
        // 移出并获取列表的最后一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。没有的话等待5秒后返回null
        $brpop = Redis::brpop('list1', 5);
        // 从列表中取出最后一个元素，并插入到另外一个列表的头部； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止
        $brpoplpush = Redis::brpoplpush('list1', 'list2', 3);
        // 从列表中取出最后一个元素，并插入到另外一个列表的头部；
        $rpoplpush = Redis::rpoplpush('list1', 'list2');
        // 在列表的元素前或者后插入元素。当指定元素不存在于列表中时，不执行任何操作 (第一个元素)
        $linsert = Redis::linsert('list2', 'before', 'aa', 'cc');
        // 移出并获取列表的第一个元素
        $lpop = Redis::lpop('list4');
        // 移出并获取列表的最后一个元素
        $rpop = Redis::rpop('list4');
        // 通过索引设置列表元素的值
        $lset = Redis::lset('list4', 0, 'kk');
        // 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除
        $ltrim = Redis::ltrim('list4', 0, -1);
        return success();
    }

    /**
     * set的使用
     * @access public
     * @param array $param
     * @return array
     */
    public function set()
    {

    }
}
