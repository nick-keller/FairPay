$(function(){
    $('#filter').keyup(function(){
        var $this = $(this);
        var $result = $('#result');

        if($this.data('searching') == true){
            $this.data('waiting', true);
            return;
        }
        $this.data('searching', true);

        $result.load($this.data('search'), {search:$this.val()}, function(){
            $this.data('searching', false);
            if($this.data('waiting')){
                $this.data('waiting', false);
                $this.keyup();
            }

            $('[data-contributor]').each(function(){
                var $item = $(this);

//                if($item.data('contributor') == 1)
                $item.find('[data-value=' + $item.data('contributor') + ']').addClass('active');
                $item.find('[data-value=' + (1-$item.data('contributor')) + ']').fadeTo(0, .5);

                $item.find('[data-value]').click(function(){
                    $item.find('[data-value]')
                        .toggleClass('active');

                    $(this).fadeTo(500, 1);
                    $(this).siblings().fadeTo(500, .5);
                    $('#filter').select();

                    $.get($(this).data('save'));
                });
            })
        });
    });
});