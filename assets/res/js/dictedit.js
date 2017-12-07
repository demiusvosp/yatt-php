
jQuery(function(){

    $dictForm = $("#dictForm");

    // Удаление элемента
    $dictForm.on('click', '.drop-item', function(){
        var item = $(this).parents("tr");
        item.addClass('dict_item_dropping');

        var request = {
            'dict': $dictForm.data('dict'),
            'item_id': item.data('id')
        };
        if($dictForm.data('project')) {
            request.project_id = $dictForm.data('project')
        }

        $.ajax({
            url:  $dictForm.data('drop-url'),
            type: 'DELETE',
            data: request,
            success: function (answer) {
                if (answer) {
                    item.remove();
                }
            }
        });
    });


    // Порядок
    $(".ui-sortable").sortable({
        items: 'tr:not(.disable-reposition)',
        update: function(event, ui) {
            var newOrder = $(".ui-sortable").sortable("toArray");
            console.log(newOrder);
            for (var i = 0; i < newOrder.length; i++) {
                $('#'+$dictForm.data('dict-name')+'-'+newOrder[i]+'-position').val(i);
            }
        }
    });


    // Для справочников версий. Перевод версии к прошлой
    $dictForm.on('click', '.past-item', function(){
        var item = $(this).parents("tr");
        item.addClass('dict_item_dropping');

        var request = {
            'dict': $dictForm.data('dict'),
            'item_id': item.data('id')
        };
        if($dictForm.data('project')) {
            request.project_id = $dictForm.data('project')
        }

        $.ajax({
            url:  $dictForm.data('drop-url'),
            type: 'DELETE',
            data: request,
            success: function (answer) {
                if (answer) {
                    item.remove();
                }
            }
        });
    });
});