<?php
require_once __DIR__ . '/../init.php';

$twig = getTwig();
$manager = getMongoDbManager();

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

$id = $_GET['id'] ?? null;
$objectId = new MongoDB\BSON\ObjectId($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->updateOne(
        ['_id' => $objectId],
        ['$set' => [
            'titre' => $_POST['titre'],
            'auteur' => $_POST['auteur']
        ]]
    );

    $redis = getRedisClient();
    $redis->del('manuscrits_homepage_list');

    header("Location: app.php");
    exit;
}

$document = $collection->findOne(['_id' => $objectId]);
echo $twig->render('edit.html.twig', [
    'document' => $document
]);