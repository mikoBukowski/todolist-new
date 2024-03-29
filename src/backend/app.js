jQuery(document).ready(function($) {

    getAll();
    var allTasks = $('#tasks-container')[0];
    
    // create
    jQuery('#new_task_form').submit(function(event) { 
        event.preventDefault();
    
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'create',
                task: $('#new_task').val(),
                id: new Date().getUTCMilliseconds(),
            },
            success: function() {
                $('#new_task_form')[0].reset();
                refresh(); 
            },
            error: function() {
                console.log('add error');
            }
        });
    });
    // read
    function getAll() {

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: ({
                action: 'read'
            }),
            success: function(response) {
                let data = JSON.parse(response);
                data.forEach(function(data){

                    const {id, title, done} = data; //destructuring

                    if (done == false) {
                        status = '';
                    } else {
                        status = 'checked';
                    } 
                    
                    let li =
                        `<li class="item list-hover">
                            <label class="item-checkbox">
                                <input class="checkbox" id="${id}" type="checkbox" ${status}>
                            </label>
                            <label class="item-text list-hover" id="${id}" title="${title}"
                            contenteditable="true"> 
                                ${title}
                            </label>
                            <span class="dashicons dashicons-trash trash" id="${id} done="${done}">
                            </span>
                        </li>`;
                    
                    allTasks.innerHTML += li;
                    });
            },
            error: function() {                  
                console.log('get error');
            }
        });
    }
    // update
    jQuery(document).on('keypress', '.item-text', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault(); 

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'update',
                    id: $(this).attr('id'),
                    title: $(this).text().trim(),
                },
                error: function() {
                    console.log('update error');
                }
            })
        }
    });
    // delete
    jQuery(document).on('click', '.trash', function() {

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'delete',
                id: $(this).attr('id'),
            },
            success: function() {
                refresh();
            },
            error: function() {
                console.log('delete error');
            }
        })
    });
    // tick
    jQuery(document).on('click', '.checkbox', function() {
        
        let done = $(this).is(":checked"); //see whether it is checked 
        
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'tick',
                id: $(this).attr('id'),
                done: done 
            },
            success: function(){
                console.log({done});
            },
            error: function() {
                console.log('checkbox error');
            }
        });
    });

    function refresh() {
        allTasks.innerHTML = "";
        getAll();
    }
});