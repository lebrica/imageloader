$('form').submit(function(event) {
    event.preventDefault();
    var form = $(this);
    var url = $('#url');

    form.find('.error').remove();

    function checkURL(url) {
        return(url.match(/\.(jpeg|jpg|png)$/) !== null);
    }

    if (url.val() === '') {
        url.before('<div class="error">Введите ссылку на изображение</div>')
    }

    if (checkURL(url.val()) === false) {
        url.before('<div class="error">Ваша ссылка не рабочая</div>')
    } else {
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: {'image': url.val()},
            dataType: 'json',
            success: function (data) {
                if (data === 'error-link') {
                    url.before('<div class="error">Ваша ссылка не рабочая</div>')
                } else if (data === 'small-size') {
                    url.before('<div class="error">Ваше изображение слишком мало</div>')
                } else {
                    $('div.images').prepend('<div class="col-md-2 image"><img src="'+ data +'" alt=""></div>')
                }
            },
        });
    }
});