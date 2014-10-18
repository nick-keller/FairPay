
jQuery.fn.extend({
    student: function() {
        return this
            .data('api', $('body').data('get-student-api'))
            .data('waiting', false)
            .data('searching', false)
            .data('query', '')
            .change(function(){
                var $this = $(this);
                var $helpTxt = $this.parents('.input-group').next();

                if($this.data('query') == $this.val()) return;

                if($this.data('searching') == true){
                    $this.data('waiting', true);
                    return;
                }
                $this.data('searching', true);

                setTimeout(function(){
                    $this.data('query', $this.val());
                    $.ajax($this.data('api').replace('-query-', $this.val()), {
                        error: function(){
                            $this.data('searching', false);
                            if($this.data('waiting')){
                                $this.data('waiting', false);
                                $this.keyup();
                            }
                        },
                        success: function(data){
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

                            if(!data.has_fairpay)
                                $helpTxt.append(' <i style="color: #a94442">pas de compte FairPay</i>');

                            $this.trigger('studentFound', [data]);
                        }
                    });

                }, 200);
            })
            .keyup(function(){
                $(this).change();
            });
    }
});

$(function(){
    $('[data-preview=student]').student().change();
});