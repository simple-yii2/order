$('#deliveryform-method :radio').on('change', function(e) {
    var $radio = $(this),
        fields = $radio.closest('#deliveryform-method').data('fields')[$radio.val()],
        $form = $radio.closest('form');

    var filter = []
    $.each(fields, function (k, v) {
        filter.push('.field-' + v);
    });

    $form.find('.form-group').not(':first, :last').addClass('hidden').filter(filter.join(',')).removeClass('hidden');

    $('.order-total-delivery-name').text($.trim($radio.parent().text()));

    calcDelivery($form);
});

$('#order-delivery-form select, #order-delivery-form input, #order-delivery-form textarea').on('change', function() {
    calcDelivery($(this).closest('form'));
});

function calcDelivery($form) {
    $.post($form.find('#deliveryform-method').data('urlCalc'), $form.serialize(), function(data) {
        $form.find('.order-delivery-amount').html(data);
    }, 'json');
};
