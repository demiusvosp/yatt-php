
$(function () {

    // включим тултипы.
    $('[data-toggle="tooltip"]').tooltip();


    // форма входа
    var $login_button = $('#login_button');
    $login_button.data('login-url', $login_button.attr('href')).removeAttr('href');
    $login_button.on('click', function () {
        $login_dialog = $('#login-dialog');

        $login_dialog.modal({
            backdrop: 'static'
        });
        $login_dialog.on('beforeSubmit', function() {
            var form = $('#login-form', this);

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serializeArray()
            })
                .done(function(data) {
                    if(data.success) {
                        /*@TODO внешне системы вызвавшие диалог должны подсказать что делать, достаточно ли вернуть
                            им true и поменять меню, или необхоимо более сложная обработка.
                            Пока что просто устроим reload
                         */
                        window.location.reload();
                    } else {
                        console.log(data.errors);
                        form.yiiActiveForm('updateMessages', data.errors, true);
                    }
                })
                .fail(function () {
                    //@TODO когда появится toasr или я научусь дергать yii'шные всплывашки эту ошибку надо вывести туда
                    console.log('Error in send login');
                    // не удалось выполнить запрос к серверу
                });

            return false; // отменяем отправку данных формы
        })
    });

});