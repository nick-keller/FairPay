$(function(){
    function updateCheck(){
        var products = [];
        var formAction = [];
        var total = 0;

        $('[data-product]').each(function(){
            var $this = $(this);

            if(parseInt($this.val()) > 0){
                products.push($this.val()+' '+$this.data('product'));
                formAction.push($this.data('product')+'='+$this.val());
            }
            total += parseFloat($this.data('price')) * parseInt($this.val());
        });

        $('[data-display]').text(total.toFixed(2));
        $('[data-input=amount]').val(total);
        $('[data-input=cause]').val(products.join(', '));
        $('form').attr('action', '?'+formAction.join('&'));
    }

    $('[data-add]').click(function(){
        var $this = $(this);
        var $input = $this.parents('.input-group').find('input');

        $input.val(Math.max(0, parseInt($input.val())+$this.data('add')));
        updateCheck();
    });

    $('[data-product]').blur(function(){
        var $this = $(this);
        var val = $this.val();

        if(val == '')
            val = $this.data('prev');
        else
            val = parseInt($this.val());

        if(isNaN(val)) val = 0;

        $this.val(val);
        updateCheck();

    }).focus(function(){
        var $this = $(this);
        $this.data('prev', $this.val());
        $this.val('');
    });

    updateCheck();
});