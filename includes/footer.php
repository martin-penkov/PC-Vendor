    </main>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Компютърни части</p>
        </div>
    </footer>
    <?php
    // Определяне на базовия път според това дали сме в поддиректория
    $basePath = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/api/') !== false) ? '../' : '';
    ?>
    <script src="<?php echo $basePath; ?>assets/js/main.js"></script>
</body>
</html>

