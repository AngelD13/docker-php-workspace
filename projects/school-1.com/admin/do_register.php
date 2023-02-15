<?php
session_start();
require_once 'Velobd.php';
require_once 'velofu.php';
$dbinfo = require_once 'config.php';

// Проверим, не занято ли имя пользователя
$pdo = new PDO('mysql:host='.$dbinfo['host'].';port='.$dbinfo['port'].';dbname='.$dbinfo['dbname'].'',
$dbinfo['login'], $dbinfo['password']);
var_dump($pdo);
$sql='SELECT * FROM `users` WHERE `username` = :username';
$stmt = $pdo->prepare($sql);
var_dump($pdo);
$stmt->execute(['username' => $_POST['username']]);
if ($stmt->rowCount() > 0) {
    flash('Это имя пользователя уже занято.');
    header('Location: /'); // Возврат на форму регистрации
    die; // Остановка выполнения скрипта
}

// Добавим пользователя в базу
$stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)");
$stmt->execute([
    'username' => $_POST['username'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
]);

header('Location: login.php');