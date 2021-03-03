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
        
        add_action('wp_ajax_create', [$this, 'create']);
        add_action('wp_ajax_read', [$this, 'read']);
        add_action('wp_ajax_update', [$this, 'update']);
        add_action('wp_ajax_delete', [$this, 'delete']);
        add_action('wp_ajax_tick', [$this, 'tick']);
        
        register_activation_hook(__FILE__, [$this, 'activate_plugin']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate_plugin']);
    }
    
    public function create() { 
		$data = array(
			'id'    => $_POST['id'],
			'title' => $_POST['task'],
			'done'  => false,
		);

		$GLOBALS['wpdb']->insert($GLOBALS['db_name'], $data);
		wp_die();
    }

    public function read(){
		$data = $GLOBALS['wpdb']->get_results("SELECT * FROM {$GLOBALS['db_name']}");
		$tasks = json_encode($data);
		echo $tasks;
		wp_die();
    }
    
    public function update(){
		$where = array(
			'id' => $_POST['id']
		);

		$data = array(
			'title' => $_POST['title']
		);

		$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data, $where);
		wp_die();
    }

    public function delete(){
        $data = array(
            'id'   => $_POST['id']
        );

		$GLOBALS['wpdb']->delete($GLOBALS['db_name'], $data);
		wp_die();
    }

    public function tick(){
		$where = array(
			'id' => $_POST['id']
		);

        $data = array(
            'done' => $_POST['done']
        );

		if ( $done == true ) { 
			$data_array = array(
				'status' => true
			);

			$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data_array, $id);

		} elseif ( $done == false ) {
			$data_array = array(
				'status' => false
			);

			$GLOBALS['wpdb']->update($GLOBALS['db_name'], $data, $where);
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

    public function deactivate_plugin() {
        flush_rewrite_rules();
    }
}
new TodoList();