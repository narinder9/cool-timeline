<?php
/**
 * CTL Loop Helper.
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
 * Cool timeline loop helper class
 */

if ( ! class_exists( 'CTL_Loop_Helpers' ) ) {

	/**
	 * Class Loop Helpers.
	 */
	class CTL_Loop_Helpers {

		/**
		 * Member Variable
		 *
		 * @var attributes
		 */
		public $attributes;

		/**
		 * Member Variable
		 *
		 * @var settings
		 */
		public $settings;

		/**
		 * Used To stop duplicate year label
		 *
		 * @var active_year
		 */
		public $active_year = '';

		/**
		 * Define Story Type
		 *
		 * @var tm_type
		 */
		public $tm_type = 'Story';


		/**
		 * Constructor
		 *
		 * @param object $attributes shortcode attributes.
		 * @param object $settings timeline settings.
		 */
		public function __construct( $attributes, $settings ) {
			// Plugin initialization.
			$this->attributes  = $attributes;
			$this->settings    = $settings;
			$this->active_year = '';
		}

		/**
		 * Renders the  timeline Single Story.
		 *
		 * @param int    $index Index value of current post.
		 * @param object $post Current Post object.
		 *
		 * @since 0.0.1
		 */
		public function ctl_render( $index, $post ) {

			$output     = '';
			$post_id    = get_the_ID();
			$classes    = array( 'ctl-story' );
			$attributes = $this->attributes;
			$layout     = $attributes['layout'];
			$animation  = 'horizontal' === $layout ? 'none' : $attributes['config']['animation'];
			if ( 'NO' === $attributes['icons'] || 'no' === $attributes['icons'] ) {
				$classes[] = 'ctl-story-dot-icon';
			} else {
				$classes[] = 'ctl-story-icon';
			};
			if ( 'horizontal' === $layout ) {
				$classes[] = 'swiper-slide';
				$classes[] = 0 === $index % 2 ? 'even' : 'odd';
			} else {
				// dynamic alternate class.
				$condition = 0 === $index % 2;

				$classes[] = $condition ? 'even' : 'odd';

				if ( isset( $this->settings['first_story_position'] ) && 'left' === $this->settings['first_story_position'] ) {
					$condition = 0 !== $index % 2;
				}

				if ( $condition ) {
					$classes[] = 'one-side' !== $this->attributes['layout'] ? 'ctl-story-left' : 'ctl-story-right';
				} else {
					$classes[] = 'ctl-story-right';
				}
			}

			// Get Year label.
			if ( $this->settings['year_label'] && ( 'one-side' === $layout || 'default' === $layout ) ) {
				$output .= $this->ctl_get_year_label( $post_id );
			}
			$output .= '<!-- Timeline Content --><div  id="ctl-story-' . esc_attr( $post_id ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '" data-aos="' . esc_attr( $animation ) . '"   data-story-index="' . esc_attr( $index ) . '" role="article">';

			if ( 'compact' !== $layout ) {
					$output .= $this->ctl_get_date( $post_id, $attributes['date-format'] );
			}

			if ( 'NO' === $attributes['icons'] || 'no' === $attributes['icons'] ) {
				$output .= '<!-- ' . $this->tm_type . ' IconDot --><div class="ctl-icondot"></div> ';
			} else {
				$output .= '<!-- ' . $this->tm_type . ' Icon -->' . $this->ctl_get_icon( $post_id );
			};

			if ( 'compact' === $layout || 'horizontal' === $layout || 'clean' !== $attributes['skin'] ) {
				$output .= '<!-- ' . $this->tm_type . ' Arrow --><div class="ctl-arrow"></div>';
			}
			$output .= '<!-- ' . $this->tm_type . ' Content --><div class="ctl-content">';
			$output .= $this->ctl_get_title( $post_id );

			// Grabing story content based upon content type.
			if ( 'horizontal' !== $layout ) {
				$output .= $this->ctl_get_featured_image( $post_id );
			}

			if ( 'compact' === $layout ) {
					$output .= $this->ctl_get_date( $post_id, $attributes['date-format'] );
			}

			if ( 'horizontal' !== $layout ) {
				$output .= $this->ctl_get_content();
			}

			$output .= '</div>';

			if ( 'horizontal' === $layout ) {
				$output .= $this->ctl_minimal_content();
			}

			$output .= '</div>';

			return $output;
		}

		/**
		 * Get Story Title
		 *
		 * @param int $post_id Current Post ID.
		 */
		public function ctl_get_title( $post_id ) {
			$output = '';
			if ( ! empty( get_the_title() ) ) {
				$layout      = $this->attributes['layout'];
				$title_class = 'story-' . $post_id;
				$re_more     = ( ( isset( $this->settings['display_readmore'] ) && 'yes' === $this->settings['display_readmore'] ) || 'horizontal' === $layout );
				$output     .= '<!-- ' . $this->tm_type . ' Title --><div class="ctl-title ' . esc_attr( $title_class ) . '" aria-label="2">';
				$output     .= $re_more ? $this->get_story_link( $post_id ) : '';
				$output     .= wp_kses_post( get_the_title( $post_id ) );
				$output     .= $re_more ? '</a>' : '';

				if ( ( isset( $this->settings['display_readmore'] ) && 'yes' === $this->settings['display_readmore'] ) && 'horizontal' === $layout ) {
					$output .= $re_more ? $this->get_story_link( $post_id ) : '';
					$output .= '<p class="ctl_read_more">' . __( 'Read More', 'cool-timeline' ) . '</p>';
					$output .= $re_more ? '</a>' : '';
				}

				$output .= '</div>';
			}
			return $output;
		}

		/**
		 * Get Timeline Story links
		 *
		 * @param int $post_id Current Post ID.
		 */
		public function get_story_link( $post_id ) {
			$attributes = $this->attributes;
			$story_link = '';
			$url        = '';
			// Get custom link settings.
			if ( 'horizontal' === $attributes['layout'] ) {
				$story_link = '<a class="minimal_glightbox" data-popup-id="#ctl-popup-' . esc_attr( $post_id ) . '">';
			} else {
				$target = ! empty( $this->settings['story_link_target'] ) ? $this->settings['story_link_target'] : '_self';
				$url    = get_the_permalink( $post_id );

				$story_link = '<a target="' . esc_attr( $target ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '" href="' . esc_url( $url ) . '" class="story-link">';
			}

			return $story_link;
		}

		/**
		 * Get Story Date
		 *
		 * @param int    $post_id Current Post ID.
		 * @param string $date_formats Timeline date format.
		 * @return string Formatted date HTML output.
		 */
		public function ctl_get_date( $post_id, $date_formats ) {
			$ctl_story_type = get_post_meta( $post_id, 'story_type', true );
			$ctl_story_date = $ctl_story_type['ctl_story_date'];
			$layout         = $this->attributes['layout'];
			$re_more        = ( ( isset( $this->settings['display_readmore'] ) && 'yes' === $this->settings['display_readmore'] ) && 'horizontal' === $layout );
			$output         = '';
			if ( $ctl_story_date ) {
				if ( strtotime( $ctl_story_date ) !== false ) {
					$posted_date = date_i18n( __( "$date_formats", 'cool-timeline' ), strtotime( "$ctl_story_date" ) );
				} else {
					$ctl_story_date = trim( str_ireplace( array( 'am', 'pm' ), '', $ctl_story_date ) );
					$dateobj        = DateTime::createFromFormat( 'm/d/Y H:i', $ctl_story_date, wp_timezone() );
					if ( $dateobj ) {
						$posted_date = $dateobj->format( __( "$date_formats", 'cool-timeline' ) );
					}
				}
				if ( ! empty( $posted_date ) ) {
					$output .= '<!-- ' . $this->tm_type . ' Date --><div class="ctl-labels">';
					$output .= '<div class="ctl-label-big story-date">';
					$output .= $re_more ? $this->get_story_link( $post_id ) : '';
					$output .= wp_kses_post( $posted_date ); // Escape date output
					$output .= $re_more ? '</a>' : '';
					$output .= '</div>';
					$output .= '</div>';
				}
				return $output;
			}
		}

		/**
		 * Get stories content
		 */
		public function ctl_get_content() {
			$attributes = $this->attributes;
			$output     = '';
			$content    = '';
			if ( 'full' === $attributes['story-content'] ) {
				global $post;
				$content .= apply_filters( 'the_content', $post->post_content );
			} else {
				// $content .= '<p>' . apply_filters( 'ctl_story_excerpt', get_the_excerpt() ) . '</p>';
				$content .= CTL_Helpers::ctl_get_excerpt( $this->settings );
			}
			if ( ! empty( $content ) ) {
				$output .= '<!-- ' . $this->tm_type . ' Description --><div class="ctl-description">' . apply_filters( 'the_content', $content ) . '</div>';
			}
			return $output;
		}

		/**
		 * Get Story Featured Image
		 *
		 * @param int $post_id Current Post ID.
		 */
		public function ctl_get_featured_image( $post_id ) {
			$attributes = $this->attributes;
			global $post;
			$post_id = $post->ID;

			if ( ! get_the_post_thumbnail_url( $post_id ) ) {
				return;
			}

			// Get story media and image container size.
			$ctl_story_media = get_post_meta( $post_id, 'story_media', true );
			$img_cont_size   = isset( $ctl_story_media['img_cont_size'] ) ? $ctl_story_media['img_cont_size'] : '';
			$img_html        = '';

			// Get alternative text for the image.
			$img_alt  = get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true );
			$alt_text = $img_alt ? $img_alt : get_the_title( $post_id );

			// Generate image link based on stories images link setting.
			$story_img_link = '';
			$img_f_url      = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
			$story_img_link = '<a data-glightbox="' . esc_attr( get_the_title( $post_id ) ) . '" href="' . esc_url( $img_f_url ) . '" class="ctl_glightbox">';

			$image_size        = '';
			$image_wrapper_cls = '';
			if ( 'small' === $img_cont_size ) {
				$image_size        = 'medium';
				$image_wrapper_cls = 'small';
			} else {
				$image_size        = 'large';
				$image_wrapper_cls = 'full';
			}

			if ( 'horizontal' === $attributes['layout'] ) {
				$img_html .= '<div class="ctl_popup_img" data-popup-image="' . esc_url( $img_f_url ) . '"></div>';
			} else {
				// Generate image HTML based on image container size.
				if ( get_the_post_thumbnail_url( $post_id ) ) {
					$img_html .= '<!-- ' . $this->tm_type . ' Media --><div class="ctl-media ' . esc_attr( $image_wrapper_cls ) . '">';
					$img_html .= $story_img_link;
					$img_html .= get_the_post_thumbnail(
						$post_id,
						apply_filters( 'cool_timeline_story_img_size', $image_size ),
						array(
							'class' => 'story-img',
							'alt'   => esc_html( $alt_text ),
						)
					);
					$img_html .= '</a>';
					$img_html .= '</div>';
				}
			}

			return $img_html;
		}

		/**
		 * Get Story Icon
		 *
		 * @param int    $post_id Current Post ID.
		 * @param string $default_icon default icon.
		 */
		public function ctl_get_icon( $post_id ) {
			$output      = '';
			$icon        = '';
			$extra_class = '';
			if ( get_post_type( $post_id ) === 'cool_timeline' ) {
				// Story Icon.
				$story_icon           = get_post_meta( $post_id, 'story_icon', true );
				$ctl_fontawesome_icon = isset( $story_icon['fa_field_icon'] ) ? $story_icon['fa_field_icon'] : '';
				$extra_class          = '';
				if ( '' !== $ctl_fontawesome_icon ) {
					if ( strpos( $ctl_fontawesome_icon, 'fab' ) === false ) {
						$extra_class = 'fa';
					}
					$icon = '<i class= "' . esc_attr( $extra_class ) . ' ' . esc_attr( $ctl_fontawesome_icon ) . '" aria-hidden ="true"></i>';
				} else {
						$icon = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
				}
			}
			$output .= '<div class="ctl-icon">' . $icon . '</div> ';
			return $output;
		}

		/**
		 * Get Story Year and create highlighted Year section in timeline
		 *
		 * @param int $post_id    Current Post ID.
		 * @return string Output HTML.
		 */
		public function ctl_get_year_label( $post_id ) {
			$attributes = $this->attributes;
			$output     = '';
			$wrp_cls    = 'vertical';
			$design     = 'light';

			// Get story date.
			$ctl_story_type = get_post_meta( $post_id, 'story_type', true );
			$ctl_story_date = isset( $ctl_story_type['ctl_story_date'] ) ? $ctl_story_type['ctl_story_date'] : '';
			$pattern        = "/\b\d{4}\b/"; // Regular expression pattern to match a four-digit number.
			preg_match( $pattern, $ctl_story_date, $matches );

				// Get Year value.
			if ( isset( $matches[0] ) ) {
				$story_year = $matches[0];
				// Display the year label if it is different from the previous year.
				if ( $story_year !== $this->active_year ) {

					$story_year_label = sprintf( '<div class="ctl-year-label ctl-year-text"><span>%s</span></div>', $story_year );

					$this->active_year = $story_year;
					if ( 'compact' === $attributes['layout'] ) {
						$output .= '<span class="scrollable-section ctl-year-container" data-section-title="' . $story_year . '"></span>';
					} else {
						$output .= sprintf(
							'<!-- ' . $this->tm_type . ' Year Section --><div data-cls="sc-nv-%s %s" class="timeline-year scrollable-section ctl-year ctl-year-container %s-year" data-section-title="%s" id="year-%s">%s</div>',
							esc_attr( $design ),
							esc_attr( $wrp_cls ),
							esc_attr( $design ),
							esc_attr( $story_year ),
							esc_attr( $story_year ),
							apply_filters( 'ctl_story_year', $story_year_label )
						);
					}
				}
			}

			return $output;
		}

		/**
		 * Returns the timezone string for a site, even if it's set to a UTC offset
		 * Adapted from http : // www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
		 *
		 * @return string valid PHP timezone string
		 */
		public function ctl_wp_get_timezone_string() {
			// if site timezone string exists, return it.
			if ( $timezone = get_option( 'timezone_string' ) ) {
				return $timezone;
			}
				// get UTC offset, if it isn't set then return UTC.
			if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
				return 'UTC';
			}
				// adjust UTC offset from hours to seconds.
				$utc_offset *= 3600;
				// attempt to guess the timezone string from the UTC offset.
			if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
				return $timezone;
			}
				// last try, guess timezone string manually.
				$is_dst = date( 'I' );
			foreach ( timezone_abbreviations_list() as $abbr ) {
				foreach ( $abbr as $city ) {
					if ( $city['dst'] === $is_dst && $city['offset'] === $utc_offset ) {
						return $city['timezone_id'];
					}
				}
			}
				// fallback to UTC.
				return 'UTC';
		}

		/**
		 * Minimal Popup Content
		 */
		public function ctl_minimal_content() {
			$post_id    = get_the_ID();
			$output     = '';
			$attributes = $this->attributes;

			$output .= '<div id="ctl-popup-' . esc_attr( $post_id ) . '" class="ctl_popup_hide">
				<div class="ctl_popup_container">
					<div class="ctl_popup_date">';
			$output .= $this->ctl_get_date( $post_id, $attributes['date-format'] );
			$output .= '</div>
					<h2 class="ctl_popup_title">';
			$output .= get_the_title();
			$output .= '</h2>
					<div class="ctl_popup_media">';
			$output .= $this->ctl_get_featured_image( $post_id );
			$output .= '</div>
					<div class="ctl_popup_desc">';
			$output .= $this->ctl_get_content();
			$output .= '</div>
				</div>
			</div>';

			return $output;
		}

	}



}

