jQuery(document).ready((function(t){e();var a=t("#tasks-container")[0];function e(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"get_tasks"},success:function(t){JSON.parse(t).forEach((function(t){if(1==t.status)var e="checked";else e="";var r='<li class="item list-hover"><label class="item-checkbox" style="padding-right: 4px;"><input class="checkbox" id="'+t.id+'" type="checkbox" '+e+'></label><label class="item-text list-hover" id="task-'+t.id+'" contenteditable="true">'+t.task+'</label><span class="dashicons dashicons-trash trash" id="trash-'+t.id+'"></span></li>';a.innerHTML+=r}))},error:function(){console.log("AJAX error getting tasks.")}})}function r(){a.innerHTML="",e()}jQuery("#new_task_form").submit((function(a){a.preventDefault(),jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"add_task",task:t("#new_task").val()},success:function(){r()},error:function(){console.log("Error addding task.")}}),t("#new_task_form")[0].reset()})),jQuery(document).on("click",".checkbox",(function(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"mark_task",task_id:t(this).attr("id"),checked:t(this).attr("checked")},error:function(){console.log("Error updating task status.")}})})),jQuery(document).on("keypress",".item-text",(function(a){var e=t(this).attr("id"),r=t("#"+e)[0].textContent;13==a.keyCode&&(a.preventDefault(),jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"edit_task",task_id:e,text:r},error:function(){console.log("Error editing task.")}}))})),jQuery(document).on("click",".trash",(function(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"delete_task",task_id:t(this).attr("id")},success:function(){r()},error:function(){console.log("Error deleting task.")}})}))}));