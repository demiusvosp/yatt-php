
$(function () {

    // включим тултипы.
    $('[data-toggle="tooltip"]').tooltip();


    // форма входа
    /*@TODO Какогото хрена не вешается на свежесозданную из аякса форму */
    $(document).on('beforeSubmit', function() {
        var form = $(this);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serializeArray()
        })
        .done(function(data) {
            alert(data);
            if(data.success) {
                // данные сохранены
            } else {
                // сервер вернул ошибку и не сохранил наши данные
            }
        })
        .fail(function () {
            alert('FAIL');
            // не удалось выполнить запрос к серверу
        });

        return false; // отменяем отправку данных формы
    })

});