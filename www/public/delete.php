<?php
require_once __DIR__ . '/../init.php';

$twig = getTwig();
$manager = getMongoDbManager();

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

$id = $_GET['id'] ?? null;
if ($id) {
    // 执行删除操作
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

    $redis = getRedisClient();
    $redis->del('manuscrits_homepage_list');
}

// 删除完毕后，重定向回首页
header("Location: app.php");
exit;