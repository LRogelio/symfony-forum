$(document).ready(function () {

    // mobile menu
    $('.nav .nav-toggle').on('click', function () {
        var target = $('.nav .nav-right');

        if ($(this).hasClass('is-active')) {
            $(this).removeClass('is-active');
            target.addClass('animated slideOutUp');
            target.removeClass('is-active');
        } else {
            $(this).addClass('is-active');
            target.addClass('is-active');
        }
    });
});