<?php
// public/image.php
// param: file=filename.jpg
$fn = $_GET['file'] ?? '';
$fn = basename($fn); // sanitize
$path = __DIR__ . '/uploads_public/' . $fn;
if (!file_exists($path)) {
    http_response_code(404); exit;
}
$info = getimagesize($path);
header('Content-Type: ' . $info['mime']);
header('Cache-Control: public, max-age=86400');
readfile($path);
exit;
