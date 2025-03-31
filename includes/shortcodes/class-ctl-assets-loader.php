<?php

/**
 * CTL Assets Loader.
 *
 * @package CTL
 */

/**
 * Do not access the page directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CTL_Assets_Loader' ) ) {

	/**
	 * CTL Assets loader Class.
	 */
	class CTL_Assets_Loader {

		/**
		 * Shortcode attribute
		 *
		 * @var ctl_attr
		 */
		public $ctl_attr = array();

		/**
		 * Timeline setting option array
		 *
		 * @var ctl_options
		 */
		public $ctl_options = array();

		/**
		 * CTL_Assets_Loader Constructor Function
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'ctl_common_assets' ) );

			add_action( 'wp_enqueue_scripts', array( 'CTL_Styles_Generator', 'ctl_global_style' ) );
		}


		/**
		 * Loaded Timeline Global Assets
		 *
		 * @param object $attr timeline attributes.
		 */
		public function ctl_global_assets( $attr ) {
			// Load common assets required for all cases.
			$this->ctl_common_assets();

			// Fontawesome not equeued if icons dot or none.
			$icons = isset( $attr['icons'] ) && ( 'yes' === $attr['icons'] || 'YES' === $attr['icons'] ) ? true : false;

			wp_enqueue_style( 'ctl-gfonts' );

			// Enqueue Lightbox scripts and styles if needed.
			wp_enqueue_script( 'ctl_glightbox' );
			wp_enqueue_style( 'ctl_glightbox_css' );

			// Enqueue FontAwesome styles for icons if needed.
			if ( $icons ) {
				wp_enqueue_style( 'ctl_font_awesome' );
				wp_enqueue_style( 'ctl_font_shims', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/v4-shims.min.css' );
			}

			// Enqueue animation styles and scripts if needed.
			if ( isset( $attr['animation'] ) && 'none' !== $attr['animation'] && ! empty( $attr['animation'] ) ) {
				wp_dequeue_script( 'ctl_common_script' );
				wp_enqueue_style( 'aos_css' );
				wp_enqueue_script( 'aos_js' );
			}

			// Enqueue common styles and scripts.
			wp_enqueue_style( 'ctl_common_style' );
			wp_enqueue_script( 'ctl_common_script' );

			// Enqueue conditional assets based on attributes.
			$this->ctl_conditional_assets( $attr );
		}


		/**
		 * Timeline Vertical Assets Loaded
		 */
		public function ctl_vr_assets() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'rtl_styles' );
			}
			wp_enqueue_style( 'ctl_vertical_style' );
		}

		/**
		 * Timeline HOrizontal Assets Loaded
		 */
		public function ctl_hr_assets() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'rtl_styles' );
			}
			wp_enqueue_style( 'ctl_swiper_style' );
			wp_enqueue_script( 'ctl_swiper_script' );
			wp_enqueue_script( 'ctl_hr_script' );
			wp_enqueue_style( 'ctl_horizontal_style' );
		}


		/**
		 * Timeline Compact Assets Loaded
		 */
		public function ctl_cpt_assets() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'rtl_styles' );
			}
			wp_enqueue_style( 'ctl_vertical_style' );
			if ( ! wp_script_is( 'ctl-masonry', 'enqueued' ) ) {
				wp_enqueue_script( 'ctl-masonry' );
			}
			wp_enqueue_script( 'ctl_compact_script' );
			wp_enqueue_style( 'ctl_compact_style' );
		}

		/**
		 * Timeline Typography font family
		 */
		public function ctl_google_fonts() {
			$google_fonts    = array();
			$fonts_settings  = array( 'post_content_typo', 'post_title_typo', 'main_title_typo', 'ctl_date_typo' );
			$ctl_options_arr = get_option( 'cool_timeline_settings' );

			foreach ( $fonts_settings as $font_setting ) {
				if ( isset( $ctl_options_arr[ $font_setting ]['font-family'] ) ) {
					$post_content_typo = $ctl_options_arr[ $font_setting ];
					if ( isset( $post_content_typo['type'] )
					&& 'google' === $post_content_typo['type'] ) {
						$fonts = $post_content_typo['font-family'];
						if ( $fonts && 'inhert' !== $fonts ) {
							if ( 'Raleway' === $fonts ) {
								$fonts = 'Raleway:100';
							}
							$fonts = str_replace( ' ', '+', $fonts );
						}
						$google_fonts[] = $fonts;
					}
				}
			}

			$google_fonts = array_unique( $google_fonts );
			if ( is_array( $google_fonts ) && ! empty( $google_fonts ) ) {
				$allfonts = implode( '|', $google_fonts );
				wp_register_style( 'ctl-gfonts', "https://fonts.googleapis.com/css?family=$allfonts", false, CTL_V, 'all' );
			}
		}

		/**
		 * Timeline All Assets Registerd
		 */
		public function ctl_common_assets() {
			$minified_file = '.min';
			wp_register_script( 'ctl_glightbox', CTL_PLUGIN_URL . 'includes/shortcodes/assets/js/jquery.glightbox.min.js', array( 'jquery' ), CTL_V, true );

			wp_register_style( 'ctl_glightbox_css', CTL_PLUGIN_URL . 'includes/shortcodes/assets/css/glightbox.min.css', null, CTL_V, 'all' );

			wp_register_style( 'aos_css', CTL_PLUGIN_URL . 'includes/shortcodes/assets/css/aos.css', null, CTL_V, 'all' );

			wp_register_script( 'aos_js', CTL_PLUGIN_URL . 'includes/shortcodes/assets/js/aos.js', array( 'jquery' ), CTL_V, true );

			wp_register_style( 'rtl_styles', CTL_PLUGIN_URL . 'includes/shortcodes/assets/css/rtl-styles.css', null, CTL_V, 'all' );

			wp_register_style( 'ctl_font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', null, CTL_V, 'all' );

			// New cool timeline shortcode files.
			wp_register_style( 'ctl_common_style', CTL_PLUGIN_URL . "includes/shortcodes/assets/css/ctl-common-styles$minified_file.css", null, CTL_V, 'all' );
			wp_register_style( 'ctl_swiper_style', CTL_PLUGIN_URL . 'includes/shortcodes/assets/css/swiper.css', null, CTL_V, 'all' );
			wp_register_script( 'ctl_swiper_script', CTL_PLUGIN_URL . 'includes/shortcodes/assets/js/swiper.js', array( 'jquery' ), CTL_V, false );
			wp_register_style( 'ctl_vertical_style', CTL_PLUGIN_URL . "includes/shortcodes/assets/css/ctl-vertical-timeline$minified_file.css", null, CTL_V, 'all' );
			wp_register_style( 'ctl_horizontal_style', CTL_PLUGIN_URL . "includes/shortcodes/assets/css/ctl-horizontal-timeline$minified_file.css", null, CTL_V, 'all' );
			wp_register_script( 'ctl_common_script', CTL_PLUGIN_URL . "includes/shortcodes/assets/js/ctl-scripts$minified_file.js", array( 'jquery' ), CTL_V, true );
			wp_register_script( 'ctl_compact_script', CTL_PLUGIN_URL . "includes/shortcodes/assets/js/ctl-compact$minified_file.js", array( 'jquery' ), CTL_V, true );
			wp_register_script( 'ctl_hr_script', CTL_PLUGIN_URL . "includes/shortcodes/assets/js/ctl-horizontal$minified_file.js", array( 'jquery' ), CTL_V, true );

			// Compact layout masonry and image loaded library.
			wp_register_script( 'ctl-masonry', CTL_PLUGIN_URL . 'includes/shortcodes/assets/js/masonry.pkgd.min.js', array( 'jquery' ), CTL_V, false );
			wp_register_style( 'ctl_compact_style', CTL_PLUGIN_URL . "includes/shortcodes/assets/css/ctl-compact-style$minified_file.css", null, CTL_V, 'all' );

			$this->ctl_google_fonts();

			$this->load_assets_on_shortcode_pages();
		}


		public function load_assets_on_shortcode_pages() {
			$ctl_shortcode_page_ids = get_option( 'ctl_shortcode_page_ids', array() );
			$current_page_id        = get_the_ID();

			// Check if the current page ID is in the array of saved IDs
			if ( in_array( $current_page_id, $ctl_shortcode_page_ids ) ) {

				wp_enqueue_style( 'ctl_common_style' );

				$ctl_layout_used = get_option( 'ctl_layout_used' );
				if ( isset( $ctl_layout_used[ $current_page_id ] ) ) {
					$active_layout = $ctl_layout_used[ $current_page_id ];
					if ( in_array( 'compact', $active_layout ) ) {
						wp_enqueue_style( 'ctl_compact_style' );
						wp_enqueue_style( 'ctl_vertical_style' );
						if ( ! wp_script_is( 'ctl-masonry', 'enqueued' ) ) {
							wp_enqueue_script( 'ctl-masonry' );
						}
					} elseif ( in_array( 'horizontal', $active_layout ) ) {
						wp_enqueue_style( 'ctl_horizontal_style' );
						wp_enqueue_style( 'ctl_swiper_style' );
						wp_enqueue_script( 'ctl_swiper_script' );
					} else {
						wp_enqueue_style( 'ctl_vertical_style' );
					}
				}
			}
		}
		/**
		 * Timeline Conditional Assets Loaded
		 *
		 * @param object $attributes timeline attributes.
		 */
		public function ctl_conditional_assets( $attributes ) {
			$this->ctl_attr = $attributes;
			$design         = sanitize_text_field( $this->ctl_attr['layout'] );

			if ( 'horizontal' === $design ) {
				$this->ctl_hr_assets();
			} elseif ( 'compact' === $design ) {
				$this->ctl_cpt_assets();
			} else {
				$this->ctl_vr_assets();
			}
		}
	}
};
