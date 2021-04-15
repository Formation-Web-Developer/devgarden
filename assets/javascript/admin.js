jQuery($ => {
    $('.ui.dropdown').dropdown();
    $('.sidebar-menu-toggler').on('click', function() {
        const target = $(this).data('target');
        $(target)
            .sidebar({
                dinPage: true,
                transition: 'overlay',
                mobileTransition: 'overlay'
            })
            .sidebar('toggle');
    });

    $('input[data-type="search"]').keyup(e => {
        if (e.keyCode === 13 && e.target.value.length > 0 && e.target.dataset.url) {
            if (!(e.target.dataset.ajax ?? false)) {
                window.location = e.target.dataset.url + '/' + encodeURI(e.target.value);
            }
        }
    });

    $('.ui.search.dropdown.select-role')
        .dropdown({
            onChange: function(value, text, selectedItem) {
                $(selectedItem).parents('form').submit();
            }
        })
    ;
})
