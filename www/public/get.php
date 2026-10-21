<?php
require_once __DIR__ . '/../init.php';

$twig = getTwig();
$manager = getMongoDbManager();

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

// 获取 URL 传过来的 id，必须转换为 MongoDB 的 ObjectId
$id = $_GET['id'] ?? null;
$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

echo $twig->render('get.html.twig', [
    'document' => $document
]);