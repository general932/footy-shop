<?php
// controllers/upload_product.php
require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/watermark.php';

if (!is_logged_in() || current_user_role() !== 'admin') {
    http_response_code(403); exit('forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'])) die('invalid csrf');

    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $stock = intval($_POST['stock']);

    if (empty($title) || empty($_FILES['image'])) {
        die('missing fields');
    }

    // validation
    $allowed = ['image/jpeg','image/png'];
    if (!in_array($_FILES['image']['type'],$allowed)) die('invalid image type');
    if ($_FILES['image']['size'] > 5*1024*1024) die('file too large');

    // save original to uploads_private
    $privateDir = __DIR__ . '/../uploads_private/';
    if (!is_dir($privateDir)) mkdir($privateDir,0755,true);
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $privatePath = $privateDir . $basename . '.' . $ext;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $privatePath)) die('upload failed');

    // create watermarked version into public/uploads_public/
    $publicDir = __DIR__ . '/../public/uploads_public/';
    if (!is_dir($publicDir)) mkdir($publicDir,0755,true);
    $publicFilename = $basename . '.jpg';
    $publicPath = $publicDir . $publicFilename;
    save_uploaded_image_with_watermark($privatePath, $publicPath, 'feet-shop');

    // insert to DB (store public filename)
    $stmt = $pdo->prepare("INSERT INTO products (title,description,price,image_path,stock,category) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$title,$desc,$price,$publicFilename,$stock,$category]);

    header('Location: /public/index.php');
    exit;
}
