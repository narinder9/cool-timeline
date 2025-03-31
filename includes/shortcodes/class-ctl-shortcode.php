<?php
/**
 * CTL Shortcode.
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
if ( ! class_exists( 'CTL_Shortcode' ) ) {

	/**
	 * Class Shortcode.
	 */
	class CTL_Shortcode {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Shortcode assets object variable
		 *
		 * @var object
		 */
		public $ctl_asset_obj = array();

		/**
		 * Configure settings array
		 *
		 * @var settings
		 */
		public $settings = array();

		/**
		 * Configure config layout array
		 *
		 * @var config_layout
		 */

		public $config_layout = array();

		/**
		 * Shortcode attribute array configure
		 *
		 * @var attribute
		 */
		public $attributes = array();


		/**
		 * Gets an instance of our plugin.
		 *
		 * @param object $settings_obj timeline settings object.
		 */
		public static function get_instance( $settings_obj ) {
			if ( null === self::$instance ) {
				self::$instance = new self( $settings_obj );
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @param object $settings_obj timeline settings object.
		 */
		public function __construct( $settings_obj ) {
			$this->settings = $settings_obj->ctl_get_settings();
			// register actions.
			add_action( 'init', array( $this, 'ctl_register_shortcode' ) );
			// layout files loader.
			$this->ctl_layout_loader();

			$this->ctl_asset_obj = new CTL_Assets_Loader();

			// Preview Ajax request instance
			CTL_Ajax_Handler::get_instance();
		}

		/**
		 * Load all layouts files
		 */
		public function ctl_layout_loader() {
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-loop-helpers.php';
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-layout-manager.php';
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-assets-loader.php';
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-styles-generator.php';
			require_once CTL_PLUGIN_DIR . 'includes/shortcodes/class-ctl-ajax-handler.php';
		}

		/**
		 * Add shortcode
		 */
		public function ctl_register_shortcode() {
			add_shortcode( 'cool-timeline', array( $this, 'ctl_shortcode_handler' ) );

		}
		/**
		 * Set shortcode attribute
		 * render shortcode block
		 *
		 * @param array  $atts shortcode attributes.
		 * @param string $content shortcode content.
		 */
		public function ctl_shortcode_handler( $atts, $content = null ) {
			/**
			 * Demo shortcode attributes
			 * [cool-timeline layout="default" skin="default" show-posts="10" date-format="F j" icons="NO" animation="none" story-content="short" order="DESC" ]
			 */

			// animations,animation.

			$default_attr = array(
				// Main configuration attributes.
				'post_per_page' => 10,
				'layout'        => '',
				'order'         => '',
				'story-content' => '',
				'animation'     => '',
				'date-format'   => '',
				'icons'         => 'YES',
				'show-posts'    => '',
				'skin'          => 'default',
				'items'         => '',
			);

			// Set shortcode attribute.
			$this->attributes = shortcode_atts( $default_attr, $atts );

			$this->attributes = $this->attributes_migration( $this->attributes, $atts );

			// Shortcode type define.
			$this->attributes['ctl_type'] = 'story_timeline';

			// load timeline global assets.
			$this->ctl_asset_obj->ctl_global_assets( $this->attributes );

			$this->attributes['config'] = $this->ctl_config_layouts( $this->attributes );
			$paged                      = 1;
			// include pagination arguments.
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			}
			$this->attributes['paged'] = $paged;
			$wp_query                  = $this->ctl_query_object( $this->attributes, $this->settings );
			// timeline html based on layout.
			$layout_manager_object = new CTL_Layout_Manager( $this->attributes, $wp_query, $this->settings );
			$output                = $layout_manager_object->render_layout();
			return $output;
		}

		/**
		 * Wp_query to get all stories.
		 */
		public function ctl_query_object() {
			$attributes = $this->attributes;
			$settings   = $this->settings;
			$show_posts = isset( $attributes['show-posts'] ) ? $attributes['show-posts'] : $settings['post_per_page'];

			$query_args               = array(
				'post_type'      => 'cool_timeline',
				'post_status'    => array( 'publish', 'future', 'Scheduled' ),
				'order'          => isset( $attributes['order'] ) ? sanitize_text_field( $attributes['order'] ) : sanitize_text_field( $settings['story_orders'] ),
				'orderby'        => 'ctl_story_timestamp',
				'posts_per_page' => $show_posts,
				'paged'          => sanitize_text_field( $attributes['paged'] ),
			);
			$query_args['meta_query'] = array(
				array(
					'key'     => 'ctl_story_timestamp',
					'compare' => 'EXISTS',
					'type'    => 'NUMERIC',
				),
			);

			$query_args = apply_filters( 'ctp_story_query_args', $query_args, $attributes );
			return new WP_Query( $query_args );
		}

		/**
		 * Configure layout based on the settings.
		 *
		 * @param object $attributes shortcode attributes.
		 */
		public function ctl_config_layouts( $attributes ) {
			$config_arr                   = array();
			$main_wrapper_cls             = 'ctl-wrapper';
			$wrapper_cls                  = array( 'cool-timeline-wrapper' );
			$ctl_animation                = '';
			$config_arr['data_attribute'] = array();

			$layout = $this->ctl_set_val( $attributes['layout'], 'default' );
			$skin   = $this->ctl_set_val( $attributes['skin'], 'default' );
			// create wrapper class based upon layout.
			switch ( $layout ) {
				case 'one-side':
					$wrapper_cls[]                 = 'ctl-one-sided';
					$wrapper_cls['ctl_design_cls'] = 'ctl-vertical-wrapper';
					break;
				case 'compact':
					$wrapper_cls[]                 = 'ctl-both-sided';
					$wrapper_cls[]                 = 'ctl-vertical-wrapper';
					$wrapper_cls['ctl_design_cls'] = 'ctl-compact-wrapper';
					break;
				case 'horizontal':
					$wrapper_cls[]                 = 'ctl-horizontal';
					$wrapper_cls['ctl_design_cls'] = 'ctl-horizontal-wrapper';
					$wrapper_cls[]                 = 'ctl-horizontal-timeline';
					break;
				default:
					$wrapper_cls[]                 = 'ctl-both-sided';
					$wrapper_cls['ctl_design_cls'] = 'ctl-vertical-wrapper';
					break;
			}

			if ( 'clean' === $skin ) {
				$wrapper_cls[] = 'ctl-clean-skin';
			}
			// create here a deprcated logic.

			// Deprecated animations attribute setting if the user has configured the shortcode with animations attribute.
			$ctl_animation = $attributes['animation'];

			if ( in_array( $ctl_animation, CTL_Helpers::get_deprecated_animations(), true ) ) {
				$ctl_animation = 'fade-in';
			}

			if ( 'horizontal' === $layout ) {
				$config_arr['data_attribute'][] = 'data-items="' . esc_attr( $this->attributes['items'] ) . '"';
			}

			$config_arr['layout']       = esc_attr( $layout );
			$config_arr['active_skin']  = esc_attr( $skin );
			$config_arr['main_wrp_cls'] = esc_attr( $main_wrapper_cls );
			$config_arr['wrapper_cls']  = $wrapper_cls;
			$config_arr['wrapper_id']   = wp_unique_id( 'cool_timeline_' );
			$config_arr['animation']    = ! empty( $ctl_animation ) ? esc_attr( $ctl_animation ) : 'none';
			return $config_arr;
		}

		public function attributes_migration( $attr, $shortcode_attr ) {
			$shortcode_attr = empty( $shortcode_attr ) ? array() : $shortcode_attr;
			if ( 'horizontal' === $attr['layout'] && ! array_key_exists( 'items', $shortcode_attr ) || array_key_exists( 'items', $shortcode_attr ) && empty( $shortcode_attr['items'] ) ) {
				$attr['items']      = isset( $attr['show-posts'] ) ? $attr['show-posts'] : $this->settings['post_per_page'];
				$attr['show-posts'] = '-1';
			}
			if ( isset( $attr['date-format'] ) && 'default' === $attr['date-format'] ) {
				$attr['date-format'] = 'F j';
			}
			return $attr;
		}

		/**
		 * Configure default values
		 *
		 * @param string $value shortcode attribute new value.
		 * @param string $default shortcode attribute default value.
		 */
		public function ctl_set_val( $value, $default ) {
			if ( isset( $value ) && ! empty( $value ) ) {
				return $value;
			}
			return $default;
		}
	}

}
