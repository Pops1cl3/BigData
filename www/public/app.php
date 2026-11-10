<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();

// 1. 初始化 Redis 客户端，并获取 .env 中的缓存开关配置
$redis = getRedisClient();
$cacheEnabled = filter_var($_ENV['REDIS_ENABLE'] ?? false, FILTER_VALIDATE_BOOLEAN);
$cacheKey = 'manuscrits_homepage_list';

// 2. 缓存核心逻辑判断
if ($cacheEnabled && $redis->exists($cacheKey)) {
    // 【走缓存】如果开启了缓存，且 Redis 里已有数据，直接读取并解码为数组
    $manuscrits = json_decode($redis->get($cacheKey), true);
} else {
    // 【查数据库】如果没开缓存，或者缓存里没数据，就去查 MongoDB
    $db = getMongoDbManager();
    $collection = $db->selectCollection('tp');
    
    // 查询最多 200 条数据
    $cursor = $collection->find([], ['limit' => 200]);
    
    $manuscrits = [];
    foreach ($cursor as $doc) {
        // 将 MongoDB 文档对象转为标准数组
        $arr = (array) $doc;
        // 特殊处理：将 MongoDB 独有的 ObjectId 对象强制转为普通字符串
        // 这样既能被 json_encode 存入 Redis，又能在 Twig 模板里正常显示
        if (isset($arr['_id'])) {
            $arr['_id'] = (string) $arr['_id'];
        }
        $manuscrits[] = $arr;
    }

    // 查完 MongoDB 后，如果缓存开关是开的，将数据打包成 JSON 存入 Redis
    // setex 意思是存入数据的同时设置过期时间（这里设置为 60 秒）
    if ($cacheEnabled) {
        $redis->setex($cacheKey, 60, json_encode($manuscrits));
    }
}

// 3. 渲染模板（保持不变）
try {
    echo $twig->render('index.html.twig', [
        'manuscrits' => $manuscrits
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
