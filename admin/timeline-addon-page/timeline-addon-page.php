<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	// Do not use namespace to keep this on global space to keep the singleton initialization working
if ( ! class_exists( 'cool_plugins_timeline_addons' ) ) {

	/**
	 *
	 * This is the main class for creating dashbord addon page and all submenu items
	 *
	 * Do not call or initialize this class directly, instead use the function mentioned at the bottom of this file
	 */
	class cool_plugins_timeline_addons {


		 /**
		  * None of these variables should be accessable from the outside of the class
		  */
		private static $instance;
			private $pro_plugins    = array();
			private $pages          = array();
			private $main_menu_slug = null;
			private $plugin_tag     = null;
			private $dashboar_page_heading;
			private $disable_plugins = array();
			private $addon_dir       = __DIR__;    // point to the main addon-page directory
			private $addon_file      = __FILE__;
			private $menu_title      = 'Addon Dashboard';
			private $menu_icon       = false;
			private $plugin_author   = 'https://plugins.coolplugins.net/plugins-list/';

			 /**
			  * initialize the class and create dashboard page only one time
			  */
		public static function init() {

			if ( empty( self::$instance ) ) {
				return self::$instance = new self();
			}
			return self::$instance;

		}

			/**
			 * Initialize the dashboard with specific plugins as per plugin tag
			 */
		public function show_plugins( $plugin_tag, $menu_slug, $dashboard_heading, $main_menu_title, $icon ) {

			if ( ! empty( $plugin_tag ) && ! empty( $menu_slug ) && ! empty( $dashboard_heading ) ) {
				$this->plugin_tag            = sanitize_text_field( $plugin_tag ); // Sanitize input
				$this->main_menu_slug        = sanitize_text_field( $menu_slug ); // Sanitize input
				$this->dashboar_page_heading = sanitize_text_field( $dashboard_heading ); // Sanitize input
				$this->menu_title            = sanitize_text_field( $main_menu_title ); // Sanitize input
				$this->menu_icon             = sanitize_text_field( $icon ); // Sanitize input
			} else {
				return false;
			}
			add_action( 'admin_menu', array( $this, 'init_plugins_dasboard_page' ), 1 );
			add_action( 'wp_ajax_cool_plugins_install_' . $this->plugin_tag, array( $this, 'cool_plugins_install' ) );
			add_action( 'wp_ajax_cool_plugins_activate_' . $this->plugin_tag, array( $this, 'cool_plugins_activate' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_required_scripts' ) );
		}

			/**
			 * handle ajax request for activating plugin from dashboard
			 */
		function cool_plugins_activate() {
			if ( current_user_can( 'upload_plugins' ) ) {
				$plugin_slug = isset( $_POST['cp_slug'] ) ? sanitize_text_field( $_POST['cp_slug'] ) : ''; // Sanitize input
				if ( ! empty( $plugin_slug ) ) {
					if ( ! check_ajax_referer( 'cp-nonce-activate-' . $plugin_slug, 'wp_nonce', false ) ) {
						wp_send_json_error( 'Invalid security token sent.' );
						wp_die();
					}
					$pluginBase      = ( isset( $_POST['pluginbase'] ) && ! empty( $_POST['pluginbase'] ) ) ? sanitize_text_field( $_POST['pluginbase'] ) : null;
					$plugin_base_arr = explode( '/', $pluginBase );
					if ( isset( $plugin_base_arr[0] ) && $plugin_base_arr[0] == $plugin_slug ) {
						activate_plugin( $pluginBase );
					} else {
						wp_send_json_error( 'Something wrong with plugin path.' );
						wp_die();
					}
				} else {
					wp_send_json_error( 'Plugin slug is missing.' );
					wp_die();
				}
			} else {
				wp_send_json_error( 'You have no permission to do this action.' );
				wp_die();
			}
		}
			/**
			 * handle ajax for installing plugin from the dashboard.
			 * This function use the core WordPress functionality of installing a plugin through URL
			 */
		function cool_plugins_install() {
			if ( current_user_can( 'upload_plugins' ) ) {
				$plugin_slug = isset( $_POST['cp_slug'] ) ? sanitize_text_field( $_POST['cp_slug'] ) : ''; // Sanitize input
				if ( ! empty( $plugin_slug ) ) {
					if ( ! check_ajax_referer( 'cp-nonce-download-' . $plugin_slug, 'wp_nonce', false ) ) {
						wp_send_json_error( 'Invalid security token sent.' );
						wp_die();
					}
					require_once plugin_dir_path( __DIR__ ) . 'timeline-addon-page/includes/cool_plugins_downloader.php';
					$downloader = new cool_plugins_downloader();
					$plugins    = $this->request_wp_plugins_data( $this->plugin_tag );
					if ( isset( $plugins[ $plugin_slug ] ) ) {
						$url = esc_url( $plugins[ $plugin_slug ]['download_link'] ); // Escape URL
						return $downloader->install( sanitize_url( $url ), 'install' ); // Sanitize URL
					} else {
						wp_send_json_error( 'Sorry, You are installing a wrong plugin.' );
						wp_die();
					}
				} else {
					wp_send_json_error( 'Plugin slug is missing.' );
					wp_die();
				}
			} else {
				wp_send_json_error( 'You have no permission to do this action.' );
				wp_die();
			}
		}

			/**
			 * This function will initialize the main dashboard menu for all plugins
			 */
		function init_plugins_dasboard_page() {

			add_menu_page( $this->menu_title, $this->menu_title, 'manage_options', $this->main_menu_slug, array( $this, 'displayPluginAdminDashboard' ), $this->menu_icon, 9 );
			add_submenu_page( $this->main_menu_slug, 'Dashboard', 'Dashboard', 'manage_options', $this->main_menu_slug, array( $this, 'displayPluginAdminDashboard' ), 1 );
		}

			/**
			 * This function will render and create the HTML display of dashboard page.
			 * All the HTML can be located in other template files.
			 * Avoid using any HTML here or use nominal HTML tags inside this function.
			 */
		function displayPluginAdminDashboard() {

			$tag     = $this->plugin_tag;
			$plugins = $this->request_wp_plugins_data( $tag );
			$this->request_pro_plugins_data( $tag );
			$this->disable_free_plugins();
			// merge free & pro plugins into one array
			if ( is_array( $plugins ) && count( $this->pro_plugins ) > 0 ) {
				$plugins = array_merge( $plugins, $this->pro_plugins );
			} else {
				$plugins = $this->pro_plugins;
			}
			if ( ! empty( $plugins ) && count( $plugins ) > 0 ) {

				require $this->addon_dir . '/includes/dashboard-header.php';
				echo '<div class="cool-body-left">
                    <div class="plugins-list installed-addons" data-empty-message="You have not installed any addon at the moment"><h3>Currently Installed Timeline Plugins</h3>';
				foreach ( $plugins as $plugin ) {

					$plugin_name = sanitize_text_field( $plugin['name'] ); // Sanitize output
					$plugin_desc = wp_kses_post( $plugin['desc'] ); // Sanitize output
					$plugin_logo = $this->addon_plugins_logo( $plugin['slug'] );
					$plugin_url  = null !== $plugin['download_link'] ? esc_url( $plugin['download_link'] ) : null; // Escape URL

					$plugin_slug    = sanitize_text_field( $plugin['slug'] ); // Sanitize output
					$plugin_version = sanitize_text_field( $plugin['version'] ); // Sanitize output

					if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
						require $this->addon_dir . '/includes/dashboard-page.php';
					}
				}
				echo '</div>';

				echo "<div class='plugins-list more-addons' data-empty-message='No more free timeline addons available at the moment'><h3>More Free Timeline Plugins</h3>";
				foreach ( $plugins as $plugin ) {

					if ( $plugin['download_link'] == null ) {
						continue;
					}

					$plugin_name    = sanitize_text_field( $plugin['name'] ); // Sanitize output
					$plugin_desc    = wp_kses_post( $plugin['desc'] ); // Sanitize output
					$plugin_logo    = $this->addon_plugins_logo( $plugin['slug'] );
					$plugin_url     = esc_url( $plugin['download_link'] ); // Escape URL
					$plugin_slug    = sanitize_text_field( $plugin['slug'] ); // Sanitize output
					$plugin_version = sanitize_text_field( $plugin['version'] ); // Sanitize output

					if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
						require $this->addon_dir . '/includes/dashboard-page.php';
					}
				}
				echo '</div>';
				if ( ! empty( $this->pro_plugins ) && count( $this->pro_plugins ) > 0 ) :
					/**
					 * Load this Pro Plugin container only if there are any pro plugins available
					 */
					echo "<div class='plugins-list pro-addons' data-empty-message='No more Pro plugins available at the moment'><h3>Premium Timeline Plugins</h3>";
					foreach ( $this->pro_plugins as $plugin ) {
						$plugin_logo    = '';
						$plugin_name    = sanitize_text_field( $plugin['name'] ); // Sanitize output
						$plugin_desc    = wp_kses_post( $plugin['desc'] ); // Sanitize output
						$plugin_logo    = $this->addon_plugins_logo( $plugin['slug'] );
						$plugin_pro_url = esc_url( $plugin['buyLink'] ); // Escape URL
						$plugin_url     = null;
						$plugin_version = null;
						$plugin_slug    = sanitize_text_field( $plugin['slug'] ); // Sanitize output

						if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
							require $this->addon_dir . '/includes/dashboard-page.php';
						}
					}
					echo '</div>';
					endif;
				echo '</div>';  // end of .cool-body-left
				require $this->addon_dir . '/includes/dashboard-sidebar.php';

			} else {
				// plugins are not available under this tag.
			}
		}

			/**
			 * Lets enqueue all the required CSS & JS
			 */
		function enqueue_required_scripts() {
			// A common CSS file will be enqueued for admin panel
			wp_enqueue_style( 'cool-plugins-timeline-addon', plugin_dir_url( __FILE__ ) . 'assets/css/styles.css', null, null, 'all' );
			if ( isset( $_GET['page'] ) && ( sanitize_text_field( $_GET['page'] ) == $this->main_menu_slug ) ) {
				wp_enqueue_script( 'cool-plugins-timeline-addon', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), null, true );
				wp_localize_script( 'cool-plugins-timeline-addon', 'cp_events', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			}

			wp_enqueue_script(
				'ctl-migration-js',
				plugin_dir_url( __FILE__ ) . 'assets/js/migration.js',
				array( 'jquery' ),
				'1.0',
				true
			);
			
			wp_localize_script( 'ctl-migration-js', 'ctl_migration', array(
				'nonce' => wp_create_nonce('ctl_migrate_nonce'),
				'redirect_url' => esc_url(admin_url('edit.php?post_type=cool_timeline')),
				'ajax_url' => admin_url('admin-ajax.php')
			));
		}

		function disable_free_plugins() {
			if ( isset( $this->pro_plugins ) ) {
				foreach ( $this->pro_plugins as  $plugin ) {
					if ( isset( $plugin['incompatible'] ) && $plugin['incompatible'] != null ) {
						$this->disable_plugins[ $plugin['incompatible'] ] = array( 'pro' => $plugin['slug'] );
					}
				}
			}
		}

			/**
			 * This function will gather all information regarding pro plugins.
			 */
		function request_pro_plugins_data( $tag = null ) {
			$trans_name  = $this->main_menu_slug . '_pro_api_cache' . $this->plugin_tag;
			$option_name = $this->main_menu_slug . '-' . $this->plugin_tag . '-pro';
			if ( get_transient( $trans_name ) != false ) {

				return $this->pro_plugins = get_option( $option_name, false );
			}
			$url = $this->plugin_author . 'pro/timeline';

			$pro_api  = esc_url( $url );
			$response = wp_remote_get( $pro_api, array( 'timeout' => 300 ) );

			if ( is_wp_error( $response ) ) {
				return;
			}
			$plugin_info = (array) json_decode( $response['body'] );
			
			foreach ( $plugin_info as $plugin ) {

				if ( $plugin->tag == $tag ) {

					$this->pro_plugins[ $plugin->slug ] = array(
						'name'          => sanitize_text_field( $plugin->name ), // Sanitize output
						'logo'          => esc_url( $plugin->image_url ), // Escape URL
						'desc'          => wp_kses_post( $plugin->info ), // Sanitize output
						'slug'          => sanitize_text_field( $plugin->slug ), // Sanitize output
						'buyLink'       => esc_url( $plugin->buy_url ), // Escape URL
						'version'       => sanitize_text_field( $plugin->version ), // Sanitize output
						'download_link' => null,
						'incompatible'  => sanitize_text_field( $plugin->free_version ), // Sanitize output
						'buyLink'       => esc_url( $plugin->buy_url ), // Escape URL
					);
					if ( property_exists( $plugin, 'free_version' ) && $plugin->free_version != null ) {
						$this->disable_plugins[ $plugin->free_version ] = array( 'pro' => $plugin->slug );
					}
				}
			}

			if ( ! empty( $this->pro_plugins ) && is_array( $this->pro_plugins ) && count( $this->pro_plugins ) ) {
				set_transient( $trans_name, $this->pro_plugins, DAY_IN_SECONDS );
				update_option( $option_name, $this->pro_plugins );
				return $this->pro_plugins;
			} elseif ( get_option( $option_name, false ) != false ) {
				return get_option( $option_name );
			}
		}


			/**
			 * Gather all the free plugin information from wordpress.org API
			 */
		function request_wp_plugins_data( $tag = null ) {

			if ( get_transient( $this->main_menu_slug . '_api_cache' . $this->plugin_tag ) != false ) {
				return get_option( $this->main_menu_slug . '-' . $this->plugin_tag, false );
			}
			// $request = array( 'action' => 'plugin_information', 'timeout' => 300, 'request' => serialize( $args) );

			$url = $this->plugin_author . 'free/timeline';

			$response = wp_remote_get( $url, array( 'timeout' => 300 ) );

			if ( is_wp_error( $response ) ) {
				return;
			}
			$plugin_info = json_decode( $response['body'], true );
			$all_plugins = array();
			
			foreach ( $plugin_info as $plugin ) {
				// if (!property_exists($plugin['tag'], $tag)) {
				// continue;
				// }
				$plugins_data['name'] = sanitize_text_field( $plugin['name'] ); // Sanitize output
				$plugins_data['logo'] = esc_url( $plugin['image_url'] ); // Escape URL

				/*
				   foreach ($plugin->icons as $icon) {
					$plugins_data['logo'] = $icon;
					break;
				} */
				$plugins_data['slug']           = sanitize_text_field( $plugin['slug'] ); // Sanitize output
				$plugins_data['desc']           = wp_kses_post( $plugin['info'] ); // Sanitize output
				$plugins_data['version']        = sanitize_text_field( $plugin['version'] ); // Sanitize output
				$plugins_data['tags']           = sanitize_text_field( $plugin['tag'] ); // Sanitize output
				$plugins_data['download_link']  = esc_url( $plugin['download_url'] ); // Escape URL
				$all_plugins[ $plugin['slug'] ] = $plugins_data;
			}

			if ( ! empty( $all_plugins ) && is_array( $all_plugins ) && count( $all_plugins ) ) {
				set_transient( $this->main_menu_slug . '_api_cache' . $this->plugin_tag, $all_plugins, DAY_IN_SECONDS );
				update_option( $this->main_menu_slug . '-' . $this->plugin_tag, $all_plugins );
				return $all_plugins;
			} elseif ( get_option( $this->main_menu_slug . '-' . $this->plugin_tag, false ) != false ) {
				return get_option( $this->main_menu_slug . '-' . $this->plugin_tag );
			}

		}
		function addon_plugins_logo( $slug ) {
			$logos_arr = array(
				'cool-timeline'                           => 'cool-timeline.png',
				'timeline-widget-addon-for-elementor'     => 'timeline-widget-addon-for-elementor.png',
				'timeline-widget-addon-for-elementor-pro' => 'timeline-widget-addon-for-elementor.png',
				'cool-timeline-pro'                       => 'cool-timeline.png',
				'timeline-block'                          => 'timeline-block.png',
				'timeline-builder-pro'                    => 'timeline-builder-pro.png',
				'timeline-module-for-divi'                => 'timeline-module-for-divi.png',
				'timeline-block-pro'                => 'timeline-block.png',
				'timeline-module-for-divi-pro'                => 'timeline-module-for-divi.png',
			);
			if ( isset( $logos_arr[ $slug ] ) ) {
				return $logo_url = CTL_PLUGIN_URL . 'admin/timeline-addon-page/assets/images/' . $logos_arr[ $slug ];
			} else {
				return $logo_url = CTL_PLUGIN_URL . 'admin/timeline-addon-page/assets/images/default-logo.png';
			}

		}
	}

	/**
	 *
	 * initialize the main dashboard class with all required parameters
	 */
	function cool_plugins_timeline_addons_settings_page( $tag, $settings_page_slug, $dashboard_heading, $main_menu_title, $icon ) {
		$event_page = cool_plugins_timeline_addons::init();
		$event_page->show_plugins( $tag, $settings_page_slug, $dashboard_heading, $main_menu_title, $icon );
	}
}

