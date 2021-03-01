<?php
/**
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
        global $wpdb, $table_prefix, $db_name;
        $db_ref = 'todolist_new';
        $db_name = $table_prefix . $db_ref;
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
    
    public function get_tasks(){
		$data = $GLOBALS['wpdb']->get_results("SELECT * FROM {$GLOBALS['db_name']}");
		$tasks = json_encode($data);
		echo $tasks;
		wp_die();
    }
    
    public function add_tasks() { 
		$data_array = array(
			'id'    => $_POST['id'],
			'title' => $_POST['task'],
			'done'  => false,
		);

		$GLOBALS['wpdb']->insert($GLOBALS['db_name'], $data_array);
		wp_die();
    }

    public function edit_tasks(){
		// $data_array = array(
        //     'id'    => $_POST['id'],
        //     'title' => $_POST['title']
        // );

		$task_id = $_POST['id'];
		$text = $_POST['title'];

		$where = array(
			'id' => $task_id
		);

		$data_array = array(
			'task' => $text
		);

		$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data_array, $where);
		wp_die();
    }

    public function remove_tasks(){
        $data_array = array(
            'id'   => $_POST['id']
        );

		$GLOBALS['wpdb']->delete($GLOBALS['db_name'], $data_array);
		wp_die();
    }

    public function check_tasks(){

        $id = $_POST['id'];
		$done = $_POST['done'];
        
		// $where = array(
		// 	'id' => $task_id
		// );
		
		if ( $done == true ) { 
            
			$data_array = array(
				'status' => true
			);

			$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data_array, $id);

		} elseif ( $done == false ) {

			$data_array = array(
				'status' => false
			);

			$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data_array, $id);

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

        if ($GLOBALS['wpdb']->get_var( "SHOW TABLES LIKE '{$GLOBALS['db_name']}'" ) != $GLOBALS['db_name']) 
            {
                $sql = "CREATE TABLE IF NOT EXISTS `" . $GLOBALS['db_name'] . "`  ( ";
                $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
                $sql .= "  `title` text NOT NULL, ";
                $sql .= "  `done` tinyint(1) NOT NULL DEFAULT '0', ";
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