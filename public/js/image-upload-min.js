$(document).ready(function(){var e=$("#image"),a=$("#image-preview",e),i=$("#image-edit",e),n=$("#image-edit-change",i),s=$("form",i),t=$("#image-edit-file",s);n.on("click",function(e){e.preventDefault(),t.trigger("click")}),t.on("change",function(){s.submit()}),s.on("submit",function(e){e.preventDefault();var i=t.prop("files");if(i.length){i=i[0];var n=["image/gif","image/jpeg","image/jpg","image/png"];$.inArray(i.type,n)>0&&$.ajax({url:"/ajax/recipe-image",type:"POST",dataType:"json",data:new FormData(this),contentType:!1,cache:!1,processData:!1,success:function(e){"undefined"!=typeof e.success&&null!==e.success&&(e.success?"undefined"!=typeof e.image&&null!==e.image&&(a.attr("src",e.image),alertMessage("success",e.message)):"undefined"!=typeof e.message&&null!==e.message&&alertMessage("danger",e.message))}})}})});
//# sourceMappingURL=./image-upload-min.js.map