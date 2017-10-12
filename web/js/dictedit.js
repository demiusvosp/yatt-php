
jQuery(function(){
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
    })

    $(".ui-sortable").sortable({
        update: function(event, ui) {
            console.log(event);
            console.log(ui);
        }
    });
});