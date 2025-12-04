<?php
require_once __DIR__ . '/../src/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = intval($_POST['product_id']);
    $qty = max(1,intval($_POST['qty'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] = 0;
    $_SESSION['cart'][$pid] += $qty;
    header('Location: /public/cart.php');
    exit;
}
