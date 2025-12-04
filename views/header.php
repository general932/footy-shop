<!doctype html><html lang="fa"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Feet Shop</title>
<link rel="stylesheet" href="/assets/css/style.css"></head><body>
<header class="main-header">
  <div class="logo">Feet Shop</div>
  <nav>
    <a href="/public/index.php">خانه</a> |
    <a href="/public/cart.php">سبد خرید (<?= count($_SESSION['cart'] ?? []) ?>)</a>
    <?php if(is_logged_in()): ?>
      | <a href="/public/profile.php">پروفایل</a>
      <?php if(current_user_role()==='admin'): ?> | <a href="/public/admin.php">ادمین</a><?php endif; ?>
      | <a href="/public/logout.php">خروج</a>
    <?php else: ?>
      | <a href="/public/login.php">ورود</a> | <a href="/public/register.php">ثبت‌نام</a>
    <?php endif; ?>
  </nav>
</header>
