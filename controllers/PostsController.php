<?php if (!defined('WPINC')) die();

class SoftDelete_PostsController {
	
	public static function init() {
		// override deletion
			add_action('before_delete_post', array(__CLASS__, 'fake_delete_post'), 100000, 1);
	}
	
	public static function role_uses_soft_deletion($current_user) {
		$roles_allowed_to_soft_delete = SoftDelete_AdminController::getSetting('roles_allowed_to_soft_delete');
		
		$roles = array_intersect($current_user->roles, $roles_allowed_to_soft_delete);
		return !empty($roles);
	}
	
	public static function role_uses_hard_deletion($current_user) {
		$roles_allowed_to_hard_delete = SoftDelete_AdminController::getSetting('roles_allowed_to_hard_delete');
		
		$roles = array_intersect($current_user->roles, $roles_allowed_to_hard_delete);
		return !empty($roles);
	}
	
	public static function post_is_allowed_for_soft_delete($post) {
		$types_allowed_for_soft_delete = SoftDelete_AdminController::getSetting('types_allowed_for_soft_delete');
		
		$allowed = in_array($post->post_type, $types_allowed_for_soft_delete);
		return $allowed;
	}
	
	public static function post_is_allowed_for_hard_delete($post) {
		$types_allowed_for_hard_delete = SoftDelete_AdminController::getSetting('types_allowed_for_hard_delete');
		
		$allowed = in_array($post->post_type, $types_allowed_for_hard_delete);
		return $allowed;
	}
	
	public static function fake_delete_post($post_id) {
		$post = get_post($post_id);
		$current_user = wp_get_current_user();
		
		if (self::role_uses_soft_delete($current_user) && self::post_is_allowed_for_soft_delete($post)) {
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => SoftDelete_AdminController::getSetting('soft_deleted_status')
			));
			
			$post_status = "";
			if (isset($_GET['post_status']) && $_GET['post_status']) {
				$post_status = "post_status=".sanitize_text_field($_GET['post_status']);
			}
			
			wp_redirect(admin_url('edit.php?post_type='.$post->post_type.$post_status));
			exit;
		}
		else if (self::role_uses_hard_delete($current_user) && self::post_is_allowed_for_hard_delete($post)) {
			// continue the deletion process
		}
		else {
			wp_die(__("You're not allowed to delete this post."));
		}
	
		
	}
	
}
