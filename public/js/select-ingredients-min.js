$(document).ready(function(){if($("#select-ingredients").length){var e={ajax:{cache:!0,data:function(e){return{query:e.term}},dataType:"json",delay:250,processResults:function(e){return{results:e.suggestions}},type:"POST",url:"/ajax/search/ingredients"},escapeMarkup:function(e){return e},minimumInputLength:3,templateResult:function(e){return e.loading?e.text:"<div>"+e.value+"</div>"},templateSelection:function(e){return e.value||e.text},theme:"bootstrap"},t=$("#select-ingredients"),n=$("#create-ingredients-group",t),s='<option value="">&nbsp;</option>',i=0,l=function(){var t="create-ingredients-div-"+i++;n.append('<div id="'+t+'" class="row mb-4 mb-lg-0 create-ingredients-div"><div class="col-12 mb-2 col-lg-6 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Ingredients</label><select style="width: 100%" class="select-ingredients form-control" name="ingredients[]"><option value="">&nbsp;</option></select></div><div class="col-12 mb-2 col-lg-2 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Quality</label><input class="select-ingredients-measure-amount form-control" type="text" name="ingredients.measure.amount[]" value=""></div><div class="col-12 mb-2 col-lg-3 mb-lg-4 pr-lg-0"><label class="hidden-lg-up">Unit</label>'+s+'</div><div class="col-12 mb-2 col-lg-1 mb-lg-4 align-self-end"><button type="button" class="btn btn-gray create-ingredients-close"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>'),$("#"+t+" .select-ingredients").select2(e),$("#"+t+" .select-ingredients-measure").select2({theme:"bootstrap"})};$(document).on("click",".create-ingredients-close",function(e){e.preventDefault(),$(this).closest(".create-ingredients-div").remove()}),$(".select-ingredients").each(function(){$(this).select2(e)}),$(".select-ingredients-measure").each(function(){$(this).select2({theme:"bootstrap"})}),"undefined"!=typeof drinkMeasures&&null!==drinkMeasures&&($.isEmptyObject(drinkMeasures)||$.each(drinkMeasures,function(e,t){s+="<option value="+e+">"+t+"</option>"})),s='<select style="width: 100%" class="select-ingredients-measure form-control" name="ingredients.measure[]">'+s+"</select>",$(document).on("click","#create-ingredients-button",function(e){e.preventDefault(),l()})}});
//# sourceMappingURL=./select-ingredients-min.js.map