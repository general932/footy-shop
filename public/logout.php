<?php
require_once __DIR__ . '/../src/init.php';
session_destroy();
header('Location: /public/index.php');
