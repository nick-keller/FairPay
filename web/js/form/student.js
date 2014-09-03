$(function(){
    jQuery.fn.extend({
        student: function() {
            return this
                .data('api', $('body').data('get-student-api'))
                .change(function(){
                    var $this = $(this);
                    var $helpTxt = $this.parents('.input-group').next();

                    if($this.data('searching') == true){
                        $this.data('waiting', true);
                        return;
                    }
                    $this.data('searching', true);

                    setTimeout(function(){
                        $.get($this.data('api').replace('-query-', $this.val()), function(data){
                            $this.data('searching', false);
                            if($this.data('waiting')){
                                $this.data('waiting', false);
                                $this.keyup();
                            }

                            $this.val(data.id);
                            $this.blur();
                            $helpTxt.html('<b>' + data.first_name + ' ' + data.last_name + '</b>');

                            if(data.is_contributor)
                                $helpTxt.append(' cotisant');
                            else
                                $helpTxt.append(' non-cotisant');
                        });
                    }, 100);
                })
                .keyup(function(){
                    $(this).change();
                });
        }
    });

    $('[data-preview=student]').student();
});