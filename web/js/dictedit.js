
jQuery(function(){
    $(".dictForm").on('click', '.drop-item', function(){
        var index = $(this).data('id');
        console.log('drop item: ' + index);
        $(this).parents("tr").remove();
    })

});