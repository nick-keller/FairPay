$(function(){
    jQuery.fn.extend({
        money: function() {
            return this.attr('type', 'tel')
                .blur(function(){
                    var $this = $(this);
                    var val = $this.val();

                    val = val.replace(',', '.');
                    val = val.replace(' ', '');

                    if(val.match(/^-?\d+$/))
                        val = val + '.00';
                    else if(val.match(/^\.\d$/))
                        val = '0' + val + '0';
                    else if(val.match(/^\.\d{2}$/))
                        val = '0' + val;
                    else if(val.match(/^$/))
                        val = '0.00';
                    else if(val.match(/^-?\d+\.$/))
                        val = val + '00';
                    else if(val.match(/^-?\d+\.\d$/))
                        val = val + '0';
                    else if(!val.match(/^-?\d+\.\d{2}$/))
                        val = '0.00';

                    $this.val(val);
                }).focus(function(){
                    var $this = $(this);
                    var val = $this.val();

                    if(val == '0.00')
                        $this.val('');
                    else if(val.match(/^-?\d+\.00$/))
                        $this.val(val.replace('.00', ''))
                });
        }
    });

    $('input[data-type=money]').money();
});
