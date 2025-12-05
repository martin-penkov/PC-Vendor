<?php
$pageTitle = 'Начало';
include '../includes/header.php';
?>

<div class="container">
    <section class="hero-section">
        <h2>Добре дошли в нашия магазин за компютърни части!</h2>
        <p>Намерете най-добрите компоненти за вашия компютър</p>
        <a href="/products" class="btn btn-primary">Виж продуктите</a>
    </section>

    <section class="features-section">
        <div class="feature-grid">
            <div class="feature-card">
                <h3>📦 Широк избор</h3>
                <p>Голям асортимент от качествени компоненти</p>
            </div>
            <div class="feature-card">
                <h3>💰 Добри цени</h3>
                <p>Конкурентни цени за всички продукти</p>
            </div>
            <div class="feature-card">
                <h3>⚡ Бърза доставка</h3>
                <p>Безпроблемна и бърза доставка</p>
            </div>
            <div class="feature-card">
                <h3>🛡️ Гаранция</h3>
                <p>Пълна гаранция на всички продукти</p>
            </div>
        </div>
    </section>

    <section class="latest-products">
        <h2>Последни продукти</h2>
        <div id="latestProducts" class="products-grid">
        
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    loadLatestProducts();
});

function loadLatestProducts() {
    $.ajax({
        url: '/api/get-products',
        method: 'GET',
        data: { limit: 4 },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayProducts(response.data, '#latestProducts');
            }
        },
        error: function() {
            $('#latestProducts').html('<p>Грешка при зареждане на продуктите.</p>');
        }
    });
}

function displayProducts(products, container) {
    if (products.length === 0) {
        $(container).html('<p class="no-results">Няма налични продукти.</p>');
        return;
    }

    let html = '';
    products.forEach(function(product) {
        html += `
            <div class="product-card" data-id="${product.id}">
                <div class="product-image">
                    ${product.image ? `<img src="/assets/images/${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><div class="no-image" style="display:none;">Няма снимка</div>` : '<div class="no-image">Няма снимка</div>'}
                </div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="product-category">${product.category}</p>
                    <p class="product-description">${product.description ? (product.description.length > 100 ? product.description.substring(0, 100) + '...' : product.description) : ''}</p>
                    <div class="product-footer">
                        <span class="product-price">${parseFloat(product.price).toFixed(2)} лв.</span>
                        <span class="product-stock">Наличност: ${product.stock}</span>
                    </div>
                </div>
            </div>
        `;
    });

    $(container).html(html);
}
</script>

<?php include '../includes/footer.php'; ?>

