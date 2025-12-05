<?php
$pageTitle = 'Продукти';
include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Всички продукти</h1>
        <a href="/add-product" class="btn btn-primary">+ Добави нов продукт</a>
    </div>

    <div class="filters">
        <select id="categoryFilter" class="filter-select">
            <option value="">Всички категории</option>
            <option value="Процесор">Процесор</option>
            <option value="Видео карта">Видео карта</option>
            <option value="Памет">Памет</option>
            <option value="Твърд диск">Твърд диск</option>
            <option value="Дънна платка">Дънна платка</option>
        </select>
        <input type="text" id="searchInput" class="filter-input" placeholder="Търсене по име...">
        <button id="searchBtn" class="btn btn-secondary">Търси</button>
    </div>

    <div id="productsContainer" class="products-grid">
    
    </div>

    <div id="loadingMessage" class="loading-message">Зареждане...</div>
    <div id="errorMessage" class="error-message"></div>
</div>

<script>
$(document).ready(function() {
    loadProducts();

    $('#searchBtn').click(function() {
        loadProducts();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            loadProducts();
        }
    });

    $('#categoryFilter').change(function() {
        loadProducts();
    });
});

function loadProducts() {
    const category = $('#categoryFilter').val();
    const search = $('#searchInput').val();

    $('#loadingMessage').show();
    $('#errorMessage').hide();
    $('#productsContainer').empty();

    $.ajax({
        url: '/api/get-products',
        method: 'GET',
        data: {
            category: category,
            search: search
        },
        dataType: 'json',
        success: function(response) {
            $('#loadingMessage').hide();
            if (response.success) {
                displayProducts(response.data, '#productsContainer');
            } else {
                $('#errorMessage').text(response.message || 'Грешка при зареждане на продуктите.').show();
            }
        },
        error: function() {
            $('#loadingMessage').hide();
            $('#errorMessage').text('Грешка при зареждане на продуктите.').show();
        }
    });
}

function displayProducts(products, container) {
    if (products.length === 0) {
        $(container).html('<p class="no-results">Няма намерени продукти.</p>');
        return;
    }

    let html = '';
    products.forEach(function(product) {
        html += `
            <div class="product-card" data-id="${product.id}">
                <div class="product-image">
                    ${product.image ? `<img src="../assets/images/${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><div class="no-image" style="display:none;">Няма снимка</div>` : '<div class="no-image">Няма снимка</div>'}
                </div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="product-category">${product.category}</p>
                    <p class="product-description">${product.description ? (product.description.length > 100 ? product.description.substring(0, 100) + '...' : product.description) : ''}</p>
                    <div class="product-footer">
                        <span class="product-price">${parseFloat(product.price).toFixed(2)} лв.</span>
                        <span class="product-stock">Наличност: ${product.stock}</span>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-small btn-primary" onclick="editProduct(${product.id})">Редактирай</button>
                        <button class="btn btn-small btn-danger" onclick="deleteProduct(${product.id})">Изтрий</button>
                    </div>
                </div>
            </div>
        `;
    });

    $(container).html(html);
}

function editProduct(id) {
    window.location.href = '/edit-product?id=' + id;
}

function deleteProduct(id) {
    if (!confirm('Сигурни ли сте, че искате да изтриете този продукт?')) {
        return;
    }

    $.ajax({
        url: '/api/delete-product',
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Продуктът е изтрит успешно!');
                loadProducts();
            } else {
                alert(response.message || 'Грешка при изтриване на продукта.');
            }
        },
        error: function() {
            alert('Грешка при изтриване на продукта.');
        }
    });
}
</script>

<?php include '../includes/footer.php'; ?>

