<?php
require_once __DIR__ . '/env.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $host = env('DB_HOST', '127.0.0.1');
    $name = env('DB_NAME', 'foro_b1');
    $user = env('DB_USER', 'foro');
    $pass = env('DB_PASS', '');
    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
