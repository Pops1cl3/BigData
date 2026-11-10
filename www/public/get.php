<?php
require_once __DIR__ . '/../init.php';

$twig = getTwig();
$manager = getMongoDbManager();

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

$id = $_GET['id'] ?? null;
$document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

echo $twig->render('get.html.twig', [
    'document' => $document
]);