$(document).ready(function() {
    if ($('#select-ingredients').length) {
        if (typeof drinkMeasures !== 'undefined' && drinkMeasures !== null) {
            if (!$.isEmptyObject(drinkMeasures)) {
                var select2Option =
                    {
                        ajax: {
                            cache: true,
                            data: function (params) {
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
                        templateResult: function  (data) {
                            if (data.loading) {
                                return data.text;
                            }

                            return '<div>'+data.value+'</div>';
                        },
                        templateSelection: function (data) { return data.value || data.text; },
                    },
                    $selectIngredients = $('#select-ingredients'),
                    $selectIngredientsGroup = $('#create-ingredients-group', $selectIngredients),
                    measuresList = '<option value="">--</option>',
                    select2Count = 0
                ;

                var select2Insert = function () {
                    var selectIngredientsDivCount = 'create-ingredients-div-'+(select2Count++),
                    $selectIngredientsHTML = $('<select class="search-select select-ingredients" name="ingredients[]" style="width: 100%"><option value="">--</option></select>');

                    $selectIngredientsGroup.append('<div id="'+selectIngredientsDivCount+'" class="create-ingredients-div"></div>');
                    $('#'+selectIngredientsDivCount).append('<a class="create-ingredients-close" href="">X</a>').append($selectIngredientsHTML).append(measuresList);
                    $selectIngredientsHTML.select2(select2Option);
                };

                $(document).on('click', '.create-ingredients-close', function (e) {
                    e.preventDefault();
                    $(this).closest('.create-ingredients-div').remove();
                });

                $('.search-select').each(function() {
                    $(this).select2(select2Option);
                });

                $.each(drinkMeasures, function(key, value){
                    measuresList += '<option value=' + key + '>' + value + '</option>';
                });

                measuresList = '<select class="select-ingredients-measure" name="ingredients.measure[]">'+measuresList+'</select><input class="select-ingredients-measure-amount" type="text" name="ingredients.measure.amount[]">';

                $(document).on('click', '#create-ingredients-button', function(e) {
                    e.preventDefault();
                    select2Insert();
                });
            }
        }
    }
});