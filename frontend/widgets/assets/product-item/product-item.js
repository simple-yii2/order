$(document).on('click', 'a.product-cart-add', function(e) {
    e.preventDefault();
    var $button = $(this);
    if ($button.hasClass('disabled')) {
        return; 
    };
    $button.addClass('disabled');
	cartAdd($button.data('id'));
});

$(document).on('cart.add', function(e, cart) {
    $('.product-cart-add[data-id="' + cart.product_id + '"]').addClass('hidden');
    $('.product-cart[data-id="' + cart.product_id + '"]').removeClass('hidden');
});
