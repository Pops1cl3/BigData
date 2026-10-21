<?php
require_once __DIR__ . '/../init.php';

$twig = getTwig();
$manager = getMongoDbManager();

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

$id = $_GET['id'] ?? null;
$objectId = new MongoDB\BSON\ObjectId($id);

// 处理修改后的表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->updateOne(
        ['_id' => $objectId],
        ['$set' => [
            'titre' => $_POST['titre'],
            'auteur' => $_POST['auteur']
        ]]
    );
    header("Location: app.php");
    exit;
}

// 查出当前数据供表单默认显示
$document = $collection->findOne(['_id' => $objectId]);
echo $twig->render('edit.html.twig', [
    'document' => $document
]);