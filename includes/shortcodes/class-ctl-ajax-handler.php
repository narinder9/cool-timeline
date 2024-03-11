<?php
/**
 * CTL Ajax Handler
 *
 * @package CTL
 */

/**
 * Do not access the page directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Cool Timeline ajax requests
 */
if ( ! class_exists( 'CTL_Ajax_Handler' ) ) {

	/**
	 * Class Ajax Hanlder.
	 *
	 * @package CTL
	 */
	class CTL_Ajax_Handler {
		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 *
		 * @param object $settings_obj timeline settings.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @param object $settings_obj Plugin settings.
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'wp_ajax_get_shortcode_preview', array( $this, 'ctp_shortocde_preview' ) );
			}
		}

		/**
		 * Cool Timeline story shortcode preview.
		 */
		public function ctp_shortocde_preview() {
			if ( ! check_ajax_referer( 'ctl_preview', 'nonce', false ) ) {
				wp_send_json_error( __( 'Invalid security token sent.', 'cool-timeline' ) );
				wp_die( '0', 400 );
			}

			$ctl_shortcode = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : wp_send_json_error( __( 'Try Again.', 'cool-timeline' ) );
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-shortcode-preview.php';

			$assets_data = new CTL_Shortcode_Preivew( $ctl_shortcode );
			$shortcode   = $assets_data->ctl_preview_shortcode();
			$assets_obj  = $assets_data->assets_obj();

			// Render the shortcode.
			$rendered_shortcode = do_shortcode( $shortcode );

			// Assuming $this->assets_obj is defined.
			$data['shortcode'] = $rendered_shortcode;
			$data['assets']    = $assets_obj;
			wp_send_json_success( $data );
			wp_die(); // Important for AJAX in WordPress.
		}
	}
}
