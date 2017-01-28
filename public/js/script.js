if (window.location.hash && window.location.hash == '#_=_') {
    if (window.history && history.pushState) {
        window.history.pushState("", document.title, window.location.pathname);
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#search').autocomplete({
        autoSelectFirst: true,
        dataType: 'json',
        deferRequestBy: 250,
        groupBy: 'category',
        minChars: 3,
        serviceUrl: '/autocomplete',
        showNoSuggestionNotice: true,
        triggerSelectOnValidInput: false,
        type: 'POST',
        onSelect: function (suggestion) {
            console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
})