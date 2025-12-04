<?php
require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'])) die('invalid csrf');
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if (empty($email) || empty($pass)) die('missing');
    $id = register_user($pdo,$name,$email,$pass);
    $_SESSION['user_id'] = $id;
    header('Location: /public/index.php');
    exit;
}
