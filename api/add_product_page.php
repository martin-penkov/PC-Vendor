<?php
$pageTitle = 'Добави продукт';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Добави нов продукт</h1>
        <a href="/products" class="btn btn-secondary">← Назад към продуктите</a>
    </div>

    <form id="addProductForm" class="product-form">
        <div class="form-group">
            <label for="name">Име на продукта *</label>
            <input type="text" id="name" name="name" required>
            <span class="error-message" id="nameError"></span>
        </div>

        <div class="form-group">
            <label for="category">Категория *</label>
            <select id="category" name="category" required>
                <option value="">Избери категория</option>
                <option value="Процесор">Процесор</option>
                <option value="Видео карта">Видео карта</option>
                <option value="Памет">Памет</option>
                <option value="Твърд диск">Твърд диск</option>
                <option value="Дънна платка">Дънна платка</option>
                <option value="Захранване">Захранване</option>
                <option value="Кутия">Кутия</option>
            </select>
            <span class="error-message" id="categoryError"></span>
        </div>

        <div class="form-group">
            <label for="price">Цена (лв.) *</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>
            <span class="error-message" id="priceError"></span>
        </div>

        <div class="form-group">
            <label for="stock">Наличност (бройки) *</label>
            <input type="number" id="stock" name="stock" min="0" required>
            <span class="error-message" id="stockError"></span>
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Име на снимката</label>
            <input type="text" id="image" name="image" placeholder="например: product_image.jpg">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Запази продукт</button>
            <button type="reset" class="btn btn-secondary">Изчисти</button>
        </div>

        <div id="formMessage" class="form-message"></div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        clearErrors();
        clearMessage();

        if (!validateForm()) {
            return;
        }

        const formData = {
            name: $('#name').val(),
            category: $('#category').val(),
            price: $('#price').val(),
            stock: $('#stock').val(),
            description: $('#description').val(),
            image: $('#image').val()
        };

        $.ajax({
            url: '/api/add-product',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('Продуктът е добавен успешно!', 'success');
                    $('#addProductForm')[0].reset();
                    setTimeout(function() {
                        window.location.href = '/products';
                    }, 1500);
                } else {
                    showMessage(response.message || 'Грешка при добавяне на продукта.', 'error');
                    if (response.errors) {
                        displayErrors(response.errors);
                    }
                }
            },
            error: function() {
                showMessage('Грешка при добавяне на продукта.', 'error');
            }
        });
    });
});

function validateForm() {
    let isValid = true;

    if ($('#name').val().trim() === '') {
        showError('name', 'Името на продукта е задължително.');
        isValid = false;
    }

    if ($('#category').val() === '') {
        showError('category', 'Категорията е задължителна.');
        isValid = false;
    }

    const price = parseFloat($('#price').val());
    if (isNaN(price) || price <= 0) {
        showError('price', 'Цената трябва да е положително число.');
        isValid = false;
    }

    const stock = parseInt($('#stock').val());
    if (isNaN(stock) || stock < 0) {
        showError('stock', 'Наличността трябва да е неотрицателно число.');
        isValid = false;
    }

    return isValid;
}

function showError(field, message) {
    $('#' + field + 'Error').text(message).show();
    $('#' + field).addClass('error');
}

function clearErrors() {
    $('.error-message').text('').hide();
    $('.form-group input, .form-group select, .form-group textarea').removeClass('error');
}

function displayErrors(errors) {
    for (let field in errors) {
        showError(field, errors[field]);
    }
}

function showMessage(message, type) {
    const messageDiv = $('#formMessage');
    messageDiv.removeClass('success error').addClass(type).text(message).show();
}

function clearMessage() {
    $('#formMessage').text('').hide();
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
