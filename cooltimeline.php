<?php
/*
  Plugin Name: Cool Timeline
  Plugin URI:https://cooltimeline.com
  Description:Showcase your story, company history, events, or roadmap using stunning vertical or horizontal layouts.
  Version:3.0.5
  Author:Cool Plugins
  Author URI:https://coolplugins.net/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=author_page&utm_content=dashboard
  License:GPLv2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain:cool-timeline
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/** Configuration */
if ( ! defined( 'CTL_V' ) ) {
	define( 'CTL_V', '3.0.5' );
}
// define constants for later use
define( 'CTL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CTL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'CTL_DEMO_URL' ) ) {
	define( 'CTL_DEMO_URL', 'https://cooltimeline.com/demo/cool-timeline-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=demo' );
}
define( 'CTL_FEEDBACK_API', 'https://feedback.coolplugins.net/' );
if ( ! defined( 'CTL_BUY_PRO' ) ) {
	define( 'CTL_BUY_PRO', 'https://cooltimeline.com/plugin/cool-timeline-pro/' );
}

if ( ! class_exists( 'CoolTimeline' ) ) {
	final class CoolTimeline {


		/**
		 * The unique instance of the plugin.
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Registers our plugin with WordPress.
		 */
		public static function registers() {
			$thisIns = self::$instance;
			if ( class_exists( 'CoolTimelinePro' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				deactivate_plugins( 'cool-timeline/cooltimeline.php' );
				return;
			}

			// Installation and uninstallation hooks
			register_activation_hook( __FILE__, array( $thisIns, 'ctl_activate' ) );
			register_deactivation_hook( __FILE__, array( $thisIns, 'ctl_deactivate' ) );

			add_action( 'activated_plugin', array( $thisIns, 'ctl_plugin_redirection' ) );
			/* including required files */
			add_action( 'plugins_loaded', array( $thisIns, 'ctl_include_files' ) );
			add_action( 'init', array( $thisIns, 'ctl_flush_rules' ) );
			// loading plugin translation files
			add_action( 'init', array( $thisIns, 'ctl_load_plugin_textdomain' ) );
			// Cool Timeline all hooks integrations
			if ( is_admin() ) {
				$pluginpath = plugin_basename( __FILE__ );
				// plugin settings links hook
				add_filter( "plugin_action_links_$pluginpath", array( $thisIns, 'ctl_settings_link' ) );
				// save extra story meta for timeline sorting
				add_action( 'save_post', array( $thisIns, 'ctl_save_story_meta' ), 10, 3 );
				add_action( 'admin_init', array( $thisIns, 'onInit' ) );
				add_action( 'admin_menu', array( $thisIns, 'ctl_add_new_item' ) );

			}

			// Fixed bridge theme confliction using this action hook
			add_action( 'wp_print_scripts', array( $thisIns, 'ctl_deregister_javascript' ), 100 );
			
			// gutenberg block integartion
			require CTL_PLUGIN_DIR . 'includes/shortcode-blocks/ctl-block.php';
		}


		

		/** Constructor */
		public function __construct() {
			 // Setup your plugin object here
			 $this->cpfm_load_file();
			 add_action('csf_cool_timeline_settings_save_after', array($this,'ctl_plugin_settings_saved'));
		}
		public function cpfm_load_file(){
			if(!class_exists('CPFM_Feedback_Notice')){
					require_once __DIR__ . '/admin/feedback/cpfm-feedback-notice.php';
				}
			require_once __DIR__ . '/includes/cron/class-cron.php';
		}
		public function ctl_add_new_item() {
			add_submenu_page( 'cool-plugins-timeline-addon', 'Add New Story', '<strong>Add New Story</strong>', 'manage_options', 'post-new.php?post_type=cool_timeline', false, 15 );
		}

		public function ctl_plugin_settings_saved(){


			
			$data = get_option('cool_timeline_settings'); 

 			$opt_in = !empty($data['ctl_cpfm_feedback_data']) ? $data['ctl_cpfm_feedback_data']:'';
			
			if (!empty($opt_in)) {
				if(!wp_next_scheduled('ctl_extra_data_update')){
                wp_schedule_event(time(), 'every_30_days', 'ctl_extra_data_update');
				}
           
			}else {

				if (wp_next_scheduled('ctl_extra_data_update')) {
					wp_clear_scheduled_hook('ctl_extra_data_update');
				}
				
			}
		}

		/*
		  Including required files
		*/
		public function ctl_include_files() {
			// register cool-timeline post type
			require CTL_PLUGIN_DIR . 'admin/class.cool-timeline-posttype.php';
			require CTL_PLUGIN_DIR . 'includes/class-stories-migration.php';
			require_once CTL_PLUGIN_DIR . 'admin/class-migration.php';
			// contains helper funciton for timeline
			include_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-helpers.php';

			// Cool Timeline Src New Shortcode
			require CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-settings.php';
			$settings_obj = new CTL_Settings();
			// Cool Timeline Src New Shortcode
			require CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-shortcode.php';
			new CTL_Shortcode( $settings_obj );

			// VC addon support
			require CTL_PLUGIN_DIR . '/includes/class-cool-vc-addon.php';
			new CoolTmVCAddon();

			/* Loaded Backend files only */
			if ( is_admin() ) {
				
				require_once CTL_PLUGIN_DIR . 'admin/feedback/users-feedback.php';
				// including timeline stories meta boxes class
				
				require_once CTL_PLUGIN_DIR . 'admin/codestar-framework/codestar-framework.php';


				require_once CTL_PLUGIN_DIR . 'admin/feedback/users-feedback.php';
				
				/*** Plugin review notice file */
				require_once CTL_PLUGIN_DIR . '/admin/notices/admin-notices.php';

				require_once __DIR__ . '/admin/timeline-addon-page/timeline-addon-page.php';
				cool_plugins_timeline_addons_settings_page( 'timeline', 'cool-plugins-timeline-addon', 'Timeline Addons', ' Timeline Addons', CTL_PLUGIN_URL . 'assets/images/cool-timeline-icon.svg' );

			}
			
			require CTL_PLUGIN_DIR . 'includes/cool-timeline-block/src/init.php';
			require_once CTL_PLUGIN_DIR . 'admin/ctl-shortcode-generator.php';

			add_action('cpfm_register_notice', function () {
            
				if (!class_exists('CPFM_Feedback_Notice') || !current_user_can('manage_options')) {
					return;
				}
	
		$notice = [
	
			'title' => __('Timeline Plugins by Cool Plugins', 'ctl'),
			'message' => __('Help us make this plugin more compatible with your site by sharing non-sensitive site data.', 'cool-plugins-feedback'),
			'pages' => ['cool_timeline_settings', 'cool-plugins-timeline-addon'],
			'always_show_on' => ['cool_timeline_settings', 'cool-plugins-timeline-addon'],
			'plugin_name'=>'ctl'
		];
	
				
	
				CPFM_Feedback_Notice::cpfm_register_notice('cool-timeline', $notice);
	
					if (!isset($GLOBALS['cool_plugins_feedback'])) {
						$GLOBALS['cool_plugins_feedback'] = [];
					}
					
				
					$GLOBALS['cool_plugins_feedback']['cool-timeline'][] = $notice;
		   
			});
			add_action('cpfm_after_opt_in_ctl', function($category) {
			

				if ($category === 'cool-timeline') {
					$data = get_option('cool_timeline_settings'); 
					$data['ctl_cpfm_feedback_data'] = true;
			update_option('cool_timeline_settings', $data);
					
					require_once __DIR__ . '/includes/cron/class-cron.php';
					CTL_CRONJOB::ctl_send_data();
					
					
				}
			});



			

		}

		public function onInit() {

			if ( self::is_theme_activate( 'Divi' ) ) {
				ctl_free_create_admin_notice(
					array(
						'id'              => 'ctl-divi-module-notice',
						'message'         => __(
							'Greetings! We have noticed that you are currently using the <strong>Divi Page Builder</strong>.</br> 
					We would like to suggest trying out the latest <strong> <a href="https://wordpress.org/plugins/timeline-module-for-divi/" target="_blank"> Timeline Module For Divi </a></strong> plugin. <a class="button button-primary" href="https://wordpress.org/plugins/timeline-module-for-divi/" target="_blank">Try it now!</a> </br>Showcase your life story or <strong>company history</strong> an <strong>elegant & precise</strong> way.
					
					',
							'cool-timeline'
						),
						'review_interval' => 3,
						'logo'            => CTL_PLUGIN_URL . 'assets/images/divi-timeline-logo.png',
						'plugin_name'     => 'Timeline Module For Divi',
					)
				);
			}

			if ( did_action( 'elementor/loaded' ) ) {
				$old_user_ele_install_notice = get_option( 'dismiss_ele_addon_notice' ) != false ? get_option( 'dismiss_ele_addon_notice' ) : 'no';
				// check user already rated
				

				if ( $old_user_ele_install_notice == 'no' ) {
					ctl_free_create_admin_notice(
						array(
							'id'              => 'ctl-elementor-addon-notice',
							'message'         => __(
								'Greetings! We have noticed that you are currently using the <strong>Elementor Page Builder</strong>.</br> 
						We would like to suggest trying out the latest <strong> <a href="https://cooltimeline.com/plugin/elementor-timeline-widget-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=twea_inside_notice" target="_blank"> Timeline Widget Pro for Elementor </a></strong> plugin. <a class="button button-primary" href="https://cooltimeline.com/plugin/elementor-timeline-widget-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=twea_inside_notice" target="_blank">Try it now!</a> </br>Showcase your life story or <strong>company history</strong> an <strong>elegant & precise</strong> way.
						
						',
								'cool-timeline'
							),
							'review_interval' => 3,
							'logo'            => CTL_PLUGIN_URL . 'assets/images/elementor-addon.png',
							'plugin_name'     => 'Timeline Widget Pro for Elementor',
						)
					);

				}
			}
			/*** Plugin review notice file */
			ctl_free_create_admin_notice(
				array(
					'id'              => 'ctl_review_box',  // required and must be unique
					'slug'            => 'ctl',      // required in case of review box
					'review'          => true,     // required and set to be true for review box
					'review_url'      => esc_url( 'https://wordpress.org/support/plugin/cool-timeline/reviews/?filter=5#new-post' ), // required
					'plugin_name'     => 'Cool Timeline',    // required
					'logo'            => CTL_PLUGIN_URL . 'assets/images/cool-timeline-logo.png',    // optional: it will display logo
					'review_interval' => 3,                    // optional: this will display review notice
													  // after 5 days from the installation_time
														  // default is 3
				)
			);
		}

		// flush rewrite rules after activation
		public function ctl_flush_rules() {
			if ( get_option( 'ctl_flush_rewrite_rules_flag' ) ) {
				flush_rewrite_rules();
				delete_option( 'ctl_flush_rewrite_rules_flag' );
			}
		}

		// loading language files
		public function ctl_load_plugin_textdomain() {

			load_plugin_textdomain( 'cool-timeline', false, basename( dirname( __FILE__ ) ) . '/languages/' );

			if (!get_option( 'ctl_initial_save_version' ) ) {
				add_option( 'ctl_initial_save_version', CTL_V );
			}
	
			if(!get_option( 'ctl-install-date' ) ) {
				add_option( 'ctl-install-date', gmdate('Y-m-d h:i:s') );
			}

			if ( is_admin() ) {
				
				require_once CTL_PLUGIN_DIR . 'admin/ctl-admin-settings.php';
				require CTL_PLUGIN_DIR . 'admin/ctl-meta-fields.php';

				
			}
		}

		public function ctl_plugin_redirection( $plugin ) {
			if ( plugin_basename( __FILE__ ) === $plugin ) {
				exit( wp_redirect( admin_url( 'admin.php?page=cool_timeline_settings#tab=get-started' ) ) );
			}
		}

		// Add the settings link to the plugins page
		public function ctl_settings_link( $links ) {
			array_unshift( $links, '<a href="admin.php?page=cool_timeline_settings">Settings</a>' );
			$links[] = '<a style="font-weight:bold; color:#852636;" href="https://cooltimeline.com/plugin/cool-timeline-pro/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=plugin_list" target="_blank">Get Pro</a>';

			return $links;
		}

		/**
		 * Save post metadata when a story is saved.
		 *
		 * @param int  $post_id The post ID.
		 * @param post $post The post object.
		 * @param bool $update Whether this is an existing post being updated or not.
		 */
		public function ctl_save_story_meta( $post_id, $post, $update ) {
			// Check if our nonce is set and valid
			if ( ! isset( $_POST['ctl_nonce'] ) || ! wp_verify_nonce( $_POST['ctl_nonce'], 'ctl_save_story_meta' ) ) {
				return; // Nonce is invalid, exit
			}

			$post_type = get_post_type( $post_id );
			// If this isn't a 'cool_timeline' post, don't update it.

			if ( 'cool_timeline' != $post_type ) {
				return;
			}
			// - Update the post's metadata.
			if ( isset( $_POST['ctl_post_meta']['story_type']['ctl_story_date'] ) ) {
				$story_date      = sanitize_text_field( $_POST['ctl_post_meta']['story_type']['ctl_story_date'] );
				$story_timestamp = CTL_Helpers::ctlfree_generate_custom_timestamp( $story_date );
				update_post_meta( $post_id, 'ctl_story_timestamp', $story_timestamp );
				update_post_meta( $post_id, 'story_based_on', 'default' );
				update_post_meta( $post_id, 'ctl_story_date', $story_date );
			}

		}



		/*
		* Fixed Bridge theme confliction
		*/
		public function ctl_deregister_javascript() {
			if ( is_admin() ) {
				global $post;
				$screen = get_current_screen();
				if ( $screen->base == 'toplevel_page_cool_timeline_page' ) {
					wp_deregister_script( 'default' );
				}
				if ( isset( $post ) && isset( $post->post_type ) && $post->post_type == 'cool_timeline' ) {
					wp_deregister_script( 'acf-timepicker' );
					// wp_deregister_script( 'acf-input' ); // datepicker translaton issue
					// wp_deregister_script( 'acf' ); // datepicker translaton issue
					wp_deregister_script( 'jquery-ui-timepicker-js' );
					wp_deregister_script( 'thrive-admin-datetime-picker' ); // datepicker conflict with Rise theme
					wp_deregister_script( 'et_bfb_admin_date_addon_js' ); // datepicker conflict with Divi theme
					wp_deregister_script( 'zeen-engine-admin-vendors-js' ); // datepicker conflict with zeen engine plugin
				}
			}
		}



		public static function is_theme_activate( $target ) {
			$theme = wp_get_theme();
			if ( $theme->name == $target || stripos( $theme->parent_theme, $target ) !== false ) {
				return true;
			}
			return false;
		}
		/* Activating plugin and adding some info */
		public function ctl_activate() {

			update_option( 'cool-free-timeline-v', CTL_V );
			update_option( 'cool-timelne-plugin-type', 'FREE' );
			update_option( 'cool-timelne-installDate', gmdate( 'Y-m-d h:i:s' ) );
			update_option( 'cool-timeline-already-rated', 'no' );
			update_option( 'ctl_flush_rewrite_rules_flag', true );



			if (!get_option( 'ctl_initial_save_version' ) ) {
				add_option( 'ctl_initial_save_version', CTL_V );
			}
	
			if(!get_option( 'ctl-install-date' ) ) {
				add_option( 'ctl-install-date', gmdate('Y-m-d h:i:s') );
			}
			$data = get_option('cool_timeline_settings'); 

			$opt_in = !empty($data['ctl_cpfm_feedback_data']) ? $data['ctl_cpfm_feedback_data']:'';
			
		   if($opt_in){

			if (!wp_next_scheduled('ctl_extra_data_update')) {
	
				wp_schedule_event(time(), 'every_30_days', 'ctl_extra_data_update');
	
			}
		   }
	}

		/* Deactivate the plugin */
		public function ctl_deactivate() {
			if (wp_next_scheduled('ctl_extra_data_update')) {
				wp_clear_scheduled_hook('ctl_extra_data_update');
			}
		}

	public static function ctl_get_user_info() {

		global $wpdb;
	
		// Server and WP environment details
		$server_info = [
			'server_software'        => isset($_SERVER['SERVER_SOFTWARE']) ? sanitize_text_field($_SERVER['SERVER_SOFTWARE']) : 'N/A',
			'mysql_version'          => $wpdb ? sanitize_text_field($wpdb->get_var("SELECT VERSION()")) : 'N/A',
			'php_version'            => sanitize_text_field(phpversion() ?: 'N/A'),
			'wp_version'             => sanitize_text_field(get_bloginfo('version') ?: 'N/A'),
			'wp_debug'               => (defined('WP_DEBUG') && WP_DEBUG) ? 'Enabled' : 'Disabled',
			'wp_memory_limit'        => sanitize_text_field(ini_get('memory_limit') ?: 'N/A'),
			'wp_max_upload_size'     => sanitize_text_field(ini_get('upload_max_filesize') ?: 'N/A'),
			'wp_permalink_structure' => sanitize_text_field(get_option('permalink_structure') ?: 'Default'),
			'wp_multisite'           => is_multisite() ? 'Enabled' : 'Disabled',
			'wp_language'            => sanitize_text_field(get_option('WPLANG') ?: get_locale()),
			'wp_prefix'              => isset($wpdb->prefix) ? sanitize_key($wpdb->prefix) : 'N/A',
		];
	
		// Theme details
		$theme = wp_get_theme();
		$theme_data = [
			'name'      => sanitize_text_field($theme->get('Name')),
			'version'   => sanitize_text_field($theme->get('Version')),
			'theme_uri' => esc_url($theme->get('ThemeURI')),
		];
	

		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if (!function_exists('get_plugin_data')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
	

		$plugin_data = [];
		$active_plugins = get_option('active_plugins', []);
	
		foreach ($active_plugins as $plugin_path) {
			$plugin_file = WP_PLUGIN_DIR . '/' . ltrim($plugin_path, '/');
	
			if (file_exists($plugin_file)) {

				$plugin_info = get_plugin_data($plugin_file, false, false);
				$plugin_url = !empty($plugin_info['PluginURI']) ? esc_url($plugin_info['PluginURI']) : (!empty($plugin_info['AuthorURI']) ? esc_url($plugin_info['AuthorURI']) : 'N/A');
				$plugin_data[] = [
					'name'       => sanitize_text_field($plugin_info['Name']),
					'version'    => sanitize_text_field($plugin_info['Version']),
					'plugin_uri' => !empty($plugin_url) ? $plugin_url : 'N/A',
				];
			}
		}
	
		return [
			'server_info'   => $server_info,
			'extra_details' => [
				'wp_theme'       => $theme_data,
				'active_plugins' => $plugin_data,
			],
		];
	}
	
	}
}

/*** THANKS - CoolPlugins.net ) */
$ctl = CoolTimeline::get_instance();
$ctl->registers();

