<?php
require_once __DIR__ . '/../src/init.php';
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $rows = $stmt->fetchAll();
    foreach($rows as $r) {
        $qty = $cart[$r['id']];
        $sub = $qty * $r['price'];
        $total += $sub;
        $items[] = ['product'=>$r,'qty'=>$qty,'sub'=>$sub];
    }
}
?>
<!doctype html><html lang="fa"><head><meta charset="utf-8"><title>سبد خرید</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include __DIR__.'/header.php'; ?>
<div class="container">
<h2>سبد خرید</h2>
<?php if(empty($items)): ?>
  <p>سبد شما خالی است.</p>
<?php else: ?>
  <table>
    <tr><th>محصول</th><th>تعداد</th><th>قیمت</th></tr>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?=htmlspecialchars($it['product']['title'])?></td>
        <td><?= $it['qty'] ?></td>
        <td><?= number_format($it['sub']) ?> تومان</td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p>مجموع: <?= number_format($total) ?> تومان</p>
  <form method="post" action="/controllers/checkout_action.php">
    <input type="hidden" name="csrf" value="<?=csrf_token()?>">
    <button>پرداخت با کارت اختصاصی</button>
  </form>
<?php endif; ?>
</div>
<?php include __DIR__.'/footer.php'; ?></body></html>
admin_panel.php