<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// petite aide : https://github.com/VSG24/mongodb-php-examples

$db = getMongoDbManager();
$collection = $db->selectCollection('tp');

// 如果是表单提交 (POST 请求)，则存入数据库
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->insertOne([
        'titre' => $_POST['titre'],
        'auteur' => $_POST['auteur']
    ]);
    header("Location: app.php");
    exit;
}
// 如果是普通访问 (GET 请求)，则显示空白表单
echo $twig->render('create.html.twig');

