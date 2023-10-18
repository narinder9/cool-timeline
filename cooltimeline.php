<?php
/*
  Plugin Name: Cool Timeline
  Plugin URI:https://cooltimeline.com
  Description:Cool Timeline is a responsive WordPress timeline plugin that allows you to create beautiful vertical storyline. You simply create posts, set images and date then Cool Timeline will automatically populate these posts in chronological order, based on the year and date
  Version:2.7.1
  Author:Cool Plugins
  Author URI:https://coolplugins.net/our-cool-plugins-list/
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
	define( 'CTL_V', '2.7.1' );
}
// define constants for later use
define( 'CTL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CTL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'CTL_DEMO_URL' ) ) {
	define( 'CTL_DEMO_URL', 'https://cooltimeline.com/demo/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=demo' );
}
if ( ! defined( 'CTL_BUY_PRO' ) ) {
	define( 'CTL_BUY_PRO', 'https://cooltimeline.com/buy-cool-timeline-pro/' );
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

			/* including required files */
			add_action( 'plugins_loaded', array( $thisIns, 'ctl_include_files' ) );
			add_action( 'init', array( $thisIns, 'ctl_flush_rules' ) );
			// loading plugin translation files
			add_action( 'plugins_loaded', array( $thisIns, 'ctl_load_plugin_textdomain' ) );
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
		}
		public function ctl_add_new_item() {
			add_submenu_page( 'cool-plugins-timeline-addon', 'Add New Story', '<strong>Add New Story</strong>', 'manage_options', 'post-new.php?post_type=cool_timeline', false, 15 );
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
			include_once CTL_PLUGIN_DIR . 'includes/ctl-helper-functions.php';
			// Cool Timeline Main shortcode
			require CTL_PLUGIN_DIR . 'includes/shortcodes/story-timeline/cool-timeline-shortcode.php';
			new CoolTimelineShortcodeFree();
			// VC addon support
			require CTL_PLUGIN_DIR . '/includes/class-cool-vc-addon.php';
			new CoolTmVCAddon();

			/* Loaded Backend files only */
			if ( is_admin() ) {
				// Codestar
				/* Plugin Settings panel */

				require_once CTL_PLUGIN_DIR . 'admin/codestar-framework/codestar-framework.php';
				require_once CTL_PLUGIN_DIR . 'admin/ctl-admin-settings.php';
				require_once CTL_PLUGIN_DIR . 'admin/feedback/users-feedback.php';
				// including timeline stories meta boxes class
				require CTL_PLUGIN_DIR . 'admin/ctl-meta-fields.php';

				/*** Plugin review notice file */
				require_once CTL_PLUGIN_DIR . '/admin/notices/admin-notices.php';

				require_once __DIR__ . '/admin/timeline-addon-page/timeline-addon-page.php';
				cool_plugins_timeline_addons_settings_page( 'timeline', 'cool-plugins-timeline-addon', 'Timeline Addons', ' Timeline Addons', CTL_PLUGIN_URL . 'assets/images/cool-timeline-icon.svg' );

			}
			// Files specific for the front-end
			// new gutenberg instant timeline builder
			require CTL_PLUGIN_DIR . 'includes/gutenberg-instant-builder/cooltimeline-instant-builder.php';
			require CTL_PLUGIN_DIR . 'includes/cool-timeline-block/src/init.php';
			CoolTimelineInstantBuilder::get_instance();
			require_once CTL_PLUGIN_DIR . 'admin/ctl-shortcode-generator.php';

		}
		public function onInit() {

			if ( did_action( 'elementor/loaded' ) ) {
				$old_user_ele_install_notice = get_option( 'dismiss_ele_addon_notice' ) != false ? get_option( 'dismiss_ele_addon_notice' ) : 'no';
				// check user already rated

				if ( $old_user_ele_install_notice == 'no' ) {
					ctl_free_create_admin_notice(
						array(
							'id'              => 'ctl-elementor-addon-notice',
							'message'         => __(
								'Greetings! We have noticed that you are currently using the <strong>Elementor Page Builder</strong>.</br> 
						We would like to suggest trying out the latest <strong> <a href="https://coolplugins.net/product/elementor-timeline-widget-pro-addon/?utm_source=ctl_plugin&utm_medium=inside_notice&utm_campaign=get_pro&utm_content=use_twea_notice" target="_blank"> Timeline Widget Pro for Elementor </a></strong> plugin. <a class="button button-primary" href="https://coolplugins.net/product/elementor-timeline-widget-pro-addon/?utm_source=ctl_plugin&utm_medium=inside_notice&utm_campaign=get_pro&utm_content=use_twea_notice" target="_blank">Try it now!</a> </br>Showcase your life story or <strong>company history</strong> an <strong>elegant & precise</strong> way.
						
						',
								'ctl'
							),
							'review_interval' => 3,
							'logo'            => CTL_PLUGIN_URL . 'assets/images/elementor-addon.png',
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
			$rs = load_plugin_textdomain( 'cool-timeline', false, basename( dirname( __FILE__ ) ) . '/languages/' );

		}

		// Add the settings link to the plugins page
		public function ctl_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=cool_timeline_settings">Settings</a>';
			array_unshift( $links, $settings_link );
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
			$post_type = get_post_type( $post_id );
			// If this isn't a 'cool_timeline' post, don't update it.

			if ( 'cool_timeline' != $post_type ) {
				return;
			}
			// - Update the post's metadata.
			if ( isset( $_POST['ctl_post_meta']['story_type']['ctl_story_date'] ) ) {
				$story_date      = sanitize_text_field( $_POST['ctl_post_meta']['story_type']['ctl_story_date'] );
				$story_timestamp = CTL_functions::ctlfree_generate_custom_timestamp( $story_date );
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
					wp_deregister_script( 'acf-input' ); // datepicker translaton issue
					wp_deregister_script( 'acf' ); // datepicker translaton issue
					wp_deregister_script( 'jquery-ui-timepicker-js' );
					wp_deregister_script( 'thrive-admin-datetime-picker' ); // datepicker conflict with Rise theme
					wp_deregister_script( 'et_bfb_admin_date_addon_js' ); // datepicker conflict with Divi theme
					wp_deregister_script( 'zeen-engine-admin-vendors-js' ); // datepicker conflict with zeen engine plugin
				}
			}
		}



		/* Activating plugin and adding some info */
		public function ctl_activate() {

			update_option( 'cool-free-timeline-v', CTL_V );
			update_option( 'cool-timelne-plugin-type', 'FREE' );
			update_option( 'cool-timelne-installDate', gmdate( 'Y-m-d h:i:s' ) );
			update_option( 'cool-timeline-already-rated', 'no' );
			update_option( 'ctl_flush_rewrite_rules_flag', true );
		}

		/* Deactivate the plugin */
		public function ctl_deactivate() {
			// Do nothing
		}
	}
}

/*** THANKS - CoolPlugins.net ) */
$ctl = CoolTimeline::get_instance();
$ctl->registers();

