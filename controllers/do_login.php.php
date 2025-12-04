<?php
require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'])) die('invalid csrf');
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if (login_user($pdo,$email,$pass)) {
        header('Location: /public/index.php');
    } else {
        header('Location: /public/login.php?error=1');
    }
    exit;
}
