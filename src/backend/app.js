const { id } = require("postcss-selector-parser");

jQuery(document).ready(function($) {

    get_tasks();
    var tasks_container = $('#tasks-container')[0]; // Get tasks container element.

    function get_tasks() {

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: ({
                action: 'get_tasks'
            }),
            success: function(response) {
                    let data = JSON.parse(response);
                    data.forEach(function(data){

                        const {id, title, done} = data; 
                        foo =
                            `<li class="item list-hover">
                                <label class="item-checkbox">
                                    <input class="checkbox" id="${id}" type="checkbox" done="${done}">
                                </label>
                                <label class="item-text list-hover" id="${id}" title="${title}" 
                                    contenteditable="true"> ${title}
                                </label>
                                <span class="dashicons dashicons-trash trash" id="${id}"></span>
                            </li>`;
                        
                        tasks_container.innerHTML += foo;
                    });
            },
            error: function() {                  
                    console.log('get error');
            }
        });
    }

    function refresh() {
        tasks_container.innerHTML = ""; // empty the container before displaying tasks.
        get_tasks();
    }
    // add
    jQuery('#new_task_form').submit(function(event) { 
        event.preventDefault();

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'add_tasks',
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

    // check
    jQuery(document).on('click', '.checkbox', function() {

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'check_tasks',
                id: $(this).attr('id'),
                done: $(this).attr('done')
            },
            error: function() {
                    console.log('checkbox error');
            }
        });
    });

    // edit 
    jQuery(document).on('keypress', '.item-text', function(event) {
        var task_id = $(this).attr('id');
        var text = $('#' + task_id)[0].textContent;
        
        if (event.keyCode == 13) { // Key 13 is Enter.
            event.preventDefault(); // Prevent new line.

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'edit_tasks',
                    id: task_id,
                    title: text,
                },
                error: function() {
                        console.log('edit error');
                }
            })
        }
    });
    // remove
    jQuery(document).on('click', '.trash', function() {

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'remove_tasks',
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
});