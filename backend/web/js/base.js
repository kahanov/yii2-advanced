jQuery(function () {
    $('a[data-id="translit"]').click(function (e) {
        e.preventDefault();
        var inputTextId = $(this).data('input-text-id'),
            inputSlugId = $(this).data('input-slug-id'),
            string = $('input[id="' + inputTextId + '"]').val(),
            data = {
                'string': string,
            },
            url = '/helper/translit';

        if (string === '') {
            alert('Нет текста в основном поле');
            return false;
        }

        $.post(url, data, function (response) {
            try {
                $('input[id="' + inputSlugId + '"]').val(response);
            } catch (e) {
                console.log('Error: ' + e.message);
            }
        });

        return false;
    });
});
