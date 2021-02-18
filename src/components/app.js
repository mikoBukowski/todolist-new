import './style.scss';

jQuery(document).ready(function($) {

    var data = {
        'action': 'get_tasks',
        'whatever': 1234
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        alert('Spierdalaj' + response);
    });
});         