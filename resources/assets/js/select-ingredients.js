$(document).ready(function() {
    if ($('#select-ingredients').length) {
        var select2AjaxOption =
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
                    url: '/ajax/search/ingredients',
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
                theme: 'bootstrap',
            },
            $selectIngredients = $('#select-ingredients'),
            $selectIngredientsGroup = $('#create-ingredients-group', $selectIngredients),
            measuresList = '<option value="">&nbsp;</option>',
            select2Count = 0
        ;

        var select2Insert = function () {
            var selectIngredientsDivCount = 'create-ingredients-div-'+(select2Count++);

            $selectIngredientsGroup.append('<div id="'+selectIngredientsDivCount+'" class="row mb-4 mb-lg-0 create-ingredients-div"><div class="col-12 mb-2 col-lg-6 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Ingredients</label><select style="width: 100%" class="select-ingredients form-control" name="ingredients[]"><option value="">&nbsp;</option></select></div><div class="col-12 mb-2 col-lg-2 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Quality</label><input class="select-ingredients-measure-amount form-control" type="text" name="ingredients.measure.amount[]" value=""></div><div class="col-12 mb-2 col-lg-3 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Unit</label>'+measuresList+'</div><div class="col-12 mb-2 col-lg-1 mb-lg-4 align-self-end"><button type="button" class="btn btn-gray create-ingredients-close"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>');
            $('#'+selectIngredientsDivCount+' .select-ingredients').select2(select2AjaxOption);
            $('#'+selectIngredientsDivCount+' .select-ingredients-measure').select2({theme: 'bootstrap'});
        };

        $(document).on('click', '.create-ingredients-close', function (e) {
            e.preventDefault();
            $(this).closest('.create-ingredients-div').remove();
        });

        $('.select-ingredients').each(function() {
            $(this).select2(select2AjaxOption);
        });

        $('.select-ingredients-measure').each(function() {
            $(this).select2({theme: 'bootstrap'});
        });

        if (typeof drinkMeasures !== 'undefined' && drinkMeasures !== null) {
            if (!$.isEmptyObject(drinkMeasures)) {
                $.each(drinkMeasures, function(key, value){
                    measuresList += '<option value=' + key + '>' + value + '</option>';
                });
            }
        }

        measuresList = '<select style="width: 100%" class="select-ingredients-measure form-control" name="ingredients.measure[]">'+measuresList+'</select>';

        $(document).on('click', '#create-ingredients-button', function(e) {
            e.preventDefault();
            select2Insert();
        });
    }
});