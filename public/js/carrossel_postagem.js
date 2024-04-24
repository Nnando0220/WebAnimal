$(document).ready(function() {
    $('[id^=carouselExample]').each(function() {
        var currentIndex = 0;

        $(this).carousel({
            interval: false,
        });

        $(this).on('click', '.carousel-control-next, .carousel-control-prev', function() {
            var direction = $(this).hasClass('carousel-control-next') ? 'next' : 'prev';
            $(this).closest('.carousel').carousel(direction);
        });
    });
});

