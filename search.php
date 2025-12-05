<?php
$pageTitle = 'Търсене';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Търсене на продукти</h1>
    </div>

    <div class="search-section">
        <div class="search-form-container">
            <input type="text" id="searchQuery" class="search-input" placeholder="Въведете ключова дума за търсене...">
            <button id="searchButton" class="btn btn-primary">Търси</button>
        </div>

        <div class="advanced-filters">
            <h3>Разширени филтри</h3>
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="filterCategory">Категория</label>
                    <select id="filterCategory" class="filter-select">
                        <option value="">Всички категории</option>
                        <option value="Процесор">Процесор</option>
                        <option value="Видео карта">Видео карта</option>
                        <option value="Памет">Памет</option>
                        <option value="Твърд диск">Твърд диск</option>
                        <option value="Дънна платка">Дънна платка</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="minPrice">Минимална цена (лв.)</label>
                    <input type="number" id="minPrice" class="filter-input" step="0.01" min="0">
                </div>
                <div class="filter-group">
                    <label for="maxPrice">Максимална цена (лв.)</label>
                    <input type="number" id="maxPrice" class="filter-input" step="0.01" min="0">
                </div>
                <div class="filter-group">
                    <label for="minStock">Минимална наличност</label>
                    <input type="number" id="minStock" class="filter-input" min="0">
                </div>
            </div>
            <button id="applyFilters" class="btn btn-secondary">Приложи филтри</button>
        </div>
    </div>

    <div id="searchResults" class="products-grid">
    
    </div>

    <div id="loadingMessage" class="loading-message">Зареждане...</div>
    <div id="noResults" class="no-results" style="display: none;">
        <p>Няма намерени продукти по зададените критерии.</p>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#searchButton').click(function() {
        performSearch();
    });

    $('#searchQuery').keypress(function(e) {
        if (e.which == 13) {
            performSearch();
        }
    });

    $('#applyFilters').click(function() {
        performSearch();
    });

    loadAllProducts();
});

function performSearch() {
    const query = $('#searchQuery').val().trim();
    const category = $('#filterCategory').val();
    const minPrice = $('#minPrice').val();
    const maxPrice = $('#maxPrice').val();
    const minStock = $('#minStock').val();

    $('#loadingMessage').show();
    $('#noResults').hide();
    $('#searchResults').empty();

    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        data: {
            search: query,
            category: category,
            min_price: minPrice,
            max_price: maxPrice,
            min_stock: minStock
        },
        dataType: 'json',
        success: function(response) {
            $('#loadingMessage').hide();
            if (response.success) {
                if (response.data.length > 0) {
                    displayProducts(response.data, '#searchResults');
                } else {
                    $('#noResults').show();
                }
            } else {
                $('#searchResults').html('<p class="error">Грешка: ' + (response.message || 'Неизвестна грешка') + '</p>');
            }
        },
        error: function() {
            $('#loadingMessage').hide();
            $('#searchResults').html('<p class="error">Грешка при търсенето.</p>');
        }
    });
}

function loadAllProducts() {
    $('#loadingMessage').show();
    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#loadingMessage').hide();
            if (response.success) {
                displayProducts(response.data, '#searchResults');
            }
        },
        error: function() {
            $('#loadingMessage').hide();
            $('#searchResults').html('<p class="error">Грешка при зареждане на продуктите.</p>');
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
                    ${product.image ? `<img src="assets/images/${product.image}" alt="${product.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"><div class="no-image" style="display:none;">Няма снимка</div>` : '<div class="no-image">Няма снимка</div>'}
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
                        <a href="edit_product.php?id=${product.id}" class="btn btn-small btn-primary">Редактирай</a>
                    </div>
                </div>
            </div>
        `;
    });

    $(container).html(html);
}
</script>

<?php include 'includes/footer.php'; ?>

