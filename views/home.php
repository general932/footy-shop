<?php
require_once __DIR__ . '/../src/init.php';
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 20");
$products = $stmt->fetchAll();
include __DIR__.'/../views/header.php';
include __DIR__.'/../views/home.php';
include __DIR__.'/../views/footer.php';
