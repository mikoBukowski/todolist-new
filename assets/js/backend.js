jQuery(document).ready((function(e){n();var t=e("#tasks-container")[0];function n(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"read"},success:function(e){JSON.parse(e).forEach((function(e){var n=e.id,o=e.title,a=e.done;foo='<li class="item list-hover">\n                                <label class="item-checkbox">\n                                    <input class="checkbox" id="'.concat(n,'" type="checkbox" done="').concat(a,'">\n                                </label>\n                                <label class="item-text list-hover" id="').concat(n,'" title="').concat(o,'"\n                                contenteditable="true"> \n                                     ').concat(o,'\n                                </label>\n                                <span class="dashicons dashicons-trash trash" id="').concat(n,' done="').concat(a,'"></span>\n                            </li>'),t.innerHTML+=foo}))},error:function(){console.log("get error")}})}function o(){t.innerHTML="",n()}jQuery("#new_task_form").submit((function(t){t.preventDefault(),jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"create",task:e("#new_task").val(),id:(new Date).getUTCMilliseconds()},success:function(){e("#new_task_form")[0].reset(),o()},error:function(){console.log("add error")}})})),jQuery(document).on("keypress",".item-text",(function(t){13==t.keyCode&&(t.preventDefault(),jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"update",id:e(this).attr("id"),title:e(this).text().trim()},error:function(){console.log("update error")}}))})),jQuery(document).on("click",".trash",(function(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"delete",id:e(this).attr("id")},success:function(){o()},error:function(){console.log("delete error")}})})),jQuery(document).on("click",".checkbox",(function(){var t=e(this).is(":checked");jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"tick",id:e(this).attr("id"),done:t},success:function(){console.log({done:t})},error:function(){console.log("checkbox error")}})}))}));