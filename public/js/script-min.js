if(window.location.hash&&"#_=_"==window.location.hash)if(window.history&&history.pushState)window.history.pushState("",document.title,window.location.pathname);else{var scroll={top:document.body.scrollTop,left:document.body.scrollLeft};window.location.hash="",document.body.scrollTop=scroll.top,document.body.scrollLeft=scroll.left}var alertMessage=function(e,t){e="undefined"!=typeof e?e:"success",t="undefined"!=typeof t?t:"",$("#alert-wrapper").html('<div class="alert alert-'+e+' alert-dismissible fade show" role="alert"><div class="container"><div class="col-12"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+t+"</div></div></div>"),alertRun()},alertRun=function(){$(".alert").alert()};$(document).ready(function(){var e={autoSelectFirst:!0,dataType:"json",deferRequestBy:250,groupBy:"group",minChars:3,serviceUrl:"/search",showNoSuggestionNotice:!0,triggerSelectOnValidInput:!1,type:"POST"},t=$(".alert");t.length>0&&alertRun(),$("form.autocomplete .search").autocomplete(e)});
//# sourceMappingURL=./script-min.js.map