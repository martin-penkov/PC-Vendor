$(document).ready(function() {
    initializeAnimations();
    
    initializeCommonFeatures();
});

function initializeAnimations() {
    $('.product-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );
    
    $('.btn').on('click', function() {
        $(this).addClass('clicked');
        setTimeout(() => {
            $(this).removeClass('clicked');
        }, 200);
    });
}

function initializeCommonFeatures() {
    setTimeout(function() {
        $('.form-message').fadeOut();
    }, 5000);
    
    $('.delete-btn').on('click', function(e) {
        if (!confirm('Сигурни ли сте, че искате да изтриете този запис?')) {
            e.preventDefault();
            return false;
        }
    });
    
    $('input[type="number"]').on('input', function() {
        const value = parseFloat($(this).val());
        if (value < 0) {
            $(this).val(0);
        }
    });
}

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

function formatPrice(price) {
    return parseFloat(price).toFixed(2) + ' лв.';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
}

