var $formSearch=$("#form-search"),$formSearchId=$("#search-id",$formSearch),$formSearchGroup=$("#search-group",$formSearch),autocompleteOption={autoSelectFirst:!0,dataType:"json",deferRequestBy:250,groupBy:"group",minChars:3,serviceUrl:"/ajax/search",showNoSuggestionNotice:!0,triggerSelectOnValidInput:!1,type:"POST",onSearchStart:function(){$formSearchId.val(""),$formSearchGroup.val(""),$formSearch.attr("onsubmit","return false;")},onSelect:function(e){if(console.log(e),"undefined"!=typeof e.id&&null!==e.id&&"undefined"!=typeof e.data.group&&null!==e.data.group){var t=$("#form-search"),a=$("#search-id",t),r=$("#search-group",t);a.val(e.id),r.val(e.data.group),t.attr("onsubmit","").submit()}}},myLazyLoad=new LazyLoad({container:document.getElementById("content"),skip_invisible:!1}),alertRun=function(){$(".alert").alert()},alertMessage=function(e,t){e="undefined"!=typeof e?e:"success",t="undefined"!=typeof t?t:"",$("#alert-wrapper").html('<div class="alert alert-'+e+' alert-dismissible fade show" role="alert"><div class="container"><div class="col-12"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+t+"</div></div></div>"),alertRun()};$(document).ready(function(){var e=$(".social-media"),t=$(".alert"),a=$(".select2-set");if(e.length){var r=$("a",e);e.matchHeight({property:"max-height",target:r}),r.on("click",function(e){var t=$(this),a=t.data()-a||800,r=t.data()-r||500,o=Math.floor(((screen.availWidth||1024)-a)/2),n=Math.floor(((screen.availHeight||700)-r)/2),i=window.open(t.attr("href"),"social","width="+a+",height="+r+",left="+o+",top="+n+",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");return i&&(i.focus(),e.preventDefault(),e.returnValue=!1),!!i})}t.length&&alertRun(),$("form.autocomplete .search").autocomplete(autocompleteOption),$("form").on("submit",function(){var e=$("button.btn",this);e.length&&(e.prop("disabled",!0),setTimeout(function(){e.prop("disabled",!1)},2e3))}),a.length&&a.each(function(){$(this).select2({theme:"bootstrap"})})});
//# sourceMappingURL=./script-min.js.map