$(function(){

    function makeProduct($product){
        $product.find('[data-remove-product]').click(function(){
            $.get($(this).data('remove-product'));

            var $thisProduct = $(this).parents('.product');
            $thisProduct.fadeTo(500, 0, function(){
                $thisProduct.remove();
            });
        });
    }

    function makeStore($store){
        $store.find('[data-remove]').click(function(){
            $.get($(this).data('remove'));

            var $thisStore = $(this).parents('.store');
            $thisStore.wrap($('<div></div>'));
            var $div = $thisStore.parent();
            $thisStore.fadeTo(1000, 0);
            $div.animate({height:0}, 1000, function(){
                $div.remove();
            })
        });

        $store.find('[data-new-product]').click(function(){
            var $this = $(this);
            var $loading = $('<div class="list-group-item">Chargement...</div>');

            $this.before($loading)

            $.get($this.data('new-product'), function(data){
                $loading.remove();
                var $product = $(data);
                $this.before($product);
                makeProduct($product);
            });
        });

        makeProduct($store.find('.product'));
    }

    makeStore($('.store'));

    $('#add-new-store').submit(function(e){
        e.preventDefault();

        var $this = $(this);
        var $input = $(this).find('input');
        var $fieldset = $this.find('fieldset');
        var $stores = $('#stores');

        $fieldset.attr('disabled', '');

        $.get($this.attr('action'), {name:$input.val()}, function(data){
            $fieldset.removeAttr('disabled');
            $input.val('');

            var height = $stores.height();
            var $store = $(data);
            $stores.append($store);
            makeStore($store);
            var target = $stores.height();
            $stores.height(height).animate({height:target+'px'}, 500, function(){
                $stores.height('auto');
            })
        })
    });
});