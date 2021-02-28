jQuery(document).ready((function(t){a();var e=t("#tasks-container")[0];function a(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"get_tasks"},success:function(t){var a=JSON.parse(t);console.table({tasks:a}),a.forEach((function(t){if(1==t.status)var a="checked";else a="";var n='<li class="item list-hover"><label class="item-checkbox" style="padding-right: 4px;"><input class="checkbox" id="'+t.id+'" type="checkbox" '+a+'></label><label class="item-text list-hover" id="task-'+t.id+'" contenteditable="true">'+t.task+'</label><span class="dashicons dashicons-trash trash" id="trash-'+t.id+'"></span></li>';e.innerHTML+=n}))},error:function(){console.log("CZEKIT"),console.log("AJAX error getting tasks.")}})}function n(){e.innerHTML="",a()}jQuery("#new_task_form").submit((function(e){e.preventDefault();var a=document.getElementById("new_task").value;jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"add_tasks",task:t("#new_task").val()},success:function(){n(),console.log(a),t("#new_task_form")[0].reset()},error:function(){console.log("Error addding task.")}})})),jQuery(document).on("click",".checkbox",(function(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"mark_task",task_id:t(this).attr("id"),checked:t(this).attr("checked")},error:function(){console.log("Error updating task status.")}})})),jQuery(document).on("keypress",".item-text",(function(e){var a=t(this).attr("id"),n=t("#"+a)[0].textContent;13==e.keyCode&&(e.preventDefault(),jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"edit_task",task_id:a,text:n},error:function(){console.log("Error editing task.")}}))})),jQuery(document).on("click",".trash",(function(){jQuery.ajax({url:ajaxurl,type:"POST",data:{action:"delete_task",task_id:t(this).attr("id")},success:function(){n()},error:function(){console.log("Error deleting task.")}})}))}));