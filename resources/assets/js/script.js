var $formSearch = $('#form-search'),
    $formSearchId = $('#search-id', $formSearch),
    $formSearchGroup = $('#search-group', $formSearch),
    autocompleteOption = {
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
        $formSearchId.val('');
        $formSearchGroup.val(''),
        $formSearch.attr('onsubmit', 'return false;');
    },
    onSelect: function (suggestion) {
        if ((typeof suggestion.id !== 'undefined' && suggestion.id !== null) && (typeof suggestion.data.group !== 'undefined' && suggestion.data.group !== null)) {
            var $formSearch = $('#form-search'),
                $formSearchId = $('#search-id', $formSearch),
                $formSearchGroup = $('#search-group', $formSearch);

            $formSearchId.val(suggestion.id);
            $formSearchGroup.val(suggestion.data.group);
            $formSearch.attr('onsubmit', '').submit();
        }
    }
}, myLazyLoad = new LazyLoad({
}), alertRun = function() {
    $('.alert').alert();
}, alertMessage = function(type, message) {
    type = typeof type !== 'undefined' ? type : 'success';
    message = typeof message !== 'undefined' ? message : '';
    $('#alert-wrapper').html('<div class=\"alert alert-'+type+' alert-dismissible fade show\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>'+message+'</div>');
    alertRun();
};

$(document).ready(function() {
    var $socialMedia = $('.social-media'),
        $alert = $('.alert'),
        $select2Set = $('.select2-set');

    if ($socialMedia.length) {
        var $socialMediaLinks = $('a', $socialMedia);

        $socialMedia.matchHeight({
            property: 'max-height',
            target: $socialMediaLinks
        });

        $socialMediaLinks.on('click', function (e) {
            var $self = $(this),
                width = $self.data() - width || 800,
                height = $self.data() - height || 500;

            var
                px = Math.floor(((screen.availWidth || 1024) - width) / 2),
                py = Math.floor(((screen.availHeight || 700) - height) / 2);

            var popup = window.open($self.attr('href'), "social",
                "width=" + width + ",height=" + height +
                ",left=" + px + ",top=" + py +
                ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");

            if (popup) {
                popup.focus();
                e.preventDefault();
                e.returnValue = false;
            }

            return !!popup;
        });
    }

    if ($alert.length) {
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

    $('form.login-recipe-favorite').on('click', function(e) {
        e.preventDefault();
        var $self_button = $('.recipe-favorite-btn', this);
        $self_button.addClass('recipe-favorite-btn-on')

        $.ajax({
            url: '/ajax/recipe-favorite',
            type: 'POST',
            dataType: 'json',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (typeof data.success !== 'undefined' && data.success !== null) {
                    if (data.success) {
                        if (typeof data.on !== 'undefined') {
                            if (!data.on) {
                                $self_button.removeClass('recipe-favorite-btn-on')
                            }
                        }
                    } else {
                        if (typeof data.message !== 'undefined' && data.message !== null) {
                            alertMessage('danger', data.message);
                        }
                    }
                }
            }
        });
    });

    // $('.recipe-favorite-btn').hover(
    //     function() {
    //         $('i', this).addClass('fa-heart').removeClass('fa-heart-o');
    //     },
    //     function() {
    //         $('i', this).addClass('fa-heart-o').removeClass('fa-heart');
    //     }
    // );

    if ($select2Set.length) {
        $select2Set.each(function() {
            $(this).select2({theme: 'bootstrap'});
        });
    }
});