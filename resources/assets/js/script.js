var autocompleteOption = {
    autoSelectFirst: true,
    dataType: 'json',
    deferRequestBy: 250,
    groupBy: 'group',
    minChars: 3,
    serviceUrl: '/ajax/search',
    showNoSuggestionNotice: true,
    triggerSelectOnValidInput: false,
    type: 'POST',
    onSearchStart: function() {
        var $formSearch = $('#form-search'),
            $formSearchId = $('#search-id', $formSearch),
            $formSearchGroup = $('#search-group', $formSearch);

        $formSearchId.val('');
        $formSearchGroup.val('');
    },
    onSelect: function (suggestion) {
        console.log(suggestion);
        if ((typeof suggestion.id !== 'undefined' && suggestion.id !== null) && (typeof suggestion.data.group !== 'undefined' && suggestion.data.group !== null)) {
            var $formSearch = $('#form-search'),
                $formSearchId = $('#search-id', $formSearch),
                $formSearchGroup = $('#search-group', $formSearch);

            $formSearchId.val(suggestion.id);
            $formSearchGroup.val(suggestion.data.group);
            $formSearch.submit();
        }
    }
}, myLazyLoad = new LazyLoad({
    container: document.getElementById('content'),
}), alertRun = function() {
    $('.alert').alert();
}, alertMessage = function(type, message) {
    type = typeof type !== 'undefined' ? type : 'success';
    message = typeof message !== 'undefined' ? message : '';
    $('#alert-wrapper').html('<div class=\"alert alert-'+type+' alert-dismissible fade show\" role=\"alert\"><div class=\"container\"><div class=\"col-12\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>'+message+'</div></div></div>');
    alertRun();
};

$(document).ready(function() {
    var $alert = $('.alert'),
        $select2Set = $('.select2-set'),
        $grid = $('.grid');

    if ($alert.length > 0) {
        alertRun();
    }

    $('form.autocomplete .search').autocomplete(autocompleteOption);

    $('form').on('submit', function() {
        var $buttonBtn = $('button.btn', this);

        if ($buttonBtn.length) {
            $buttonBtn.prop('disabled', true);

            setTimeout(function() {
                $buttonBtn.prop('disabled', false);
            }, 2000);
        }
    });

    if ($select2Set.length) {
        $select2Set.each(function() {
            $(this).select2({theme: 'bootstrap'});
        });
    }
});