<?php
/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since 1.0.0
 * @package todolist-new 
 *
 * @wordpress-plugin
 * Plugin Name: todolist-new
 * Description: Simple CRUD based plugin to manage your tasks. 
 * Version:     1.0.0
 * Author:      Mikołaj Bukowski
 */

if (!defined('WPINC')) {
    die;
}

class TodoList {

    public function __construct() {
        $this->register_hooks();
    }

    private function register_hooks() {
        // Register hook to add a menu to the admin page as well as ajax scripts 
        add_action('admin_menu', [ $this, 'add_admin_menu' ]);
        add_action('admin_enqueue_scripts', [ $this, 'load_scripts' ]);
        
        add_action( 'wp_ajax_my_action',[$this, 'my_action']);
        
        register_activation_hook( __FILE__, array( 'ToDoListActivate', 'activate' ) );
        register_deactivation_hook( __FILE__, array( 'ToDoListDeactivate', 'deactivate' ) );

        //TODO add_actions responsible for crud operations
    }

    public function my_action() {
        global $wpdb; // this is how you get access to the database
	    $whatever = intval( $_POST['whatever'] );
	    $whatever += 10;
        echo $whatever;
	    wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function load_scripts($hook) {
        if( 'index.php' != $hook ) {
            // Only applies to dashboard panel
            return;
        }
        wp_enqueue_script('ajax-script', plugins_url( '/js/app.js', __FILE__ ), array('jquery'));

        wp_localize_script( 'ajax-script', 'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ));
    }
    
    public function activate_plugin(){
        require_once plugin_dir_path( __FILE__ ) . 'includes/todo-list-activate.php';
        register_activation_hook( __FILE__, array( 'ToDoListActivate', 'activate' ) );

        require_once plugin_dir_path( __FILE__ ) . 'includes/todo-list-deactivate.php';
        register_deactivation_hook( __FILE__, array( 'ToDoListDeactivate', 'deactivate' ) );
    }
    public function display_plugin_page(){
        //Return to display
        require_once 'templates/todolist-plugin-admin.php';
    }

    public function add_admin_menu() {
        add_menu_page(
            'todolist-new',
            'todolist-new',
            'manage_options',
            'todolist-new-slug',
            [ $this, 'display_plugin_page' ],
            'dashicons-smiley',
            4
        );
    }

}
new TodoList();

