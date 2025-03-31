<?php
/**
 * CTL Settings.
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
 * Create shortcode class if class not exists
 */
if ( ! class_exists( 'CTL_Settings' ) ) {

	/**
	 * Class Shortcode.
	 */
	class CTL_Settings {

		/**
		 * Configure settings array
		 *
		 * @var settings
		 */
		public $settings = array();

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->settings = get_option( 'cool_timeline_settings' );
		}

		/**
		 * Get Settings and set default values
		 */
		public function ctl_get_settings() {
			$settings     = array();
			$settings_arr = $this->settings;

			$settings['timeline_background'] = isset( $settings_arr['timeline_background'] ) ? sanitize_text_field( $settings_arr['timeline_background'] ) : 0; // Sanitize

			$settings['timeline_bg_color'] = isset( $settings_arr['timeline_bg_color'] ) ? sanitize_hex_color( $settings_arr['timeline_bg_color'] ) : ''; // Sanitize as hex color

			$settings['timeline_title'] = isset( $settings_arr['timeline_header']['title_text'] ) ? sanitize_text_field( $settings_arr['timeline_header']['title_text'] ) : ''; // Sanitize text

			$settings['timeline_image'] = isset( $settings_arr['timeline_header']['user_avatar'] ) ? $settings_arr['timeline_header']['user_avatar'] : ''; // Sanitize URL

			$settings['story_content_length'] = isset( $settings_arr['story_content_settings']['content_length'] ) ? intval( $settings_arr['story_content_settings']['content_length'] ) : 50; // Sanitize as integer

			$settings['story_link_target'] = isset( $settings_arr['story_content_settings']['story_link_target'] ) ? esc_attr( $settings_arr['story_content_settings']['story_link_target'] ) : '_self'; // Sanitize attribute

			$settings['display_readmore'] = isset( $settings_arr['story_content_settings']['display_readmore'] ) ? esc_attr( $settings_arr['story_content_settings']['display_readmore'] ) : 'yes'; // Sanitize attribute

			$settings['first_story_position'] = isset( $settings_arr['first_story_position'] ) ? sanitize_text_field( $settings_arr['first_story_position'] ) : 'right'; // Sanitize text

			$settings['year_label'] = isset( $settings_arr['story_date_settings']['year_label_visibility'] ) ? sanitize_text_field( $settings_arr['story_date_settings']['year_label_visibility'] ) : 1; // Sanitize

			$settings['timeline_title_tag'] = isset( $settings_arr['title_tag'] ) ? sanitize_text_field( $settings_arr['title_tag'] ) : 'H2'; // Sanitize text

			$settings['title_color'] = isset( $settings_arr['title_color'] ) ? sanitize_hex_color( $settings_arr['title_color'] ) : '#fff'; // Sanitize as hex color

			$settings['year_bg_color'] = isset( $settings_arr['circle_border_color'] ) ? sanitize_hex_color( $settings_arr['circle_border_color'] ) : '#333333'; // Sanitize as hex color
			return $settings;
		}

	}

}
