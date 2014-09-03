$(function(){
    jQuery.fn.extend({
        student: function() {
            return this
                .data('api', $('body').data('get-student-api'))
                .change(function(){
                    var $this = $(this);
                    var $helpTxt = $this.parents('.input-group').next();

                    $.get($this.data('api').replace('-query-', $this.val()), function(data){
                        $this.val(data.id);
                        $this.blur();
                        $helpTxt.html('<b>' + data.first_name + ' ' + data.last_name + '</b>');

                        if(data.is_contributor)
                            $helpTxt.append(' cotisant');
                        else
                            $helpTxt.append(' non-cotisant');
                    });
                })
                .keyup(function(){
                    $(this).change();
                });
        }
    });

    $('[data-preview=student]').student();
});