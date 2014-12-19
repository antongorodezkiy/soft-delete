<?php if (!defined('WPINC')) die();

class SoftDelete_PostsController {
	
	public static function init() {
		// override deletion
			add_action('before_delete_post', array(__CLASS__, 'fake_delete_post'), 100000, 1);
			
		// deletion of meta for attachment
			add_action( "delete_post_meta", array(__CLASS__, "fake_delete_meta"), 100000, 4 );
	}
	
	public static function soft_delete_allowed($current_user, $post) {
		$permissions = SoftDelete_AdminController::getSetting('permissions');
		
		if (defined('DOING_CRON')) {
			$roles = array("cron");
		}
		else {
			$roles = $current_user->roles;
		}
	
		$allowed = false;
		foreach($roles as $role) {
			
			if (isset($permissions[$post->post_type][$role])) {
				
				// deny first
					// if any of roles is disabled for deletion, then not allowed
					if ($permissions[$post->post_type][$role] == 'd') {
						$allowed = false;
						break;
					}
					
					// if some of roles enabled soft deletion, then use it
					else if ($permissions[$post->post_type][$role] == 's') {
						$allowed = true;
					}
			}
		}
		
		return $allowed;
	}
	
	public static function permanent_delete_allowed($current_user, $post) {
		$permissions = SoftDelete_AdminController::getSetting('permissions');
	
		if (defined('DOING_CRON')) {
			$roles = array("cron");
		}
		else {
			$roles = $current_user->roles;
		}
	
		$allowed = false;
		foreach($roles as $role) {
			
			if (isset($permissions[$post->post_type][$role])) {
				
				// deny first
					// if any of roles is disabled for deletion, then not allowed
					if ($permissions[$post->post_type][$role] == 'd') {
						$allowed = false;
						break;
					}
					
					// if some of roles enabled soft deletion, then use it
					else if ($permissions[$post->post_type][$role] == 'p') {
						$allowed = true;
					}
			}
		}
		
		return $allowed;
	}
	
	public static function fake_delete_post($post_id) {
		$post = get_post($post_id);
		$current_user = wp_get_current_user();
		
		if (self::soft_delete_allowed($current_user, $post)) {
			
			$post_id = wp_update_post(array(
				'ID' => $post_id,
				'post_status' => SoftDelete_AdminController::getSetting('soft_deleted_status')
			));
			
			if (!is_wp_error($post_id)) {
				SoftDelete_Plugin::log(__("deleted", SOFT_DELETE_PLUGIN),sprintf(__("Post #%d of type '%s' was soft deleted by user %s (#%d)", SOFT_DELETE_PLUGIN),
					$post->ID,
					$post->post_type,
					$current_user->display_name,
					$current_user->ID
				));
				SoftDelete_AdminController::showMessage(sprintf(__("Post #%d of type '%s' was soft deleted", SOFT_DELETE_PLUGIN),
					$post->ID,
					$post->post_type,
					$current_user->display_name,
					$current_user->ID
				));
			}
			else {
				SoftDelete_Plugin::log(__("delete error", SOFT_DELETE_PLUGIN), sprintf(__("Post #%d of type '%s' cannot be soft deleted by user %s (#%d) because of the error: %s", SOFT_DELETE_PLUGIN),
					$post->ID,
					$post->post_type,
					$current_user->display_name,
					$current_user->ID,
					$post_id->get_error_message()
				));
				SoftDelete_AdminController::showMessage(sprintf(__("Post #%d of type '%s' cannot be soft deleted because of the error: %s", SOFT_DELETE_PLUGIN),
					$post->ID,
					$post->post_type,
					$current_user->display_name,
					$current_user->ID,
					$post_id->get_error_message()
				));
			}
			
			$post_status = "";
			if (isset($_GET['post_status']) && $_GET['post_status']) {
				$post_status = "&post_status=".sanitize_text_field($_GET['post_status']);
			}
			
			wp_redirect(admin_url('edit.php?post_type='.$post->post_type.$post_status));
			exit;
		}
		else if (self::permanent_delete_allowed($current_user, $post)) {
			SoftDelete_Plugin::log(__("deleted", SOFT_DELETE_PLUGIN), sprintf(__("Post #%d of type '%s' was permanently deleted by user %s (#%d)", SOFT_DELETE_PLUGIN),
				$post->ID,
				$post->post_type,
				$current_user->display_name,
				$current_user->ID
			));
		}
		else {
			SoftDelete_Plugin::log(__("deleted", SOFT_DELETE_PLUGIN), sprintf(__("Post #%d of type '%s' not allowed to delete by user %s (#%d)", SOFT_DELETE_PLUGIN),
				$post->ID,
				$post->post_type,
				$current_user->display_name,
				$current_user->ID
			));
			
			wp_die(__("You're not allowed to delete this post."));
		}
	
		
	}
	
	
	public static function fake_delete_meta($meta_ids, $object_id, $meta_key, $_meta_value) {
		$post = get_post($object_id);
		
		if ($post->post_type == 'attachment') {
			self::fake_delete_post($object_id);
		}
	}
	
}
