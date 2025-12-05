<?php
session_start();
$basePath = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/api/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компютърни части - <?php echo isset($pageTitle) ? $pageTitle : 'Магазин'; ?></title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
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
                    <li><a href="/">Начало</a></li>
                    <li><a href="/products">Продукти</a></li>
                    <li><a href="/add-product">Добави продукт</a></li>
                    <li><a href="/search">Търсене</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">

