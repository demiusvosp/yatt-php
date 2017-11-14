jQuery(function(){

    var $user_select = $('.user_select');
    var $widget = $('.user-accesses');
    var startAdding = false;

    $widget.on('click', '.add_user', function(event){
        console.log('add_user');
        $item = $(event.currentTarget);
        $item.hide();
        $user_select.detach().insertAfter($item).show();
        startAdding = true;

    }).on('click', '.remove_user', function(event){
        console.log('remove_user');
        $item = $(event.currentTarget).parents('.user_wrapper');
        $item.css('border', 'red 1px solid');

        $.post(
            $widget.data('remove-url'),
            {
                userId: $item.data('id'),
                role: $item.parent('td').data('role'),
                project: $widget.data('project')
            },
            function (result) {
                console.log(result);
                if(result.success) {
                    //@TODO переделать красиво, без перезагрузки страницы
                    window.location.reload();
                }
            }
        );
    });

    $user_select.on('select2:select', function(event) {
        console.log('select');

        if(startAdding) {
            $.post(
                $widget.data('add-url'),
                {
                    userId: event.params.data.id,
                    role: $(this).parent('td').data('role'),
                    project: $widget.data('project')
                    //html: true
                },
                function (result) {
                    if(result.success) {
                        //@TODO переделать красиво, без перезагрузки страницы
                        window.location.reload();
                    }
                }
            );
            startAdding = false;
        }

    }).on('select2:unselect', function(event) {
        console.log('cancel select');
        // ну отменили выбор и ок, не очень ясно что нам делать
    });

});
