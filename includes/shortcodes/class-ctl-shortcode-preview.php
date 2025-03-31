<?php

// Exit if not in the admin area or if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() || ! is_user_logged_in() ) {
	exit;
}

if ( ! check_ajax_referer( 'ctl_preview', 'nonce', false ) ) {
	wp_die( '0', 400 );
	exit;
}

if ( ! class_exists( 'CTL_Shortcode_Preivew' ) ) {
	/**
	 * CTL Preview Assets Class.
	 */
	class CTL_Shortcode_Preivew {
		/**
		 * Member Variable
		 *
		 * @var string
		 */
		private $shortcode = '';

		/**
		 * Member Variable
		 *
		 * @var string
		 */
		private $designs = '';

		/**
		 * Member Variable
		 *
		 * @var object
		 */
		private $assets_object = array();

		/**
		 * Constructor function
		 *
		 * @param string $data timeline design.
		 */
		public function __construct( $data ) {
			$this->ctl_create_shortcode( $data );
			$this->assets_loading();
		}

		/**
		 * Makte Preview Shortcode.
		 *
		 * @param array $data shortcode array.
		 */
		public function ctl_create_shortcode( $data ) {

			$shortcode_type = $this->ctl_shortcode_filter( $data['shortcodeType'] );
			$shortcode      = '[' . $shortcode_type;
			foreach ( $data as $key => $value ) {
				$shortcode_key = $this->ctl_shortcode_filter( $key );
				if ( 'date-format' === $key || 'custom-date-format' === $key || 'timeline-title' === $key ) {
					$shortcode_value = sanitize_text_field( $value );
				} elseif ( 'pagination' === $key && isset( $data['layout'] ) && 'horizontal' === $data['layout'] ) {
					$shortcode_value = esc_html( 'none' );
				} else {
					$shortcode_value = $this->ctl_shortcode_filter( $value );
				}

				if ( 'shortcodeType' !== $key ) {
					$shortcode .= " $shortcode_key=\"$shortcode_value\"";
				}

				if ( 'layout' === $key ) {
					$this->designs = $shortcode_value;
				}
			}

			$shortcode .= ']';

			$this->shortcode = $shortcode;
		}

		/**
		 * Assets Loading Classes Call
		 */
		public function assets_loading() {
			// Timeline Settings Custom styling.
			$this->ctl_custom_style();
			// Timeline Css files path.
			$this->ctl_preview_css();
			// Timeline Script files path.
			$this->ctl_preview_script();
		}

		/**
		 * Timeline Admin style settings.
		 */
		public function ctl_custom_style() {
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-styles-generator.php';
			$ctl_options_arr                     = get_option( 'cool_timeline_settings' );
			$custom_style                        = new CTL_Styles_Generator();
			$color_style                         = $custom_style::styles_settings_vars( $ctl_options_arr );
			$style                               = $custom_style::render_global_style( $color_style );
			$style                              .= $custom_style::ctl_global_typography( $ctl_options_arr );
			$style                              .= $this->ctl_preview_custom_css();
			$custom_css                          = isset( $ctl_options_arr['custom_styles'] ) ? $ctl_options_arr['custom_styles'] : '';
			$custom_css                          = preg_replace( '/\\\\/', '', $custom_css );
			$final_css                           = $custom_style::clt_minify_css( $style );
			$this->assets_object['custom_style'] = $final_css;
		}

		/**
		 * Shortcode Preview Custom CSs.
		 */
		public function ctl_preview_custom_css() {
			$style = '.ctl-wrapper {
				max-width: calc(100% - 200px) !important;
				margin: 0 auto 30px !important;
			}
			.ctl-wrapper .ctp-media-slider img{
				width: 100% !important
			}
			.ctl-wrapper .ctl-slider-wrapper,.ctl-wrapper .ctl-story,ctl_load_more_pagination,.ctl-category-container,.ctl-navigation-bar{
				pointer-events: none
			}';
			return $style;
		}

		/**
		 * Shortcode Preview CSS Files.
		 */
		public function ctl_preview_css() {
			$file_names = array( 'swiper.css', 'ctl-common-styles.min.css' );
			if ( 'horizontal' === $this->designs ) {
				array_push( $file_names, 'ctl-horizontal-timeline.min.css' );
			} elseif ( 'compact' === $this->designs ) {
				array_push( $file_names, 'ctl-compact-style.min.css' );
				array_push( $file_names, 'ctl-vertical-timeline.min.css' );
			} else {
				array_push( $file_names, 'ctl-vertical-timeline.min.css' );
			}

			$this->assets_object['style'] = $this->files_path( $file_names, 'css' );
		}

		/**
		 * Shortcode Preview Script Files.
		 */
		public function ctl_preview_script() {
			$file_names = array( 'ctl-scripts.min.js' );
			if ( 'horizontal' === $this->designs ) {
				array_push( $file_names, 'swiper.js' );
				array_push( $file_names, 'ctl-horizontal.min.js' );
			} elseif ( 'compact' === $this->designs ) {
				array_push( $file_names, 'ctl-compact.min.js' );
				array_push( $file_names, 'masonry.pkgd.min.js' );
			}

			$this->assets_object['script'] = $this->files_path( $file_names, 'js' );
		}

		/**
		 * Return both CSS and JS Full file path.
		 *
		 * @param array  $files files names.
		 * @param string $type file type css or js.
		 */
		public function files_path( $files, $type ) {
			$files_arr = array();
			$dir       = 'css' === $type ? 'includes/shortcodes/assets/css/' : 'includes/shortcodes/assets/js/';
			foreach ( $files as $file ) {
				$file_path = CTL_PLUGIN_URL . $dir . $file;
				array_push( $files_arr, $file_path );
			}
			return $files_arr;
		}

		/**
		 * Filter All Shortocde Attribute
		 *
		 * @param string $data shortcode attribute value.
		 * @return string filtered attribute value.
		 */
		public function ctl_shortcode_filter( $data ) {
			// Define symbols to be removed from the attribute value.
			$symbols = array( '*', '(', ')', '[', ']', '{', '}', '"', "'", '\\', '/', ';', '$', '<', '>', '.', '”', '#', '!', '@', '^' );

			// Remove specified symbols from the attribute value.
			$value = str_replace( $symbols, '', $data );

			// Escape HTML entities in the attribute value.
			$value = esc_html( $value );

			// Remove white spaces from the attribute value.
			$value = preg_replace( '/\s+/', '', $value );

			// Removes slashes from a string or recursively removes slashes from strings within an array.
			$value = wp_unslash( $value );

			// Sanitize the final value to ensure it's safe for output.
			$filter_value = sanitize_text_field( $value );

			// Return the filtered attribute value.
			return $filter_value;
		}

		/**
		 * Preivew Shortcode return
		 */
		public function ctl_preview_shortcode() {
			return $this->shortcode;
		}

		/**
		 * Return files path object.
		 */
		public function assets_obj() {
			return $this->assets_object;
		}
	}
}
