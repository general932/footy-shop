<?php
require_once __DIR__.'/../src/init.php';
require_once __DIR__.'/../src/auth.php';
require_admin();
?>
<?php include 'header.php'; ?>
<div class="container">
  <h2>پنل ادمین</h2>
  <section>
    <h3>آپلود محصول</h3>
    <form method="post" action="/controllers/upload_product.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <input name="title" placeholder="عنوان"><br>
      <textarea name="description" placeholder="توضیح"></textarea><br>
      <input name="price" placeholder="قیمت"><br>
      <input type="file" name="image"><br>
      <input name="stock" placeholder="موجودی"><br>
      <button>آپلود</button>
    </form>
  </section>
  <section>
    <h3>شارژ/برداشت از کارت کاربر</h3>
    <!-- این بخش رو مطابق نیاز گسترش بده -->
  </section>
</div>
<?php include 'footer.php'; ?>
