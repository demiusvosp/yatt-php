
jQuery(function(){

    // Удаление элемента
    $("#dictForm").on('click', '.drop-item', function(){
        var form = $('#dictForm');
        var item = $(this).parents("tr");
        item.addClass('dict_item_dropping');

        var request = {
            'dict': form.data('dict'),
            'item_id': item.data('id')
        };
        if(form.data('project')) {
            request.project_id = form.data('project')
        }

        $.ajax({
            url:  form.data('drop-url'),
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
        update: function(event, ui) {
            var form = $('#dictForm');
            var newOrder = $(".ui-sortable").sortable("toArray");
            console.log(newOrder);
            for (var i = 0; i < newOrder.length; i++) {
                $('#'+form.data('dict-name')+'-'+newOrder[i]+'-position').val(i);
            }
        }
    });
});