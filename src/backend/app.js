jQuery(document).ready(function($) {

    get_tasks();
    var tasks_container = $('#tasks-container')[0]; // Get tasks container element.

    // Get tasks.
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
                                    <input class="checkbox" id="${id}" type="checkbox" ${done}>
                                </label>
                                <label class="item-text list-hover" id="task-${id}" 
                                    contenteditable="true"> ${title}
                                </label>
                                <span class="dashicons dashicons-trash trash" id="trash-'${id}"></span>
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

    // Add new task.
    jQuery('#new_task_form').submit(function(event) { // trigger on submit.
        event.preventDefault();
        let id = new Date().getUTCMilliseconds();

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'add_tasks',
                task: $('#new_task').val(),
                id: id, 
            },
            success: function() {
                    refresh(); // refresh on addition
                    // console.log(taskName);
            },
            error: function() {
                    console.log('add error');
            }
        });
        $('#new_task_form')[0].reset(); // clear form input // moze przenies LATER
    });

    // change task status (mark as done or not).
    jQuery(document).on('click', '.checkbox', function() { // trigger on click.

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mark_task',
                task_id: $(this).attr('id'),
                checked: $(this).attr('checked')
            },
            error: function() {
                    console.log('checkbox error');
            }
        });
    });

    // Edit task.
    jQuery(document).on('keypress', '.item-text', function(event) { // Trigger on pressing the key.
        
        var task_id = $(this).attr('id');
        var text = $('#' + task_id)[0].textContent; // Get text.

        if (event.keyCode == 13) { // Key 13 is Enter.
            event.preventDefault(); // Prevent new line.

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'edit_task',
                    task_id: task_id,
                    text: text,
                },
                error: function() {
                        console.log('edit error');
                }
            })
        }
    });
    // Delete task.
    jQuery(document).on('click', '.trash', function() { // Trigger on click.

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_task',
                task_id: $(this).attr('id')
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