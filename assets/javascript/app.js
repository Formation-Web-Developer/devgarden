jQuery($ => {
    $('#showMenu').click(event => {
        const menu = $('#headerMenu');
        if (menu.hasClass('active')) {
            menu.slideUp(() => menu.removeAttr('style').removeClass('active'));
        } else {
            menu.slideDown(() => menu.removeAttr('style').addClass('active'));
        }
    });
});
