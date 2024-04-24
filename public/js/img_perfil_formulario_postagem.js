let originalElement;

function toggleDropdown() {
    const dropDown = $('.dropdown-content');
    const indicator = $('.indicator');
    indicator.toggleClass('fa-chevron-down fa-chevron-up');
    dropDown.toggleClass('mostrar');
}

function verificarTamanhoTela() {
    const screenWidth = $(window).width();
    const larguraMinima = 850;

    if (screenWidth <= larguraMinima) {
        const spanElement = $('<span/>', {
            class: 'dropbtn',
            text: 'Perfil'
        });
        const indicatorIcon = $('<i/>', {
            class: 'indicator fas fa-chevron-down',
        });
        spanElement.append(indicatorIcon);
        $('.dropbtn').replaceWith(spanElement);
    } else {
        $('.dropbtn').replaceWith(originalElement);
    }
}

$(document).ready(function() {
    originalElement = $('.dropbtn').clone();
    verificarTamanhoTela();

    $(window).on('resize', function() {
        verificarTamanhoTela();
    });

    $('body').on('click', '.dropbtn', function(e) {
        toggleDropdown();
    });

    $(".mostrar-forms").click(function (){
        $(".forms").toggle(function() {
            const isVisible = $(this).is(':visible');
            if (isVisible) {
                $('.indicator').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                $('.indicator').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        });
    })
});
