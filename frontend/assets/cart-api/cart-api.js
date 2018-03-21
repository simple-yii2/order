function cartAdd(id) {
    $.get(cartUrls[0], {'id': id}, function(data) {
    	$(document).trigger('cart.add', [data]);
    }, 'json');
};

function cartRemove(id) {
    $.get(cartUrls[1], {'id': id}, function(data) {
    	$(document).trigger('cart.remove', [data]);
    }, 'json');
};

function cartCount(id, count) {
    $.get(cartUrls[2], {'id': id, 'count': count}, function(data) {
    	$(document).trigger('cart.count', [data]);
    }, 'json');
};
