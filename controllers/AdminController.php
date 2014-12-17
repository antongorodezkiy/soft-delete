<?php if (!defined('WPINC')) die();

class SoftDelete_AdminController {
	
	// show message
		public static function showMessage($message, $errormsg = false) {
			
			if (!session_id()) {
				session_start();
			}
			
			if (!isset($_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'])) {
				$_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'] = array();
			}
			
			$_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'][] = array(
				'text' => $message,
				'error' => $errormsg
			);
		}
		
		public static function showDirectMessage($message, $errormsg = false) {
			
			if ($errormsg) {
				$css_class = 'error';
			}
			else {
				$css_class = 'updated';
			}
			
			echo '<div class="'.$css_class.'"><p>'.$message.'</p></div>';
		}

		public static function showAdminNotifications() {
			if (!session_id()) {
				session_start();
			}
			
			if (isset($_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'])) {
				foreach($_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'] as $key => $notice) {
					
					if ($notice['error']) {
						$css_class = 'error';
					}
					else {
						$css_class = 'updated';
					}
					
					echo '<div class="'.$css_class.'"><p>'.$notice['text'].'</p></div>';
				}
				$_SESSION[SOFT_DELETE_PLUGIN.'admin_notice'] = array();
			}
		}
	
	// settings
		public static function registerMenuPage() {
			add_options_page(
				'Soft Delete',
				'Soft Delete',
				'manage_options',
				SOFT_DELETE_PLUGIN,
				array('SoftDelete_AdminController','showSettings')
			);
		}
	
		public static function showSettings() {
			global $wp_roles;
			
			$roles = $wp_roles->role_names;
			$post_types = get_post_types();
			$requirements = SoftDelete_Plugin::requirements();
			
			include_once(SOFT_DELETE_APPPATH.'/views/settings.php');
		}
		
		
		public static function settingsInit() {
			register_setting(SOFT_DELETE_PLUGIN, SOFT_DELETE_PLUGIN);
		}

		public static function getSettings() {
			$config = array();
			
			$config['enable_logging'] = 1;
			
			$config['types_allowed_for_soft_delete'] = array();
			$config['types_allowed_for_hard_delete'] = get_post_types();
			
			$config['roles_allowed_to_soft_delete'] = array("administrator");
			$config['roles_allowed_to_hard_delete'] = array("administrator");
			
			$config['soft_deleted_status'] = "_deleted";
			
			return wp_parse_args(get_option(SOFT_DELETE_PLUGIN),$config);
		}
		
		public static $cached_settings = null;
		public static function getSetting($name) {
			if (self::$cached_settings == null) {
				self::$cached_settings = self::getSettings();
			}
			
			if (isset(self::$cached_settings[$name])) {
				return self::$cached_settings[$name];
			}
			else {
				return null;
			}
		}
	
}
