$('form').submit(function(event) {
    event.preventDefault();
    var form = $(this);
    var url = $('#url');
    var width = $('#width');
    var height = $('#height');
    var intPattern = /^\d+$/;

    form.find('.error').remove();

    if (url.val() === '') {
        url.before('<div class="error">Введите ссылку </div>')
    } else if (width.val() === '' || intPattern.test(width.val()) === false) {
        url.before('<div class="error">Введите минимальную ширину </div>')
    } else if (height.val() === '' || intPattern.test(height.val()) === false) {
        url.before('<div class="error">Введите минимальную высоту </div>')
    } else {
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: ({
                'url': url.val(),
                'width': width.val(),
                'height': height.val()
            }),
            dataType: 'json',
            success: function (data) {
                if (data === 'error-link') {
                    url.before('<div class="error">Ваша ссылка не рабочая</div>')
                } else if (data === 'small-size') {
                    url.before('<div class="error">Ваше изображение слишком мало</div>')
                } else {
                    if (data.length === 0)  {
                        url.before('<div class="error">Изображений не найдено</div>')
                    } else {
                        $.each(data, function (index, val){
                            $('div.images').prepend('<div class="col-md-2 image"><img src="' + val + '" alt=""></div>')
                        })
                    }
                }
            },
        });
    }
});