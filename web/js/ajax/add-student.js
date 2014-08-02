$(function(){
    $('form[name=ferus_studentbundle_student]').submit(function(e){
        e.preventDefault();

        var $form = $(this);
        var $container = $form.next();
        var $btn = $form.find('button[type=submit]');

        $btn.button('loading');
        $btn.blur();

        $.ajax({
            url: window.location,
            data: $(this).serialize(),
            type: 'post',
            success: function() {
                $btn.button('reset');
                $form[0].reset();
                var $message = $('<p class="alert alert-success" role="alert">Etudiant créé avec succès.</p>');
                $container.prepend($message);
            },
            error: function(){
                $btn.button('reset');
                var $message = $('<p class="alert alert-danger" role="alert">Une erreur est survenue, vérifiez le formulaire.</p>');
                $container.prepend($message);
            }
        });
    });
});