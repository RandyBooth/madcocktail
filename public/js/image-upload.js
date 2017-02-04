$(document).ready(function() {
    $('#add-image').on('click', function(e) {
        e.preventDefault();
        $('#image').trigger('click');
    });

    $('#image').on('change', function() {
        $('#form-image').submit();
    });

    $('#form-image').on('submit', function(e) {
        e.preventDefault();
        var file = $('#image').prop('files');

        if (file.length) {
            file = file[0];

            var validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];

            if ($.inArray(file.type, validImageTypes) > 0) {
                // console.log('yes');
                $.ajax({
                    url: '/ajax/recipe-image',
                    type: 'POST',
                    dataType: 'json',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {
                        if (typeof data.success !== 'undefined' && data.success !== null) {
                            if (data.success) {
                                console.log('yes!');
                                console.log(data);
                            }
                        }
                        // $('#loading').hide();
                        // $('#message').html(data);
                    }
                });
            }
        }
    });
});