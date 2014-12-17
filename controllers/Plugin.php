<?php

class SoftDelete_Plugin {

	
	public static function activation() {

	}
	
	public static function initEarlyActions() {
		
	}

	// plugin actions
		public static function registerPluginActions($links, $file) {
			if (stristr($file, SOFT_DELETE_PLUGIN)) {
				$settings_link = '<a href="options-general.php?page='.SOFT_DELETE_PLUGIN.'">' . __('Settings', SOFT_DELETE_PLUGIN) . '</a>';
				$links = array_merge(array($settings_link), $links);
			}
			return $links;
		}
				
		
	public static function getActivePlugins() {
		$apl = get_option('active_plugins');
		$plugins = get_plugins();
		$activated_plugins = array();
		foreach($apl as $p) {           
			if(isset($plugins[$p])) {
				array_push($activated_plugins, $plugins[$p]);
			}           
		}
		
		return $activated_plugins;
	}
	
	public static function serverInfo() {
		global $wp_version, $wpdb;
		
		$mysql = $wpdb->get_row("SHOW VARIABLES LIKE 'version'");
		
		$info = array(
			'os' => php_uname(),
			'php' => phpversion(),
			'mysql' => $mysql->Value,
			'wordpress' => $wp_version
		);
		
		return $info;
	}
	
	// plugin requirements
		public static function requirements($boolean = false) {
			$upload_dir_message = __('Logs folder',SOFT_DELETE_PLUGIN).': <code>'.self::getLogsPath().'</code>';
			
			$requirements = array(
				array(
					'name' => $upload_dir_message,
					'status' => self::createLogsDirectory(),
					'success' => __('is writable',SOFT_DELETE_PLUGIN),
					'fail' => __('is not writable',SOFT_DELETE_PLUGIN)
				)
			);
			
			if ($boolean) {
				$status = true;
				foreach($requirements as $requirement) {
					$status = $status && $requirement['status'];
				}
				return $status;
			}
			else {
				return $requirements;
			}
		}
	
	public static function getDocsUrl() {
		if (file_exists(SOFT_DELETE_APPPATH.'/documentation/index_'.WPLANG.'.html')) {
			$documentation_url = 'documentation/index'.WPLANG.'.html';
		}
		else {
			$documentation_url = 'documentation/index.html';
		}
		$documentation_url = plugins_url($documentation_url, SOFT_DELETE_FILE);
		return $documentation_url;
	}
	
	public static function getSettingsUrl() {
		return admin_url('options-general.php?page='.SOFT_DELETE_PLUGIN);
	}
	
	public static function getLogsPath() {
		return WP_CONTENT_DIR.'/'.SOFT_DELETE_PLUGIN.'-logs/';
	}
	
	public static function createLogsDirectory() {
		$log_path = self::getLogsPath();
		if ( ! file_exists($log_path)) {
			mkdir($log_path);
		}
		
		return file_exists($log_path);
	}
	
	public static function log($label, $msg) {
		
		if (is_array($msg) || is_object($msg)) {
			$msg = print_r($msg,true);
		}
			
		$log_path = self::getLogsPath();
		
		if ( ! file_exists($log_path)) {
			mkdir($log_path);
		}
		
		$filename = date('Y-m-d').'.php';
		$filepath = $log_path.$filename;
			
		/*$messages = explode("\n",$msg);
		
		foreach($messages as $k => $m) {
			$messages[$k] = substr($m,0,2000);
		}
		$msg = implode("\n",$messages);*/
		
		$message = '';

		if (!file_exists($filepath)) {
			$message .= "<"."?php if ( ! defined('WPINC')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if (!$fp = fopen($filepath, 'ab')) {
			return FALSE;
		}

		$message .= "======================\n".date('d-m-Y H-i-s')."\n".' ---------------------- '."\n".$label.' >>> '.$msg."\n\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, 0666);
		return TRUE;
	}
	
	
	

	
}
