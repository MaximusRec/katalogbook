﻿<?php 
$host= 'localhost';    $db= 'book_kat';    $user = 'root';    $pass = '';    $charset = 'utf8';
$dsn= "mysql:host={$host};dbname={$db};charset={$charset}";
$opt= [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

try {
    $pdo = new PDO($dsn, $user, $pass, $opt);
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}
?>