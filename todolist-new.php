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
        // Register hook to add a menu to the admin page as well as vue scripts
        add_action('admin_menu', [ $this, 'add_admin_menu' ]);
        add_action('admin_enqueue_scripts', [ $this, 'load_scripts' ]);
    }

    public function load_scripts() {
        // $vueDirectory    = join( DIRECTORY_SEPARATOR, [ plugin_dir_url(__FILE__), 'vue', 'dist' ] );
        // wp_register_style( 'backend-vue-style', $vueDirectory . '/app.css' );
        // wp_register_script( 'backend-vue-script', $vueDirectory . '/app.js', [], '1.0.0', true );
    }

    public function display_plugin_page(){
        //Add Vue.js
        // wp_enqueue_style( 'backend-vue-style' );
        // wp_enqueue_script( 'backend-vue-script' );

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

