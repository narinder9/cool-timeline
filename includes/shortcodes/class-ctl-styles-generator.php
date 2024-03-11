<?php
/**
 * CTP Assets Loader.
 *
 * @package CTP
 */

/**
 * Do not access the page directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CTL_Styles_Generator' ) ) {
	/**
	 * CTP Style loader Class.
	 */
	class CTL_Styles_Generator {

		/**
		 * Timeline global style settings
		 */
		public static function ctl_global_style() {
			$ctl_options_arr = get_option( 'cool_timeline_settings' );

			// Style options.
			$style_vars = self::styles_settings_vars( $ctl_options_arr );

			$style  = self::render_global_style( $style_vars );
			$style .= self::ctl_global_typography();

			$custom_css = isset( $ctl_options_arr['custom_styles'] ) ? $ctl_options_arr['custom_styles'] : '';
			$custom_css = preg_replace( '/\\\\/', '', $custom_css );
			$final_css  = self::clt_minify_css( $style );

			wp_add_inline_style( 'ctl_common_style', $custom_css . ' ' . $final_css );
		}

		/**
		 * Get global style settings variables
		 *
		 * @param object $settings Timeline settings object.
		 */
		public static function styles_settings_vars( $settings ) {
			$background_type = isset( $settings['timeline_background'] ) ? $settings['timeline_background'] : '0';
			$bg_color        = '';

			if ( '1' === $background_type ) {
				$bg_color = isset( $settings['timeline_bg_color'] ) ? $settings['timeline_bg_color'] : 'none';
			}

			$first_post_color  = isset( $settings['first_post'] ) ? $settings['first_post'] : '#02c5be';
			$second_post_color = isset( $settings['second_post'] ) ? $settings['second_post'] : '#f12945';
			$content_bg_color  = isset( $settings['content_bg_color'] ) ? $settings['content_bg_color'] : '#f9f9f9';
			$year_lbl_bg_color = isset( $settings['circle_border_color'] ) ? $settings['circle_border_color'] : '#025149';
			$line_color        = isset( $settings['line_color'] ) ? $settings['line_color'] : '#000';

			$global_styles = array(
				'--ctl-bg-color'           => $bg_color,
				'--ctw-first-story-color'  => $first_post_color,
				'--ctw-second-story-color' => $second_post_color,
				'--ctw-cbx-des-background' => $content_bg_color,
				'--ctw-ybx-bg'             => $year_lbl_bg_color,
				'--ctw-line-bg'            => $line_color,
			);

			return $global_styles;
		}

		/**
		 * Global typography settings
		 */
		public static function ctl_global_typography() {
			$ctl_options_arr           = get_option( 'cool_timeline_settings' );
			$ctl_main_title_typo_all   = isset( $ctl_options_arr['main_title_typo'] ) ? self::ctl_typo_output( $ctl_options_arr['main_title_typo'] ) : '';
			$ctl_post_title_typo_all   = isset( $ctl_options_arr['post_title_typo'] ) ? self::ctl_typo_output( $ctl_options_arr['post_title_typo'] ) : '';
			$ctl_post_content_typo_all = isset( $ctl_options_arr['post_content_typo'] ) ? self::ctl_typo_output( $ctl_options_arr['post_content_typo'] ) : '';
			$ctl_date_typo_all         = isset( $ctl_options_arr['ctl_date_typo'] ) ? self::ctl_typo_output( $ctl_options_arr['ctl_date_typo'] ) : '';

			$global_styles = array(
				'main-title' => $ctl_main_title_typo_all,
				'title'      => $ctl_post_title_typo_all,
				'desc'       => $ctl_post_content_typo_all,
				'date'       => $ctl_date_typo_all,
			);

			$style = self::render_global_typo_style( $global_styles );
			return $style;

		}

		/**
		 * Render global styles
		 *
		 * @param array $styles timeline style setting css.
		 */
		public static function render_global_style( $styles ) {
			$style = '.ctl-wrapper{';
			foreach ( $styles as $key => $value ) {
				if ( '' !== $value ) {
					$style .= $key . ': ' . $value . ';';
				}
			}

			return $style;
		}

		/**
		 * Render global typography style
		 *
		 * @param array $style timeline typography settings.
		 */
		public static function render_global_typo_style( $style ) {
			$typo_value = '';
			foreach ( $style as $key => $value ) {
				$typography = explode( ';', $value );

				foreach ( $typography as $property ) {
					$property = trim( $property );

					if ( ! empty( $property ) ) {
						$property_parts = explode( ':', $property );
						$property_key   = trim( $property_parts[0] );
						$property_value = trim( $property_parts[1] );

						$typo_value .= "--ctw-cbx-$key-$property_key: $property_value;";
					}
				}
			}
			$typo_value .= '}';
			return $typo_value;
		}

		/**
		 * Timeline Type Settings output with key value pair.
		 *
		 * @param object $settings get typograph settings.
		 */
		public static function ctl_typo_output( $settings ) {
			$output        = '';
			$important     = '';
			$font_family   = ( ! empty( $settings['font-family'] ) ) ? $settings['font-family'] : '';
			$backup_family = ( ! empty( $settings['backup-font-family'] ) ) ? ', ' . $settings['backup-font-family'] : '';
			if ( $font_family ) {
				$output .= 'font-family:' . $font_family . '' . $backup_family . $important . ';';
			}

			// Common font properties.
			$properties = array(
				'color',
				'font-weight',
				'font-style',
				'font-variant',
				'text-align',
				'text-transform',
				'text-decoration',
			);

			foreach ( $properties as $property ) {
				if ( isset( $settings[ $property ] ) && $settings[ $property ] !== '' ) {
					$output .= $property . ':' . $settings[ $property ] . $important . ';';
				}
			}

			$properties = array(
				'font-size',
				'line-height',
				'letter-spacing',
				'word-spacing',
			);

			$unit = ( ! empty( $settings['unit'] ) ) ? $settings['unit'] : 'px';

			$line_height_unit = ( ! empty( $settings['line_height_unit'] ) ) ? $settings['line_height_unit'] : 'px';

			foreach ( $properties as $property ) {
				if ( isset( $settings[ $property ] ) && $settings[ $property ] !== '' ) {
					$unit    = ( $property === 'line-height' ) ? $line_height_unit : $unit;
					$output .= $property . ':' . $settings[ $property ] . $unit . $important . ';';
				}
			}

			return $output;
		}

		/**
		 * Minify CSS
		 *
		 * @param string $css timeline css.
		 */
		public static function clt_minify_css( $css ) {
			$buffer = $css;
			// Remove comments.
			$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
			// Remove space after colons.
			$buffer = str_replace( ': ', ':', $buffer );
			// Remove whitespace.
			$buffer = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $buffer );
			$buffer = preg_replace( ' {2,}', ' ', $buffer );
			// Write everything out.
			return $buffer;
		}

		/**
		 * Add inline CSS
		 *
		 * @param string $styles timeline css.
		 */
		public static function ctl_inline_css( $styles ) {
			$final_css = self::clt_minify_css( $styles );
			wp_add_inline_style( 'ctl_styles', $final_css );
		}

	}
};
