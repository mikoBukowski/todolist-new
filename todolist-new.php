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
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_scripts']);
        
        add_action('wp_ajax_get_tasks', [$this, 'get_tasks']);
        add_action('wp_ajax_add_tasks', [$this, 'add_tasks']);
        add_action('wp_ajax_edit_tasks', [$this, 'edit_tasks']);
        add_action('wp_ajax_remove_tasks', [$this, 'remove_tasks']);
        add_action('wp_ajax_check_tasks', [$this, 'check_tasks']);
        
        register_activation_hook(__FILE__, [$this, 'activate_plugin']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate_plugin']);
    }

    public function get_tasks() {
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

        //TO BE ALTERED
        // wp_enqueue_script('ajax-script', plugins_url( 'assets/js/app.js', __FILE__ ), array('jquery'));
        // //TO BE ALTERED
        // wp_localize_script( 'ajax-script', 'ajax_object',
        // array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ));
    }

    public function display_plugin_page(){
        //Return to display
        require_once 'src/components/template.php';
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

    public function activate_plugin(){
        flush_rewrite_rules();

        global $table_prefix, $wpdb;
        $table_name = 'todolist_new';
        $dbtable_name = $table_prefix . $table_name;
    
        if ($wpdb->get_var( "SHOW TABLES LIKE '{$dbtable_name}'" ) != $dbtable_name) 
            {
                $sql = "CREATE TABLE IF NOT EXISTS `" . $dbtable_name . "`  ( ";
                $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
                $sql .= "  `created_user_id` int(11) NOT NULL, ";
                $sql .= "  `task` text NOT NULL, ";
                $sql .= "  `status` tinyint(1) NOT NULL DEFAULT '0', ";
                $sql .= "  `priority` int(11) NOT NULL DEFAULT '0', ";
                $sql .= "  PRIMARY KEY `id` (`id`) ";
                $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";
                require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
            }
    }       

    public function deactivate_plugin(){
        flush_rewrite_rules();
    }
}
new TodoList();

