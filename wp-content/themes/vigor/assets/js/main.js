$(document).ready(function() {
    let topMenu = $("#navbar");

    $(window).scroll(function() {
        let fromTop = $(this).scrollTop();
    
        if (fromTop > 100) {
            topMenu.addClass('scrolled vg-bg-primary');
        } else {
            topMenu.removeClass('scrolled vg-bg-primary');
        }
    });
});