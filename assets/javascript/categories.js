jQuery($ => {
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
