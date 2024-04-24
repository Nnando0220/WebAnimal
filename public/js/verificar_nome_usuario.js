$(document).ready(function() {
    $('#nome_usuario').blur(function() {
        const username = $(this).val();
        if (username !== '') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/autorizar/verificar-username',
                type: 'POST',
                data: {
                    username: username
                },
                success: function(response) {
                    if (response.disponibilidade) {
                        $('#nome_usuario_disponivel').text('Nome de usuário disponível.').removeClass('status-indisponivel').addClass('status-disponivel');
                    } else {
                        $('#nome_usuario_disponivel').html('Nome de usuário não disponível.<br> Saia do campo para verificar.').removeClass('status-disponivel').addClass('status-indisponivel');
                    }
                }
            });
        } else if(username.length < 6){
            $('#nome_usuario_disponivel').text('Nome de usuário deve ter no minímo 6 caracteres. Saia do campo para verificar.').removeClass('status-disponivel').addClass('status-indisponivel');
        }
    });
});
