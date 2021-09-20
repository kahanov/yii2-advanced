$(document).ready(function () {
    $('.phone_button__container').mouseover(function () {
        $(this).find('.phone_button__phone').hide();
        $(this).find('.phone_button__text').show();
    }).mouseout(function () {
        $(this).find('.phone_button__text').hide();
        $(this).find('.phone_button__phone').show();
    });

    $(document).on('click', '.phone_button__button_component', function (e) {
        e.preventDefault();
        $(this).find('.phone_button__preloader').show();
        var adId = $(this).data('ad_id');
        if (adId) {
            getPhone('/ad/default/view-stat', adId, 'phone', this);
        } else {
            var id = $(this).data('id'),
                type = $(this).data('type');
            getPhone('/helper/get-phone', id, type, this);
        }
        return false;
    });

    function getPhone(url, id, type, button) {
        $.post(url, {
            id: id,
            type: type
        }).done(function (data) {
            $(button).find('.phone_button__preloader').hide();
            if (data.phone) {
                var html = '<div class="phone_button__number_text"><a href="tel:' + data.phone + '" itemprop="telephone">' + data.phone + '</a></div>';
                $(button).parent().html(html);
            }
        });
    }
});
