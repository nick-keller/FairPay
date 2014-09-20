$(function(){

    var tickets = [];

    $('#tickets .ticket').each(function(){
        var $this = $(this);

        tickets.push({
            id: $this.data('id'),
            name: $this.data('name'),
            price: $this.data('price'),
            price_contributor: $this.data('price-contributor'),
            force_check: $this.data('force-check')
        });
    });

    function chooseTicket(id, price, student, forceCheck){
        $('#validate-payment').hide();
        $('#amount').val(price);
        $('#ticket').val(tickets[id].id);
        var $selector = $('#payment-selector');
        $selector.show()
            .find('[data-payment]')
            .removeClass('disabled btn-danger')
            .addClass('btn-default');

        if(price < 10){
            $selector.find('[data-payment=cb], [data-payment=cheque]')
                .addClass('disabled btn-danger')
                .removeClass('btn-default');
        }

        if(!student.has_fairpay)
            $selector.find('[data-payment=fairpay]')
                .addClass('disabled btn-danger')
                .removeClass('btn-default');

        if(forceCheck){
            $selector.find('[data-payment=cb],[data-payment=fairpay],[data-payment=cash]')
                .addClass('disabled btn-danger')
                .removeClass('btn-default');
        }

        $selector.find('[data-payment]:not(.disabled)')
            .addClass('btn-default')
            .removeClass('active btn-success')
            .click(function(){
                var $this = $(this);
                $selector.find('[data-payment]:not(.disabled)')
                    .addClass('btn-default')
                    .removeClass('active btn-success');
                $this.addClass('active btn-success')
                    .removeClass('btn-default');

                $('#validate-payment').show();
                $('#method').val($this.data('payment'))
        });
    }

    $('[data-preview=student]').on('studentFound', function(e, student){
        var $selector = $('#ticket-selector');
        $selector.html('');
        $('#payment-selector').hide();

        $.each(tickets, function(i, e){
            var price = student.is_contributor ? e.price_contributor : e.price;
            var $btn = $('<a class="btn btn-default">' + e.name + '<br><span style="font-size: 30px">' + price + '€' + '</span></a>');
            $selector.append($btn);

            $btn.click(function(){
                var $this = $(this);

                $selector.find('a').removeClass('btn-success active')
                    .addClass('btn-default');
                $this.removeClass('btn-default')
                    .addClass('btn-success active');


                chooseTicket(i, price, student, e.force_check);
            });
        });

        if(tickets.length == 1){
            $selector.find('a:first').click();
        }

        $('#firstName').val(student.first_name);
        $('#lastName').val(student.last_name);
        $('#email').val(student.email);
        $('#studentId').val(student.id);
    });

    $('form[data-user]').submit(function(e){
        e.preventDefault();

        var $this = $(this);
        var student = {};

        $this.find('input').blur().each(function(){
            student[$(this).attr('name')] = $(this).val();
        });

        student.has_fairpay = student.has_fairpay == 1;
        student.is_contributor = student.is_contributor == 1;

        $('[data-preview=student]').trigger('studentFound', [student]);
    });

    $('form[data-user] input, [data-preview=student]').focus(function(){
        $('#ticket-selector').html('');
        $('#payment-selector').hide();
        $('#validate-payment').hide();
    });

    $('#user-selector [data-user]').click(function(){
        var $this = $(this);
        $('#user-selector [data-user]').toggleClass('hidden');
        $('#user [data-user]').toggleClass('hidden');

        $('#ticket-selector').html('');
        $('#payment-selector').hide();
        $('#validate-payment').hide();
    });
});