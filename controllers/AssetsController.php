<?php if (!defined('WPINC')) die();
		
class SoftDelete_AssetsController {	
	
	public static function admin_enqueue_scripts() {
		global $typenow;
		
		if (isset($_GET['page']) && $_GET['page'] == SOFT_DELETE_PLUGIN) {
			// styles
				$styles = array(
					'purecss.grids.responsive' => '/assets/bower_components/pure/grids-responsive-min.css',
					'purecss.grids.core' => '/assets/bower_components/pure/grids-core-min.css',
					'purecss.forms' => '/assets/bower_components/pure/forms-min.css',
					'font-awesome' => '/assets/bower_components/fontawesome/css/font-awesome.min.css',
					'admin.'.SOFT_DELETE_PLUGIN => '/assets/css/admin.css'
				);
				
				foreach($styles as $id => $file) {
					wp_enqueue_style(
						$id,
						plugins_url($file, SOFT_DELETE_FILE)
					);
				}

		}
	}
	
	
}
