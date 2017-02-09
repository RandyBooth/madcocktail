$(document).ready(function() {
    $('#add-image').on('click', function(e) {
        e.preventDefault();
        $('#upload-image').trigger('click');
    });

    $('#upload-image').on('change', function() {
        $('#form-image').submit();
    });

    $('#form-image').on('submit', function(e) {
        e.preventDefault();
        var file = $('#upload-image').prop('files');

        if (file.length) {
            file = file[0];

            var validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];

            if ($.inArray(file.type, validImageTypes) > 0) {
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
                                if (typeof data.image !== 'undefined' && data.image !== null) {
                                    $('#image #preview').attr('src', data.image);
                                    alertMessage('success', data.message);
                                    alertRun();
                                }
                            } else {
                                if (typeof data.message !== 'undefined' && data.message !== null) {
                                    alertMessage('warning', data.message);
                                }
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