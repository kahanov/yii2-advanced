$(function () {
    $(document).on('click', '.info-message-button', function () {
        $('.company__info-message').hide();
        $('.company_message').show();
    });

    $(document).on('click', '.company_message__close', function () {
        $('.company__info-message').show();
        $('.company_message').hide();
    });

    $('#company-message-form').on('beforeSubmit', function () {
        var form = $(this);
        $.post({
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: function () {
                $('.company_message__error').hide();
                $('.company-send-message').addClass('company-send-message--load');
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    $('.company_message__form-block').remove();
                    $('.company_message__suc-block').show();
                    return true;
                } else {
                    $('.company_message__error').show();
                    $('.company-send-message').removeClass('company-send-message--load');
                    return false;
                }
            }
        }).fail(function (response) {
            $('.company_message__error').show();
            $('.company-send-message').removeClass('company-send-message--load');
            return false;
        });
        return false;
    });
});
