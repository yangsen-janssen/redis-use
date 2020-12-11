<?php

namespace App\Http\Controllers;


use App\Utils\Responser;

use Illuminate\Http\Request;
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
     * 字符串使用 (使用场景 缓存 点击数统计 浏览统计 加锁)
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
     * list的使用 (使用场景 消息队列 排行榜 最新列表)
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
     * 使用场景：
     * 1、标签：比如我们博客网站常常使用到的兴趣标签，把一个个有着相同爱好，关注类似内容的用户利用一个标签把他们进行归并。
     * 2、共同好友功能，共同喜好，或者可以引申到二度好友之类的扩展应用。
     * 3、微博应用中，可以将一个用户所有的关注人存在一个集合中，将其所有粉丝存在一个集合。Redis还为集合提供了求交集、并集、差集等操作
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function set()
    {
        // 将一个或多个成员元素加入到集合中，已经存在于集合的成员元素将被忽略
        $sadd = Redis::sadd('myset', 'myset_value', 'myset_value2');
        $sadd = Redis::sadd('myset_two', 'myset_value', 'myset_value_two2');
        // 返回集合中的所有的成员。 不存在的集合 key 被视为空集合
        $setData = Redis::smembers('myset');
        // 返回集合中元素的数量
        $scard = Redis::scard('myset');
        // 第一个集合与其他集合之间的差异，也可以认为说第一个集合中独有的元素
        $sdiff = Redis::sdiff('myset', 'myset_two');
        // 将给定集合之间的差集存储在指定的集合中。如果指定的集合 key 已存在，则会被覆盖
        $sdiffstore = Redis::sdiffstore('myset_three', 'myset', 'myset_two');
        // 返回给定所有给定集合的交集
        $sinter = Redis::sinter('myset', 'myset_two');
        // 将给定集合之间的交集存储在指定的集合中。如果指定的集合已经存在，则将其覆盖
        $sinterstore = Redis::sinterstore('myset_four', 'myset', 'myset_two');
        // 判断成员元素是否是集合的成员。 成员元素是集合的成员，返回 true 。 如果成员元素不是集合的成员，或 key 不存在，返回 false
        $sismember = Redis::sismember('myset', 'myset_value');
        // 将指定成员 myset_value2 元素从 myset 集合移动到 myset_two 集合。SMOVE 是原子性操作
        $smove = Redis::smove('myset', 'myset_two', 'myset_value2');
        // 移除集合中的指定 key 的一个或多个随机元素，移除后会返回移除的元素
        $spop = Redis::spop('myset_two', 1);
        // 用于返回集合中的一个或多个随机元素
        $srandmember = Redis::srandmember('myset_two', 1);
        // 移除集合中的一个或多个成员元素，不存在的成员元素会被忽略 返回值:被成功移除的元素的数量;不包括被忽略的元素
        $srem = Redis::srem('myset_two', 'myset_value_two2');
        // 返回给定集合的并集 返回值：并集成员的列表
        $sunion = Redis::sunion('myset', 'myset_two');
        // 将给定集合的并集存储在指定的集合 destination 中。如果 destination 已经存在，则将其覆盖 返回值：结果集中的元素数量
        $sunionstore = Redis::sunionstore('myset_five', 'myset', 'myset_two');
        // 用于迭代集合中键的元素 筛选数据 分页搜索返回 返回值：数组列表
        Redis::sadd('myset_sex', 'myset_value', 'hk', 'gk', 'hy', 'ko', 'hp');
        $sscan = Redis::sscan('myset_sex', 2, ['match' => 'h*', 'count' => 10]);
        return success();
    }

    /**
     * sorted set(有序集合) 的使用
     * 使用场景：
     * 1.存储学生成绩快速做成绩排名功能
     * 2.做带权重的队列 比如普通消息的score为1，重要消息的score为2，然后工作线程可以选择按score的倒序来获取工作任务。让重要的任务优先执行。
     * 3.排行榜：有序集合经典使用场景。例如视频网站需要对用户上传的视频做排行榜，榜单维护可能是多方面：按照时间、按照播放量、按照获得的赞数等
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortedSet()
    {
        // 将一个或多个成员元素及其分数值加入到有序集当中 返回值：被成功添加的新成员的数量，不包括那些被更新的、已经存在的成员
        $zadd = Redis::zadd('myzset', 1, 'myzset_value', 2 , 'myzset_value2', 2, 'myzset_value3');
        // 用于计算集合中元素的数量。
        $zcard = Redis::zcard('myzset');
        // 用于计算有序集合中指定分数区间的成员数量 (查询排第1到第2的数据个数)
        $zcount = Redis::zcount('myzset', 1, 2);
        // 对有序集合中指定成员的分数加上增量
        $zincrby = Redis::zincrby('myzset', 2, 'myzset_value');
        // 计算给定的一个或多个有序集的交集，其中给定 key 的数量必须以 numkeys 参数指定，并将该交集(结果集)储存到 destination 。
        // 将shuxue * 1, yuwen * 2 进行sum,min,max处理
        $zadd = Redis::zadd('shuxue', 62, 'Li_Lei', 56, 'xiao_ming', 99, 'wang_ba_tian');
        $zadd = Redis::zadd('yuwen', 72, 'Li_Lei', 54, 'xiao_ming', 88, 'wang_ba_tian');
        $zinterstore = Redis::zinterstore('sum_point', ['shuxue', 'yuwen'], ['aggregate' => 'sum', 'weights' => [1, 2]]);
        // 计算给定的一个或多个有序集的并集，其中给定 key 的数量必须以 numkeys 参数指定，并将该并集(结果集)储存到 destination 。
        $zadd = Redis::zadd('shuxue2', 62, 'Li_Lei', 56, 'xiao_ming', 99, 'wang_ba_tian');
        $zadd = Redis::zadd('yuwen2', 72, 'Li_Lei', 54, 'xiao_ming', 88, 'wang_ba_tian');
        $zunionstore = Redis::zunionstore('sum_point', ['shuxue2', 'yuwen2'], ['aggregate' => 'sum', 'weights' => [1, 2]]);
        // 计算有序集合中指定字典区间内成员数量
        $zlexcount = Redis::zlexcount('sum_point', '-', '+'); // 查全部
        $zlexcount = Redis::zlexcount('sum_point', '[Li_Lei', '[wang_ba_tian'); // 查区间
        // 返回有序集中，指定区间内的成员 成员的位置按分数值递增(从小到大)来排序
        $zrange = Redis::zrange('sum_point', 0, -1, true);
        // 命令返回有序集中，指定区间内的成员。其中成员的位置按分数值递减(从大到小)来排列。具有相同分数值的成员按字典序的逆序(reverse lexicographical order)排列
        $zrevrange = Redis::zrevrange('sum_point', 0, -1, true);
        // 通过字典区间返回有序集合的成员
        $zrangebylex = Redis::zrangebylex('myzset', '-', '[myzset_value2'); // 包含自己
        $zrangebylex = Redis::zrangebylex('myzset', '-', '(myzset_value2'); // 不包含自己
        // 返回有序集合中指定分数区间的成员列表。有序集成员按分数值递增(从小到大)次序排列 -inf(最小) +inf(最大) ( (不包含自己)
        $zrangebyscore = Redis::zrangebyscore('shuxue', '(62', '99');
        // 返回有序集中指定分数区间内的所有的成员。有序集成员按分数值递减(从大到小)的次序排列 +inf -inf [ (
        $zrevrangebyscore = Redis::zrevrangebyscore('shuxue', 55, 65);
        // 返回有序集中指定成员的排名。其中有序集成员按分数值递增(从小到大)顺序排列 显示 wang_ba_tian 的分数排名，第5
        $zrank = Redis::zrank('shuxue', 'wang_ba_tian');
        // 返回有序集中成员的排名。其中有序集成员按分数值递减(从大到小)排序 第2
        $zrevrank = Redis::zrevrank('shuxue', 'wang_ba_tian');
        // 用于移除有序集中的一个或多个成员，不存在的成员将被忽略
        $zrem = Redis::zrem('shuxue', 'wang_ba_tian');
        // 用于移除有序集合中给定的字典区间的所有成员 xiao_ming-Li_Lei(包含)之间数据删除
        $zremrangebylex = Redis::zremrangebylex('shuxue', '(xiao_ming', '[Li_Lei');
        // 用于移除有序集中，指定排名(rank)区间内的所有成员。位置0的数据删除
        $zremrangebyrank = Redis::zremrangebyrank('shuxue', 0, 0);
        // 用于移除有序集中，指定分数（score）区间内的所有成员 删除排名55 到 65 之间的
        $zremrangebyscore = Redis::zremrangebyscore('shuxue', 55, 65);
        // 返回有序集中，成员的分数值。 如果成员元素不是有序集 key 的成员，或 key 不存在，返回 false
        $zscore = Redis::zscore('yuwen', 'Li_Lei');
        // 用于迭代集合中键的元素 筛选数据 分页搜索返回 返回值：数组列表 (遗留：zscan第二个参数除了0，输入其他任何参数，返回值一样)
        $zadd = Redis::zadd('myzset2', 1, 'gh', 2 , 'gk', 2, 'hk');
        $zscan = Redis::zscan('myzset2', 1, ['match' => 'g*', 'count' => 10]);
        return success();
    }

    /**
     * hash 的使用
     * 使用场景：
     * 1.存储用户信息 用户的昵称、年龄、性别、积分
     * 2.购物车
     * 3.存储对象
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function hash()
    {
        // 用于为哈希表中的字段赋值 。如果哈希表不存在，一个新的哈希表被创建并进行 HSET 操作。如果字段已经存在于哈希表中，旧值将被覆盖
        $hset = Redis::hset('myhash', 'field1', 'value1');
        // 用于同时将多个 field-value (字段-值)对设置到哈希表中。
        $hmset = Redis::hmset('myhash', 'field2', 5, 'field3', 6);
        // 查看哈希表的指定字段是否存在。
        $hexists = Redis::hexists('myhash', 'field1');
        // 返回哈希表中，所有的字段和值。
        $hgetall = Redis::hgetall('myhash');
        // 用于返回哈希表中指定字段的值。
        $hget = Redis::hget('myhash', 'field1');
        // 删除哈希表 key 中的一个或多个指定字段，不存在的字段将被忽略
        $hdel = Redis::hdel('myhash', 'field1');
        // 用于为哈希表中的字段值加上指定增量值 增量也可以为负数，相当于对指定字段进行减法操作 如果指定的字段不存在，那么在执行命令前，字段的值被初始化为 0
        $hincrby = Redis::hincrby('myhash', 'field2', 2);
        // 为哈希表中的字段值加上指定浮点数增量值 如果指定的字段不存在，那么在执行命令前，字段的值被初始化为 0
        $hincrbyfloat = Redis::hincrbyfloat('myhash', 'field2', 0.4);
        // 用于获取哈希表中的所有域（field）
        $hkeys = Redis::hkeys('myhash');
        // 用于获取哈希表中字段的数量
        $hlen = Redis::hlen('myhash');
        // 用于返回哈希表中，一个或多个给定字段的值
        $hmget = Redis::hmget('myhash', 'field2', 'field3');
        // 用于为哈希表中不存在的的字段赋值 如果字段已经存在于哈希表中，操作无效
        $hsetnx = Redis::hsetnx('myhash', 'field3', 'value3');
        // 返回哈希表所有域(field)的值。
        $hvals = Redis::hvals('myhash');
        // 用于迭代集合中键的元素 筛选数据 分页搜索返回 返回值：数组列表 (遗留：zscan第二个参数除了0，输入其他任何参数，返回值一样)
        $hmset = Redis::hmset('myhashscan', 'hk', 'value1', 'hy', 'value2', 'gy', 'value3');
        $hscan = Redis::hscan('myhashscan', 1, ['match' => '*y', 'count' => 10]);
        return success();
    }

    /**
     * https://segmentfault.com/a/1190000010287367
     * 发布订阅模式 的使用
     * 使用场景：
     * 1.构建实时消息系统，比如普通的即时聊天，群聊等功能
     * 2.消息服务
     * @access public
     * @param Request $request
     * @return void
     */
    public function pub(Request $request)
    {
        // 用于查看订阅与发布系统状态，它由数个不同格式的子命令组成, 查看频道
        $pubsub = Redis::pubsub('channels');
        // 用于将信息发送到指定的频道 返回值：接收到信息的订阅者数量
        $publish = Redis::publish($request->channels, $request->message);
        return success(['success_num' => $publish]);
    }

    /**
     * 查看Console\Commands
     * 遗留问题：在闭包里面使用不了 redis的任何操作
     * 问题解决：除了 SUBSCRIBE、PSUBSCRIBE、UNSUBSCRIBE、PUNSUBSCRIBE 这 4 条命令以外，其它命令都不能使用。
     * 发布订阅模式 的使用
     * 使用场景：
     * 1.构建实时消息系统，比如普通的即时聊天，群聊等功能
     * 2.消息服务
     * @access public
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub()
    {
        // 订阅一个或多个符合给定模式的频道
//        $psubscribe = Redis::psubscribe('runoobChat', function ($redis, $channelName, $message) {
//            $redis->hset('pubsubhash', $message, date('Y-m-d H:i:s'));
//        });
        // 退订所有给定模式的频道
//        $punsubscribe = Redis::punsubscribe(['runoobChat']);
        // 用于订阅给定的一个或多个频道的信息
//        $subscribe = Redis::subscribe(['runoobChat'], function ($message, $channel) {
//
//        });
        // 用于退订给定的一个或多个频道的信息
//        $unsubscribe = Redis::unsubscribe(['runoobChat']);
        return success();
    }

    /**
     * 事务 的使用
     * 使用场景：
     * 1.秒杀系统
     * 2.支付系统
     * @access public
     * @return bool
     */
    public function transactions()
    {
//        dd(Redis::get('key2'));
        // 监视一个(或多个)key，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断
        $watch = Redis::watch(['key1', 'key2']);
        // 用于监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断 模拟监视 key 被打断
        $data = Redis::set('key1', 'valuehh');
        // 标记一个事务块的开始。
        $multi = Redis::transaction(function () {
            $data = Redis::set('key1', 'valuegg');
            Redis::set('key2', 'value2');
        });
        // 用于取消 WATCH 命令对所有 key 的监视。
        $unwatch = Redis::unwatch();
        return true;
    }

    /**
     * 脚本 的使用
     * 使用场景：
     * @access public
     * @return void
     */
    public function eval()
    {

    }
}
