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
        autoSelectFirst: true,
        dataType: 'json',
        deferRequestBy: 250,
        groupBy: 'group',
        minChars: 3,
        serviceUrl: '/search',
        showNoSuggestionNotice: true,
        triggerSelectOnValidInput: false,
        type: 'POST',
    }
    ;

    $('form.autocomplete .search').autocomplete(autocompleteOption);
})