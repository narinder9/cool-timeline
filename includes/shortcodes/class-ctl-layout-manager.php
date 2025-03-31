<?php
/**
 * CTL Layout Manager.
 *
 * @package CTL
 */

/**
 * Cool timeline posts loop helper class
 */

/**
 * Do not access the page directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create Cool Timeline layouts manager class.
 * Create shortcode HTML according to the layout.
 */
if ( ! class_exists( 'CTL_Layout_Manager' ) ) {

	/**
	 * Class Layouts Manager.
	 *
	 * @package CTL
	 */
	class CTL_Layout_Manager {

		/**
		 * Member Variable.
		 *
		 * @var array
		 */
		public $attributes;

		/**
		 * Member Variable.
		 *
		 * @var WP_Query
		 */
		public $wp_query;

		/**
		 * Member Variable.
		 *
		 * @var array
		 */
		public $settings;

		/**
		 * Member Variable.
		 *
		 * @var CTL_Loop_Helpers
		 */
		public $content_instance;

		/**
		 * Member Variable.
		 *
		 * @var Timeline_Id
		 */
		public $timeline_id;

		/**
		 * Constructor.
		 *
		 * @param array    $attributes Shortcode attributes.
		 * @param WP_Query $wp_query   WP_Query object.
		 * @param array    $settings   Plugin settings.
		 */
		public function __construct( $attributes, $wp_query, $settings ) {

			$this->attributes       = $attributes;
			$this->wp_query         = $wp_query;
			$this->settings         = $settings;
			$this->timeline_id      = uniqid();
			$this->content_instance = new CTL_Loop_Helpers( $this->attributes, $this->settings );
		}

		/**
		 * Render the layout based on the shortcode attributes.
		 *
		 * @return string The rendered layout HTML.
		 */
		public function render_layout() {
			ob_start();

			$layout   = $this->attributes['layout'];
			$response = $this->ctl_render_loop( $this->wp_query );
			if ( 'horizontal' === $layout ) {
				$this->render_horizontal_layout( $response );
			} else {
				$this->render_vertical_layout( $response );
			}

			return ob_get_clean();
		}

		/**
		 * Renders the story timeline.
		 *
		 * @param WP_Query $query WP_Query object.
		 *
		 * @return string The rendered stories HTML.
		 */
		private function ctl_render_loop( $query ) {
			$output = '';
			$index  = 1;
			// The Loop.
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					global $post;
					$output .= $this->content_instance->ctl_render( $index, $post );
					$index++;
				}
			} else {
				$output .= '<div class="no-content"><h4>';
				$output .= __( 'Sorry,You have not added any story yet', 'cool-timeline' );
				$output .= '</h4></div>';
			}
			wp_reset_postdata();
			return array(
				'HTML' => $output,
			);
		}
		/**
		 * Render horizontal timeline layout.
		 *
		 * @param string $response The content HTML.
		 */
		private function render_horizontal_layout( $response ) {
			$attributes         = $this->attributes;
			$wrapper_cls        = implode( ' ', $attributes['config']['wrapper_cls'] );
			$svg_icon           = 'icon' === $attributes['icons'];
			$swiper_left_arrow  = $svg_icon ? '<i class="fas fa-chevron-left"></i>' : CTL_Helpers::ctl_static_svg_icons( 'chevron_left' );
			$swiper_right_arrow = $svg_icon ? '<i class="fas fa-chevron-right"></i>' : CTL_Helpers::ctl_static_svg_icons( 'chevron_right' );
			?>
			<!-- Cool Timeline Free V<?php echo esc_html( CTL_V ); ?> -->
			<div class="<?php echo esc_attr( $attributes['config']['main_wrp_cls'] ); ?>" role="region" aria-label="Timeline">
				<div id="<?php echo esc_attr( $attributes['config']['wrapper_id'] ); ?>" class="<?php echo esc_attr( $wrapper_cls ); ?>" <?php echo implode( ' ', $attributes['config']['data_attribute'] ); ?>>
					<div class="ctl-wrapper-inside">
						<div id="ctl-slider-container" class="ctl-slider-container swiper-container swiper-container-horizontal">
							<!-- Timeline Container -->
							<div class="ctl-slider-wrapper ctl-timeline-container swiper-wrapper">
								<?php echo $response['HTML']; ?>
							</div>
							<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
						</div>
					</div>
					<!-- Swiper Next button -->
					<div class="ctl-button-prev swiper-button-disabled" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="true">
						<?php echo $swiper_left_arrow; ?>
					</div>
					<!-- Swiper Previous Button -->
					<div class="ctl-button-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false">
						<?php echo $swiper_right_arrow; ?>
					</div>
					<!-- Swiper Horizontal line -->
					<div class="ctl-h-line"></div>
				</div>
			</div>
			<?php
		}


		/**
		 * Render vertical timeline layout (both-sided and one-sided).
		 *
		 * @param string $response The content HTML.
		 */
		private function render_vertical_layout( $response ) {

			$rtl         = is_rtl() ? 'rtl' : '';
			$attributes  = $this->attributes;
			$wrapper_cls = implode( ' ', $attributes['config']['wrapper_cls'] );
			$svg_icon    = 'icon' === $attributes['icons'];
			?>
			<!-- Cool Timeline Free V<?php echo esc_html( CTL_V ); ?> -->
			<div class="<?php echo esc_attr( $attributes['config']['main_wrp_cls'] ) . ( $rtl ? ' ' . esc_attr( $rtl ) : '' ); ?>" role="region" aria-label="Timeline">
				<?php
				$timeline_content = CTL_Helpers::timeline_before_content( $this->settings );
				if ( ! empty( $timeline_content ) ) {
					echo wp_kses_post( $timeline_content ); // Escape output to allow safe HTML
				}
				?>
				<div id="<?php echo esc_attr( $attributes['config']['wrapper_id'] ); ?>" class="<?php echo esc_attr( $wrapper_cls ); ?>" <?php echo implode( ' ', $attributes['config']['data_attribute'] ); ?>>
					<div class="ctl-start"></div>
					<!-- Timeline Container -->
					<div class="ctl-timeline ctl-timeline-container" data-animation="<?php echo esc_attr( $attributes['config']['animation'] ); ?>">
						<!-- Center Line -->
						<div class="ctl-inner-line" role="presentation"></div>
						<?php echo $response['HTML']; ?>
					</div>
					<div class="ctl-end"></div>
					<?php
					if ( $this->wp_query->max_num_pages > 1 ) {
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						echo CTL_Helpers::ctl_pagination( $this->wp_query, $paged, $svg_icon );
					}
					?>
				</div>
			</div>
			<?php
		}


	}

}
