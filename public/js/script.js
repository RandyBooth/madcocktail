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

var alertMessage = function(type, message) {
    type = typeof type !== 'undefined' ? type : 'success';
    message = typeof message !== 'undefined' ? message : '';
    $('#alert-wrapper').html('<div class=\"alert alert-'+type+' alert-dismissible fade show\" role=\"alert\"><div class=\"container\"><div class=\"col-12\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>'+message+'</div></div></div>');
    alertRun();
}

var alertRun = function() {
    $('.alert').alert();

    setTimeout(function() {
        $('.alert').alert('close');
    }, 10000);
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

    var $alert = $('.alert');

    if ($alert.length > 0) {
        alertRun();
    }

    $('form.autocomplete .search').autocomplete(autocompleteOption);
})