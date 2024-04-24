$(document).ready(function() {
    $('.link-cadastrar').click(function(e) {
        e.preventDefault();
        window.location.href = "?form=cadastro";
    });
    $('.link-login').click(function(e) {
        e.preventDefault();
        window.location.href = "?form=login";
    });
    const urlParams = new URLSearchParams(window.location.search);
    const form = urlParams.get('form');
    if (form === 'cadastro') {
        $('.slide-container').removeClass('active');
        $('.login-form').removeClass('active');
        $('.cadastro-form').addClass('active');
    } else {
        $('.slide-container').addClass('active');
        $('.cadastro-form').removeClass('active');
        $('.login-form').addClass('active');
    }

    if ($(window).width() < 500){
        $('.slide-container.active').remove();
    }
});
