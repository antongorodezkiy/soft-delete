<?php
/*
	Plugin Name: WordPress Soft Delete Plugin
	Plugin URI: http://soft-delete.wp.teamlead.pw/
	Description: Plugin allows you to prevent deletion of posts or custom posts of chosen post type, and instead just mark them as "_deleted"
	Version: 1.0.0
	Author: E.Kozachek [TeamleadPower]
	Author URI: http://teamlead.pw/
	License: BSD
	Text Domain: soft-delete
*/

if (!defined('WPINC')) die();

define('SOFT_DELETE_PLUGIN','soft-delete');
define('SOFT_DELETE_APPPATH',dirname(__FILE__));
define('SOFT_DELETE_FILE',__FILE__);

if (!class_exists('SoftDelete_Plugin')) {
	include_once(SOFT_DELETE_APPPATH.'/controllers/Plugin.php');
}

// initialization
	register_activation_hook(__FILE__, array('SoftDelete_Plugin','activation'));
	
// plugin actions
	add_filter('plugin_action_links', array('SoftDelete_Plugin','registerPluginActions'), 10, 2);
	
function wp_soft_delete_init() {

	if (!class_exists('SoftDelete_Model')) {
		include_once(SOFT_DELETE_APPPATH.'/models/Model.php');
	}
	
	if (!class_exists('SoftDelete_AssetsController')) {
		include_once(SOFT_DELETE_APPPATH.'/controllers/AssetsController.php');
	}
	
	if (!class_exists('SoftDelete_AdminController')) {
		include_once(SOFT_DELETE_APPPATH.'/controllers/AdminController.php');
	}

	// assets
		if (is_admin()) {
			add_action('admin_enqueue_scripts', array('SoftDelete_AssetsController', 'admin_enqueue_scripts'));
		}

	//ADMIN
	if (is_admin() && (current_user_can('edit_posts') || current_user_can('edit_pages'))) {
		
		// settings init
			add_action('admin_init', array('SoftDelete_AdminController','settingsInit'));
			
		// admin page
			add_action( 'admin_menu', array('SoftDelete_AdminController','registerMenuPage'));
			
	}
}
add_action('after_setup_theme','wp_soft_delete_init');
