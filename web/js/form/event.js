$(function(){

    function updatePrice(){
        var $optionTotal = $('.options-total');
        var total = parseFloat($optionTotal.data('base-price'));
        var $checkboxes = $('[data-option-id]');
        var str = '';

        $checkboxes.each(function(){
            var $this = $(this);
            total += $this.is(':checked') ? parseFloat($this.data('option-price')) : 0;
            str += '|' + ($this.is(':checked') ? 1 : 0);
        });

        $optionTotal.html(total.toFixed(2));
        $('#ferus_eventbundle_participation_options').val(str.substr(1));

        if(total < 10){
            if($('#price [data-payment="cb"], #price [data-payment="cheque"]')
                .removeClass('btn-default')
                .addClass('btn-danger disabled')
                .filter('.active')
                .removeClass('active btn-success')
                .length){
                $('#price [data-payment=""]')
                    .addClass('btn-success active')
                    .removeClass('btn-default');
                $('#ferus_eventbundle_participation_paymentMethod').val('');
            }
        }
        else{
            $('#price [data-payment="cb"], #price [data-payment="cheque"]')
                .addClass('btn-default')
                .removeClass('btn-danger disabled');
        }

        $('#ferus_eventbundle_participation_paymentAmount').val(total);
    }


    $('[data-preview=student]').on('studentFound', function(e, student){
        var $container = $('#participation-data');
        $('#loading').show();
        $container.addClass('hidden');

        // options
        var $optionTotal = $('.options-total');
        var basePrice = parseFloat($container.data(student.is_contributor ? 'price' : 'price-non-contributor'));
        $optionTotal.data('base-price', basePrice);
        $('[data-option-id]').prop('checked', false);

        // fields
        $('[data-extra-field-id]').val('').first().blur();

        // payment
        $('[data-payment]').attr('class', 'btn btn-default');
        $('[data-payment=""]')
            .addClass('btn-success active')
            .removeClass('btn-default');
        updatePrice();

        // fairpay
        if(!student.has_fairpay){
            $('[data-payment="fairpay"]')
                .removeClass('btn-default')
                .addClass('btn-danger disabled');
        }

        // deposit
        if(parseFloat($container.data('price-deposit')) < 10){
            $('[data-payment="cb"], [data-payment="cheque"]')
                .removeClass('btn-default')
                .addClass('btn-danger disabled');
        }

        if(parseInt($container.data('deposit-by-check')) == 1){
            $('#deposit [data-payment="cb"], #deposit [data-payment="cash"], #deposit [data-payment="fairpay"]')
                .removeClass('btn-default')
                .addClass('btn-danger disabled');
        }

        // form
        $('#ferus_eventbundle_participation_studentId').val(student.id);
        $('#ferus_eventbundle_participation_firstName').val(student.first_name);
        $('#ferus_eventbundle_participation_lastName').val(student.last_name);
        $('#ferus_eventbundle_participation_email').val(student.email);
        $('#ferus_eventbundle_participation_depositAmount').val($container.data('price-deposit'));
        $('#ferus_eventbundle_participation_paymentMethod').val('');
        $('#ferus_eventbundle_participation_depositMethod').val('');
        $('#ferus_eventbundle_participation_comments').val('');

        $.get($container.data('get-participation').replace('_email_', student.email), function(data){
            if(data != null){
                // options
                $('[data-option-id]').each(function(index){
                    if(data.options[index] == "1"){
                        $(this).prop('checked', true);
                    }
                    index++;
                });
                updatePrice();

                // fields
                $('[data-extra-field-id]').each(function(index){
                    $(this).val(data.fields[index]).blur();
                });

                $('#ferus_eventbundle_participation_comments').val(data.comments);
                $('#price [data-payment="' + data.payment_method + '"]').click();
                $('#deposit [data-payment="' + data.deposit_method + '"]').click();
            }

            $('#loading').hide();
            $container.removeClass('hidden');
        });
    });

    $('label').click(function(){
        updatePrice();
    });

    $('[data-payment]').click(function(){
        var $this = $(this);
        $this.parent().find('div').not($this)
            .removeClass('btn-success active')
            .addClass('btn-default');

        $this
            .addClass('btn-success active')
            .removeClass('btn-default');
    });

    $('#price [data-payment]').click(function(){
        $('#ferus_eventbundle_participation_paymentMethod').val($(this).data('payment'));
    });

    $('#deposit [data-payment]').click(function(){
        $('#ferus_eventbundle_participation_depositMethod').val($(this).data('payment'));
    });

    $('[data-extra-field-id]').blur(function(){
        var str = '';

        $('[data-extra-field-id]').each(function(){
            str += '|' + $(this).val();
        });

        $('#ferus_eventbundle_participation_fields').val(str.substr(1));
    });

    $('#user-external form').submit(function(e){
        e.preventDefault();

        var $form = $(this);
        $form.find('.extra-info').hide();

        var student = {
            id: null,
            first_name: $form.find('input[name="first-name"]').val(),
            last_name: $form.find('input[name="last-name"]').val(),
            is_contributor: false,
            email: $form.find('input[name="email"]').val(),
            class: null,
            has_fairpay: false
        };

        $('[data-preview=student]').trigger('studentFound', [student]);
    });

    $('[data-toggle="tab"], [data-preview="student"], #user-external input').click(function(){
        $('#user-external .extra-info').show();
        $('#participation-data').addClass('hidden');
    });

    $('#user-external tr').click(function(){
        var $this = $(this);

        var $form = $('#user-external form');
        $form.find('input[name="first-name"]').val($this.data('first-name'));
        $form.find('input[name="last-name"]').val($this.data('last-name'));
        $form.find('input[name="email"]').val($this.data('email'));
        $form.submit();
    });
});