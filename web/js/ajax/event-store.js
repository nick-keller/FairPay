$(function(){

    var tickets = [];

    $('#tickets .ticket').each(function(){
        var $this = $(this);

        tickets.push({
            name: $this.data('name'),
            price: $this.data('price'),
            price_contributor: $this.data('price-contributor')
        });
    });

    function chooseTicket(id, price, fairpay){
        var $selector = $('#payment-selector');
        $selector.show();

        if(price < 10)
            $selector.find('[data-payment=cb]')
                .addClass('disabled btn-danger')
                .removeClass('btn-default');

        if(!fairpay)
            $selector.find('[data-payment=fairpay]')
                .addClass('disabled btn-danger')
                .removeClass('btn-default');
    }

    $('[data-preview=student]').on('studentFound', function(e, student){
        var $selector = $('#ticket-selector');
        $selector.html('');
        $('#payment-selector').hide()
            .find('[data-payment]')
                .removeClass('disabled btn-danger')
                .addClass('btn-default');

        $.each(tickets, function(i, e){
            var price = student.is_contributor ? e.price_contributor : e.price;
            var $btn = $('<a class="btn btn-default">' + e.name + '<br><span style="font-size: 30px">' + price + '€' + '</span></a>');
            $selector.append($btn);

            $btn.click(function(){
                var $this = $(this);

                $selector.find('a').unbind('click').addClass('disabled');
                $this.removeClass('btn-default')
                    .addClass('btn-success active')
                    .removeClass('disabled');


                chooseTicket(i, price, student.has_fairpay);
            });
        });

        if(tickets.length == 1){
            $selector.find('a:first').click();
        }
    });
});