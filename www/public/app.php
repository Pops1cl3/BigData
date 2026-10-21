<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// @todo implementez la récupération des données dans la variable $list
// petite aide : https://github.com/VSG24/mongodb-php-examples
$db = getMongoDbManager();
$collection = $db->selectCollection('tp'); // 选择 tp 集合
$manuscrits = $collection->find([], ['limit' => 200]); // 建议加个 limit 限制数量，防止数据太多卡死网页

// 将查询结果传递给 Twig
/*echo $twig->render('index.html.twig', [
    'manuscrits' => $manuscrits
]);*/

//$list = [['name' => 'test']];

// render template
try {
    echo $twig->render('index.html.twig', [
        'manuscrits' => $manuscrits
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}



