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

			$settings['timeline_background'] = isset( $settings_arr['timeline_background'] ) ? $settings_arr['timeline_background'] : 0;

			$settings['timeline_bg_color'] = isset( $settings_arr['timeline_bg_color'] ) ? $settings_arr['timeline_bg_color'] : '';

			$settings['timeline_title'] = isset( $settings_arr['timeline_header']['title_text'] ) ? $settings_arr['timeline_header']['title_text'] : '';

			$settings['timeline_image'] = isset( $settings_arr['timeline_header']['user_avatar'] ) ? $settings_arr['timeline_header']['user_avatar'] : '';

			$settings['story_content_length'] = isset( $settings_arr['story_content_settings']['content_length'] ) ? $settings_arr['story_content_settings']['content_length'] : 50;

			$settings['story_link_target'] = isset( $settings_arr['story_content_settings']['story_link_target'] ) ? $settings_arr['story_content_settings']['story_link_target'] : '_self';

			$settings['display_readmore'] = isset( $settings_arr['story_content_settings']['display_readmore'] ) ? $settings_arr['story_content_settings']['display_readmore'] : 'yes';

			$settings['first_story_position'] = isset( $settings_arr['first_story_position'] ) ? $settings_arr['first_story_position'] : 'right';

			$settings['year_label'] = isset( $settings_arr['story_date_settings']['year_label_visibility'] ) ? $settings_arr['story_date_settings']['year_label_visibility'] : 1;

			$settings['timeline_title_tag'] = isset( $settings_arr['title_tag'] ) ? $settings_arr['title_tag'] : 'H2';

			$settings['title_color'] = isset( $settings_arr['title_color'] ) ? $settings_arr['title_color'] : '#fff';

			$settings['year_bg_color'] = isset( $settings_arr['circle_border_color'] ) ? $settings_arr['circle_border_color'] : '#333333';
			return $settings;
		}

	}

}
