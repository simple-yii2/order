$('.product-cart-remove').on('click', function(e) {
    e.preventDefault();

    var $button = $(this);
    if ($button.hasClass('disabled')) {
        return;
    };
    $button.addClass('disabled');

    cartRemove($button.closest('.cart-item').data('key'));
});

$('.product-cart-count-dec').on('click', function(e) {
    updateCartCount($(this).closest('.cart-item'), -1);
});

$('.product-cart-count-inc').on('click', function(e) {
    updateCartCount($(this).closest('.cart-item'), 1);
});

$('.cart-item-count input').on('blur', function(e) {
    updateCartCount($(this).closest('.cart-item'), 0);
});

$('.cart-item-count input').on('keypress', function(e) {
    if (e.which == '13') {
        updateCartCount($(this).closest('.cart-item'), 0);
    }
});

function updateCartCount($item, d) {
    var $controls = $item.find('.cart-item-count button, .cart-item-count input'), $input = $controls.filter('input'),
        count = parseInt($input.val());

    if (isNaN(count)) {
        $input.val($input.data('value'));
        return;
    }

    count += d;

    if (count < 1) count = 1;
    if (count > 999) count = 999;
    var quantity = $input.data('quantity');
    if (quantity && (count > quantity)) count = quantity;

    $controls.prop('disabled', true);

    cartCount($item.data('key'), count);
};

$(document).on('cart.remove', function(e, cart) {
    $('.cart-item[data-key="' + cart.product_id + '"]').animate({'opacity': 0}, 200, function() {
        $(this).remove();
    });
});

$(document).on('cart.count', function(e, cart) {
    var $item = $('.cart-item[data-key="' + cart.product_id + '"]');
    $.each(cart.items, function(k, v) {
        if (v.id == cart.product_id) {
            $item.find('.cart-item-total-amount').html(v.totalAmount);
            if (v.count > 1) $item.find('.product-cart-count-dec').prop('disabled', false);
            if (v.quantity == 0 || v.count < v.quantity) $item.find('.product-cart-count-inc').prop('disabled', false);
            $item.find('.cart-item-count input').val(v.count).data('value', v.count).prop('disabled', false);
            return false;
        }
    });
});

$(document).on('cart.remove cart.count', function(e, cart) {
    $('.cart-list-total-amount').html(cart.totalAmount);
});