
jQuery(function(){

    $dictForm = $("#dictForm");

    // Удаление элемента
    $dictForm.on('click', '.drop-item', function(){
        var item = $(this).parents("tr");
        item.addClass('dict_item_warning');

        var request = {
            'class': $dictForm.data('dict'),
            'id': item.data('id')
        };
        if($dictForm.data('project')) {
            request.project_id = $dictForm.data('project')
        }

        $.ajax({
            url:  $dictForm.data('dict-url') + '/delete',
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
        item.addClass('dict_item_warning');

        var request = {
            'class': $dictForm.data('dict'),
            'id': item.data('id')
        };
        if($dictForm.data('project')) {
            request.suffix = $dictForm.data('project')
        }

        $.ajax({
            url:  $dictForm.data('dict-url') + '/past',
            type: 'POST',
            data: request,
            success: function (answer) {
                if (answer) {
                    item.removeClass('dict_item_warning').addClass('dict_item_past');
                    $('#'+$dictForm.data('dict-name')+'-'+item.id+'-type oprion[value=2]').attr('selected', 'selected');
                    // лять, может проще перегрузить это гавно
                }
            }
        });
    });
});