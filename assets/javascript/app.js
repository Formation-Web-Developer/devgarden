jQuery($ => {
    $('#showMenu').click(event => {
        const menu = $('#headerMenu');
        if (menu.hasClass('active')) {
            menu.slideUp(() => menu.removeAttr('style').removeClass('active'));
        } else {
            menu.slideDown(() => menu.removeAttr('style').addClass('active'));
        }
    });

    $('form[data-type="commentForm"]').submit(e => {
        e.preventDefault();
        const form = $(e.target)
        form.find('.field').addClass('disabled');
        let message = createAlert('warning', 'notched circle loading', 'Traitement de votre commentaire...')
        form.parent().prepend(message);
        $.ajax({
            method: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(response => {
            setTimeout(()=> {
                message.remove();
                let timeout = 2000;
                if (response.type === 'success') {
                    message = createAlert('positive', 'check', 'Votre commentaire a bien été posté !');
                    form.find('textarea').val('');
                    reloadComments($('#'+form.attr('data-target')));
                } else {
                    message = createAlert('negative', 'times', response.reason, 'Votre commentaire n\'a pas pu être posté !')
                    timeout = 10000;
                }
                form.parent().prepend(message);
                form.find('.field').removeClass('disabled');
                setTimeout(()=>message.remove(), timeout)
            }, 1000);
        });
    })

    function reloadComments(element)
    {
        element.empty();
        $.ajax({
            method: 'get',
            url: element.attr('data-url')
        }).done(response => {
            JSON.parse(response).reverse().forEach(comment => {
                element.append(createComment(comment))
            })
        });
    }

    function createAlert(clazz, icon, text, header = '')
    {
        return $('<div>')
            .addClass('my-1')
            .append(
                $('<div>')
                    .addClass('ui ' + clazz + ' icon message')
                    .append(
                        $('<i>').addClass(icon+' icon'),
                        $('<div>').addClass('content')
                            .append(
                                $('<div>').addClass('header').text(header)
                            )
                            .append($('<p>').text(text))
                    )
            );
    }

    function createComment(comment)
    {
        const $text = $('<div>').addClass('text');
        comment.comment.split('\n').forEach((split, index) => {
            if (index !== 0) { $text.append('<br>'); }
            $text.append(split);
        })
        return $('<div>').addClass('comment')
            .append(
                $('<a>').addClass('avatar')
                    .append($('<img>').attr('src', 'https://picsum.photos/75/75')),
                $('<div>').addClass('content')
                    .append(
                        $('<a>').attr('href', '#').text(comment.author.name),
                        $('<div>').addClass('metadata')
                            .append($('<span>').addClass('date').text(comment.created_at)),
                        $text
                    )
            );
    }

    $('*[data-type="commentList"]').each((index, element) => reloadComments($(element)));

    const selectCategories = $('.ui.search.dropdown.select-categories');

    selectCategories.parents('form').submit((e) => {
        $(e.target.querySelectorAll('[name]'))
            .each((index, element) => {
                if(element.name === 'categories') {
                    $(element).append(
                        $('<option>')
                            .val($(e.target).find('.item.active.selected').attr('data-value'))
                            .attr('selected', 'true')
                            .text('')
                    );
                }
            });
    })

    const history = [];
    let defaultValue = null;
    selectCategories.dropdown({
            minCharacters: 0 ,
            onNoResults: function (text) {
                if (defaultValue !== null){

                    return
                }
                defaultValue = false;
                const select = $(this)
                updateCategories(select,text,select.attr('data-url'),true)
            }
        })
        .keydown(function (e){
            const select = $(this);
            const input = select.find('input.search');
            const lastText = input.val();
            setTimeout(() => {
                let text = input.val();
                if(lastText === text) {
                    return;
                }
                if (text.length === 0){
                    setTimeout(()=> {
                        select.dropdown('change values', defaultValue);
                    },100)
                    return
                }
                updateCategories(select,text,select.find('select').attr('data-url'))

            },50)
        })
    function updateCategories(select, text, url, isDefault = false) {
        if (history[text]) {
            setTimeout(()=> {
                select.dropdown('change values', history[text]);
                // if (isDefault){
                //     select.dropdown('toggle')
                // }
            },1)

            return;
        }
        $.ajax({
            url: url + '/' + encodeURI(text),
            method: 'GET'
        }).done(response => {
            try {
                const results = [];
                const json = JSON.parse(response);
                let toggle = false;
                if (json.length === 0) {
                    results.push({
                        name: text + ' (Nouvelle catégorie)',
                        value: text
                        // selected: true
                    })
                    toggle = true;
                } else {
                    json.forEach((option, index) => {
                        results.push({
                            name: option.name,
                            value: option.id
                        });
                    })
                }

                select.dropdown('change values', results);
                if (text.length === 0 && isDefault){
                    defaultValue = results;
                }
                if (isDefault || toggle){
                    select.dropdown('toggle')
                }
                history[text] = results;
            } catch (e) {}
        })
    }
})
