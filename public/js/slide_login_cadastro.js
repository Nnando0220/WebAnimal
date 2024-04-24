$(document).ready(function() {
    function adjustSlideHeight() {
        let slideHeight = $('.forms-container').height();
        slideHeight = slideHeight + 40;
        $('.slide-container').css('height', slideHeight + 'px');
    }

    adjustSlideHeight();

    $('.link-cadastrar, .link-login').click(function(e) {
        e.preventDefault();
        adjustSlideHeight();
    });

    let slideIndex = 0;

    function slideImage() {
        $('.slider').css('transform', 'translateX(' + (-slideIndex * $('.slide').width()) + 'px)');
    }

    function nextSlide() {
        if (slideIndex === $('.slide').length - 1) {
            slideIndex = 0;
        } else {
            slideIndex++;
        }
        slideImage();
    }

    setInterval(nextSlide, 5000);
});
