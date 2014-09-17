$(function(){
    $('[data-prototype]').data('index', $('form .well').length)

    .click(function(e){
        e.preventDefault();

        var $this = $(this);
        var html = $this.data('prototype').replace(/__name__/g, $this.data('index'));
        $this.data('index', $this.data('index') +1);

        var $container = $('<div class="well"><div class="text-right"><button class="btn btn-danger" data-remove-widget><span class="glyphicon glyphicon-remove"></span> Supprimer</button></div></div>');
        $container.prepend(html);
        $container.find('input[data-type=money]').money();
        $container.find('[data-remove-widget]').click(function(e){
            e.preventDefault();
            var $this = $(this);
            $this.parents('.well').remove();
        });

        $this.before($container);
    });

    $('[data-remove-widget]').click(function(e){
        e.preventDefault();
        var $this = $(this);
        $this.parents('.well').remove();
    });
});