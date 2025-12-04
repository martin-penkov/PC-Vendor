// Глобален JavaScript файл

$(document).ready(function() {
    // Анимации и интерактивност
    initializeAnimations();
    
    // Общи функционалности
    initializeCommonFeatures();
});

// Инициализиране на анимации
function initializeAnimations() {
    // Анимация при навеждане на мишка върху карти с продукти
    $('.product-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );
    
    // Анимация при кликване на бутони
    $('.btn').on('click', function() {
        $(this).addClass('clicked');
        setTimeout(() => {
            $(this).removeClass('clicked');
        }, 200);
    });
}

// Общи функционалности
function initializeCommonFeatures() {
    // Автоматично скриване на съобщения след 5 секунди
    setTimeout(function() {
        $('.form-message').fadeOut();
    }, 5000);
    
    // Потвърждение при изтриване
    $('.delete-btn').on('click', function(e) {
        if (!confirm('Сигурни ли сте, че искате да изтриете този запис?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Валидация на числови полета
    $('input[type="number"]').on('input', function() {
        const value = parseFloat($(this).val());
        if (value < 0) {
            $(this).val(0);
        }
    });
}

// Функция за показване на toast съобщения
function showToast(message, type = 'info') {
    const toast = $('<div class="toast toast-' + type + '">' + message + '</div>');
    $('body').append(toast);
    
    setTimeout(function() {
        toast.addClass('show');
    }, 100);
    
    setTimeout(function() {
        toast.removeClass('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

// Функция за форматиране на цена
function formatPrice(price) {
    return parseFloat(price).toFixed(2) + ' лв.';
}

// Функция за форматиране на дата
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
}

