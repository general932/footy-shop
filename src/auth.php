<?php
// src/auth.php
require_once __DIR__ . '/init.php';

function generate_card_number($pdo) {
    do {
        $card = 'FS'.strtoupper(bin2hex(random_bytes(6)));
        $stmt = $pdo->prepare("SELECT id FROM users WHERE card_number = ?");
        $stmt->execute([$card]);
    } while ($stmt->fetch());
    return $card;
}

function register_user($pdo, $name, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $card = generate_card_number($pdo);
    $stmt = $pdo->prepare("INSERT INTO users (name,email,password,card_number,card_balance) VALUES (?, ?, ?, ?, 0)");
    $stmt->execute([$name,$email,$hash,$card]);
    return $pdo->lastInsertId();
}

function login_user($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        session_regenerate_id(true);
        return true;
    }
    return false;
}

function require_admin() {
    if (current_user_role() !== 'admin') {
        header('Location: /public/login.php'); exit;
    }
}

