<?php
require_once __DIR__ . '/../src/init.php';
if (!is_logged_in()) { header('Location: /public/login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /public/cart.php'); exit; }
if (!check_csrf($_POST['csrf'])) die('invalid csrf');

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: /public/cart.php'); exit; }

// calculate total
$ids = implode(',', array_map('intval', array_keys($cart)));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$rows = $stmt->fetchAll();
$total = 0;
foreach($rows as $r) {
    $total += $r['price'] * $cart[$r['id']];
}

// check balance
$stmt = $pdo->prepare("SELECT card_balance FROM users WHERE id=?");
$stmt->execute([current_user_id()]);
$user = $stmt->fetch();
if (!$user || $user['card_balance'] < $total) {
    die('موجودی کافی نیست');
}

$pdo->beginTransaction();
try {
    $pdo->prepare("INSERT INTO orders (user_id,total) VALUES (?,?)")->execute([current_user_id(), $total]);
    $orderId = $pdo->lastInsertId();
    foreach($rows as $r) {
        $qty = $cart[$r['id']];
        $pdo->prepare("INSERT INTO order_items (order_id,product_id,qty,price) VALUES (?,?,?,?)")
            ->execute([$orderId, $r['id'], $qty, $r['price']]);
    }
    // update balance
    $pdo->prepare("UPDATE users SET card_balance = card_balance - ? WHERE id = ?")->execute([$total, current_user_id()]);
    // transaction log
    $pdo->prepare("INSERT INTO transactions (user_id,amount,type,note) VALUES (?,?, 'purchase',?)")
        ->execute([current_user_id(), $total, 'purchase order '.$orderId]);
    $pdo->commit();
    unset($_SESSION['cart']);
    header('Location: /public/index.php?paid=1');
} catch (Exception $e) {
    $pdo->rollBack();
    die('خطا در پرداخت: '.$e->getMessage());
}
