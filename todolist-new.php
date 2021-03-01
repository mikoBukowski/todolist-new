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
        // $this->activate_plugin(); do wywołania przy uruchomieniu ?
    }

    private function register_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'frontend_script']);
		add_action('admin_enqueue_scripts', [$this, 'backend_script']);

        add_action('wp_ajax_get_tasks', [$this, 'get_tasks']);
        add_action('wp_ajax_add_tasks', [$this, 'add_tasks']);
        add_action('wp_ajax_edit_tasks', [$this, 'edit_tasks']);
        add_action('wp_ajax_remove_tasks', [$this, 'remove_tasks']);
        add_action('wp_ajax_check_tasks', [$this, 'check_tasks']);
        
        register_activation_hook(__FILE__, [$this, 'activate_plugin']);
        // register_deactivation_hook(__FILE__, [$this, 'deactivate_plugin']); // pytanie czy to
    }
    //DONE
    public function get_tasks(){
        global $table_prefix, $wpdb;
		$tablename = 'todolist_new';
		$todo_list_table = $table_prefix . $tablename;

		// $id = get_current_user_id();

		$tasks = $wpdb->get_results( "SELECT * FROM {$todo_list_table} " );
		$tasks = json_encode($tasks);

		echo $tasks;

		wp_die();
    }
    //DONE
    public function add_tasks() { 
        global $table_prefix, $wpdb;
		$tablename = 'todolist_new';
		$todo_list_table = $table_prefix . $tablename;

		$data_array = array(
			'id'    => $_POST['id'],
			'title' => $_POST['task'],
			'done'  => '0',
		);

		$wpdb->insert($todo_list_table, $data_array);
		wp_die();
    }

    public function edit_tasks(){
        global $table_prefix, $wpdb;
		$tablename = 'todolist_new';
		$todo_list_table = $table_prefix . $tablename;

		$task_id = substr( $_POST['task_id'], 5 ); // Select only ID of the task in database.
		$text = $_POST['text'];

		$where = array(
			'id' => $task_id
		);

		$data_array = array(
			'task' => $text
		);

		$wpdb->update( $todo_list_table, $data_array, $where );

		wp_die();
    }

    public function remove_tasks(){
        global $table_prefix, $wpdb;
		$tablename = 'todolist_new';
		$todo_list_table = $table_prefix . $tablename;

		$task_id = substr( $_POST['task_id'], 6 ); // Select only ID of the task in database.

		$wpdb->delete( $todo_list_table, array( 'id' => $task_id ) );

		wp_die();
    }

    public function check_tasks(){
        global $table_prefix, $wpdb;
		$tablename = 'todolist_new';
		$todo_list_table = $table_prefix . $tablename;

		$task_id = $_POST['task_id'];
		$checked = $_POST['checked'];

		$where = array(
			'id' => $task_id
		);
		
		if ( $checked == 'checked' ) { // Checked.

			$data_array = array(
				'status' => '1'
			);

			$wpdb->update( $todo_list_table, $data_array, $where );

		} elseif ( $checked != 'checked' ) { // Unchecked.

			$data_array = array(
				'status' => '0'
			);

			$wpdb->update( $todo_list_table, $data_array, $where );

		}
		
		wp_die();
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
    
    public function frontend_script() {
        wp_register_script( 'frontend-script', plugins_url( 'assets/js/frontend.js', __FILE__ ), [ 'jquery' ], '11272018' );
		wp_enqueue_script( 'frontend-script' );
    }

    public function backend_script() {
        wp_register_script( 'backend-script', plugins_url( 'assets/js/backend.js', __FILE__ ), [ 'jquery' ], '11272018' );
		wp_enqueue_script( 'backend-script' );
    }

    public function display_plugin_page() {
        require_once 'src/frontend/template.php';
    }

    public function activate_plugin() {
        flush_rewrite_rules();

        global $table_prefix, $wpdb;
        $table_name = 'todolist_new';
        $dbtable_name = $table_prefix . $table_name;
    
        if ($wpdb->get_var( "SHOW TABLES LIKE '{$dbtable_name}'" ) != $dbtable_name) 
            {
                $sql = "CREATE TABLE IF NOT EXISTS `" . $dbtable_name . "`  ( ";
                $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
                // $sql .= "  `created_user_id` int(11) NOT NULL, ";
                $sql .= "  `title` text NOT NULL, ";
                $sql .= "  `done` tinyint(1) NOT NULL DEFAULT '0', ";
                // $sql .= "  `priority` int(11) NOT NULL DEFAULT '0', ";
                $sql .= "  PRIMARY KEY `id` (`id`) ";
                $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";
                require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
            }
    }       

    // public function deactivate_plugin() {
    //     flush_rewrite_rules();
    // }
}
new TodoList();

