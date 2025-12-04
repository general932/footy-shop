<?php
// src/init.php
session_start();

// تنظیمات دیتابیس — این رو با مقدار واقعی تغییر بده
$db_host = '127.0.0.1';
$db_name = 'feet_shop';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",$db_user,$db_pass,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Database connection failed.');
}

/* توابع کمکی */
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}
function current_user_role() {
    return $_SESSION['user_role'] ?? 'user';
}
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function check_csrf($t) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $t);
}
