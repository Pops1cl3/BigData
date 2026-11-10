<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();

$redis = getRedisClient();
$cacheEnabled = filter_var($_ENV['REDIS_ENABLE'] ?? false, FILTER_VALIDATE_BOOLEAN);
$cacheKey = 'manuscrits_homepage_list';

if ($cacheEnabled && $redis->exists($cacheKey)) {
    $manuscrits = json_decode($redis->get($cacheKey), true);
} else {
    $db = getMongoDbManager();
    $collection = $db->selectCollection('tp');
    
    $cursor = $collection->find([], ['limit' => 200]);
    
    $manuscrits = [];
    foreach ($cursor as $doc) {
        $arr = (array) $doc;
        if (isset($arr['_id'])) {
            $arr['_id'] = (string) $arr['_id'];
        }
        $manuscrits[] = $arr;
    }

    if ($cacheEnabled) {
        $redis->setex($cacheKey, 60, json_encode($manuscrits));
    }
}

try {
    echo $twig->render('index.html.twig', [
        'manuscrits' => $manuscrits
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
