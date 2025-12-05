    </main>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Компютърни части</p>
        </div>
    </footer>
    <?php
    // Detect if running on Vercel or locally
    $isVercel = isset($_SERVER['VERCEL']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'vercel.app') !== false;
    $assetPath = $isVercel ? '/' : '';
    ?>
    <script src="<?php echo $assetPath; ?>assets/js/main.js"></script>
</body>
</html>

