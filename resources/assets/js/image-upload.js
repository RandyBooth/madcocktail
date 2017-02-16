$(document).ready(function() {
    var $image = $('#image'),
        $imagePreview = $('#image-preview', $image),
        $imageEdit = $('#image-edit', $image),
        $imageEditChange = $('#image-edit-change', $imageEdit),
        $imageEditForm = $('form', $imageEdit),
        $imageEditFormFile = $('#image-edit-file', $imageEditForm);

    $imageEditChange.on('click', function(e) {
        e.preventDefault();
        $imageEditFormFile.trigger('click');
    });

    $imageEditFormFile.on('change', function() {
        $imageEditForm.submit();
    });

    $imageEditForm.on('submit', function(e) {
        e.preventDefault();
        var file = $imageEditFormFile.prop('files');

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
                                    $imagePreview.attr('src', data.image);
                                    alertMessage('success', data.message);
                                }
                            } else {
                                if (typeof data.message !== 'undefined' && data.message !== null) {
                                    alertMessage('danger', data.message);
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