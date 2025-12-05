<?php
session_start();
// Detect if running on Vercel or locally
$isVercel = isset($_SERVER['VERCEL']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'vercel.app') !== false;
$basePath = $isVercel ? '' : '';
$assetPath = $isVercel ? '/' : '';
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компютърни части - <?php echo isset($pageTitle) ? $pageTitle : 'Магазин'; ?></title>
    <link rel="stylesheet" href="<?php echo $assetPath; ?>assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <h1>🖥️ Компютърни части</h1>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo $isVercel ? '/' : 'index.php'; ?>">Начало</a></li>
                    <li><a href="<?php echo $isVercel ? '/products' : 'products.php'; ?>">Продукти</a></li>
                    <li><a href="<?php echo $isVercel ? '/add-product' : 'add_product.php'; ?>">Добави продукт</a></li>
                    <li><a href="<?php echo $isVercel ? '/search' : 'search.php'; ?>">Търсене</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">

