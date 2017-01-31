if (window.location.hash && window.location.hash == '#_=_') {
    if (window.history && history.pushState) {
        window.history.pushState('', document.title, window.location.pathname);
    } else {
        var scroll = {
            top: document.body.scrollTop,
            left: document.body.scrollLeft
        };
        window.location.hash = '';
        document.body.scrollTop = scroll.top;
        document.body.scrollLeft = scroll.left;
    }
}

$(document).ready(function() {
    var autocompleteOption = {
            ajax: {
                cache: true,
                data: function (params) {
                    console.log(JSON.stringify(params));
                    return {
                        query: params.term
                    };
                },
                dataType: 'json',
                delay: 250,
                processResults: function (data) { return {results: data.suggestions}; },
                type: 'POST',
                url: '/search/ingredients',
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 3,
            templateResult: function  (repo) {
                if (repo.loading) return repo.text;
                return '<div>'+repo.value+'</div>';
            },
            templateSelection: function (data) { return data.value || data.text; },
        }
        ;

    $('form.autocomplete .search').autocomplete(autocompleteOption);
})