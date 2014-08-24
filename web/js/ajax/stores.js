$(function(){
    $('#add-new-store').submit(function(e){
        e.preventDefault();

        var name = $(this).find('input').val();
        $('#stores').loadTemplate($('#store-template'), {
            storeName: name
        },{
            append: true
        })
    });
});