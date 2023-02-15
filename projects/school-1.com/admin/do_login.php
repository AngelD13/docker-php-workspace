<?php
session_start();
//require_once 'admin/Velobd.php';
//require_once 'admin/velofu.php';
$dbinfo = require_once 'config.php';

// проверяем наличие пользователя с указанным юзернеймом

$pdo = new PDO('mysql:host='.$dbinfo['host'].';port='.$dbinfo['port'].';dbname='.$dbinfo['dbname'].'',
$dbinfo['login'], $dbinfo['password']);
$pdo = $pdo->prepare("SELECT * FROM `users` WHERE `username` = :username");
$pdo->execute(['username' => $_POST['username']]);
if (!$pdo->rowCount()) {
    flash('Пользователь с такими данными не зарегистрирован');
    header('Location: login.php');
    die;
}
$user = $pdo->fetch(PDO::FETCH_ASSOC);

// проверяем пароль
if (password_verify($_POST['password'], $user['password'])) {
    // Проверяем, не нужно ли использовать более новый алгоритм
    // или другую алгоритмическую стоимость
    // Например, если вы поменяете опции хеширования
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pdo = $pdo->prepare('UPDATE `users` SET `password` = :password WHERE `username` = :username');
        $pdo->execute([
            'username' => $_POST['username'],
            'password' => $newHash,
        ]);
    }
    $_SESSION['user_id'] = $user['id'];
    header('Location: /veload.php');
    die;
}

flash('Пароль неверен');
header('Location: login.php');