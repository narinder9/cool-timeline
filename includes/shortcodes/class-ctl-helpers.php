<?php
/**
 * CTL Helper.
 *
 * @package CTL
 */

/**
 * Do not access the page directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CTL_Helpers' ) ) {
	/**
	 * Class Helpers
	 */
	class CTL_Helpers {

		/**
		 * Timeline Stories Load More Pagination
		 *
		 * @param string $current_page_id current page id.
		 * @param string $layout shortcode layout.
		 */
		public static function has_shortcode_added( $current_page_id, $layout ) {
			// Get the list of saved page IDs.
			$ctl_shortcode_page_ids = get_option( 'ctl_shortcode_page_ids', array() );

			// Add the current page ID to the array if it's not already there.
			if ( ! in_array( $current_page_id, $ctl_shortcode_page_ids ) ) {
				$ctl_shortcode_page_ids[] = $current_page_id;
				update_option( 'ctl_shortcode_page_ids', $ctl_shortcode_page_ids );
				$ctl_used_layout[ $current_page_id ][] = $layout == 'default' ? 'vertical' : $layout;
				update_option( 'ctl_layout_used', $ctl_used_layout );
			}
		}

		/**
		 * Create own custom timestamp for stories
		 *
		 * @param string $story_date get story date.
		 */
		public static function ctlfree_generate_custom_timestamp( $story_date ) {

			if ( ! empty( $story_date ) ) {
				$ctl_story_date = strtotime( $story_date );
				if ( $ctl_story_date !== false ) {
					$story_timestamp = gmdate( 'YmdHi', $ctl_story_date );
				}
				return $story_timestamp;
			}
		}

		/**
		 * Get post type from url
		 */
		public static function ctl_get_ctp() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type ) {
				return $post->post_type;
			} elseif ( $typenow ) {
				return $typenow;
			} elseif ( $current_screen && $current_screen->post_type ) {
				return $current_screen->post_type;
			} elseif ( isset( $_REQUEST['post_type'] ) ) {
				return sanitize_key( $_REQUEST['post_type'] );
			}
			return null;
		}

		/**
		 * Old version deprecated animation list.
		 */
		public static function get_deprecated_animations() {
			$deprecated_animations = array(
				'bounceInUp',
				'bounceInDown',
				'bounceInLeft',
				'bounceInRight',
				'slideInDown',
				'slideInUp',
				'bounceIn',
				'slideInLeft',
				'slideInRight',
				'shake',
				'wobble',
				'swing',
				'jello',
				'flip',
				'fadein',
				'rotatein',
				'zoomIn',
			);
			return $deprecated_animations;
		}

		/**
		 * Get Timeline Excerpt Content.
		 *
		 * @param array $attributes timeline attributes.
		 * @param array $settings settings settings.
		 */
		public static function ctl_get_excerpt( $settings ) {
			global $post;

			$post_content      = $post->post_content;
			$length            = $settings['story_content_length'];
			$display_read_more = $settings['display_readmore'];
			$read_more_link    = '';

			// display read more btn if read more yes.
			if ( 'yes' === $display_read_more ) {
				$url             = get_the_permalink( get_the_ID() );
				$read_more_lbl   = __( 'Read More', 'cool-timeline' );
				$target          = ! empty( $settings['story_link_target'] ) ? $settings['story_link_target'] : '_self';
				$read_more_link .= '&hellip;<a class="read_more ctl_read_more" target="' . esc_attr( $target ) . '" href="' . esc_url( $url ) . '" role="link">' . esc_html__( $read_more_lbl, 'cool-timeline' ) . '</a>';
			}

			$excerpt = wpautop(
				// wp_trim_words() gets the first X words from a text string.
				wp_trim_words(
					$post_content, // We'll use the post's content as our text string.
					$length, // We want the first 55 words.
					$read_more_link // This is what comes after the first 55 words.
				)
			);

			return $excerpt;

		}

		/**
		 * Timeline before title and image
		 *
		 * @param object $settings Timeline settings object.
		 */
		public static function timeline_before_content( $settings ) {
			$output         = '';
			$title_text     = $settings['timeline_title'];
			$timeline_image = $settings['timeline_image'];
			$title_tag      = $settings['timeline_title_tag'];

			// if ( ! empty( $timeline_image['id'] ) || ( 'yes' === $title_enable && ! empty( $title_text ) ) ) {
			if ( ! empty( $title_text ) ) {
				$output .= '<div class="ctl-before-content">';

				// if ( ! empty( $timeline_image['id'] ) ) {
				// $user_avatar = wp_get_attachment_image_src( $timeline_image['id'], 'ctl_avatar' );
				// $output     .= sprintf(
				// '<div class="ctl-avatar"><span title="%s"><img src="%s" alt="%s"></span></div>',
				// esc_attr( $title_text ),
				// esc_url( $user_avatar[0] ),
				// esc_attr( $title_text )
				// );
				// }

				if ( ! empty( $title_text ) ) {
					$output .= '<div class="timeline-main-title"><' . $title_tag . '>' . esc_html( $title_text ) . '</' . $title_tag . '></div>';
				}

				$output .= '</div>';
			}

			return $output;
		}

		/**
		 * Timeline Stories Default Pagination
		 *
		 * @param WP_Query $wp_query WP_Query object.
		 * @param int      $paged current page number.
		 */
		public static function ctl_pagination( $wp_query, $paged, $fontawesome ) {
			$output   = '';
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
			$big        = 999999999;
			$of_lbl     = __( ' of ', 'cool-timeline' );
			$page_lbl   = __( ' Page ', 'cool-timeline' );
			$prev_arrow = $fontawesome ? '<i class="fas fa-angle-double-left"></i>' : self::ctl_static_svg_icons( 'double_left' );
			$next_arrow = $fontawesome ? '<i class="fas fa-angle-double-right"></i>' : self::ctl_static_svg_icons( 'double_right' );

			$pagination_args = array(
				'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'       => '?paged=%#%',
				'total'        => $numpages,
				'current'      => $paged,
				'show_all'     => false,
				'end_size'     => 1,
				'mid_size'     => 1,
				'prev_next'    => true,
				'prev_text'    => $prev_arrow,
				'next_text'    => $next_arrow,
				'type'         => 'plain',
				'add_args'     => false,
				'add_fragment' => '',
			);

			$paginate_links = paginate_links( $pagination_args );

			if ( $paginate_links ) {
				$output  = '<nav class="ctl-pagination" aria-label="Timeline Navigation">';
				$output .= '<span class="page-numbers ctl-page-num" role="status">' . $page_lbl . $paged . $of_lbl . $numpages . '</span> ';
				$output .= $paginate_links;
				$output .= '</nav>';
				return $output;
			}
			return '';
		}

		/**
		 * Static svg icon html
		 *
		 * @param string $icon icon type.
		 */
		public static function ctl_static_svg_icons( $icon ) {
			$icons_arr = array(
				'clock'         => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
				<path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"/>
				</svg>',
				'chevron_left'  => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
				<path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"/>
				</svg>',
				'chevron_right' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
				<path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
				</svg>',
				'chevron_up'    => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
				<path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"/>
				</svg>',
				'double_right'  => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
				<path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34zm192-34l-136-136c-9.4-9.4-24.6-9.4-33.9 0l-22.6 22.6c-9.4 9.4-9.4 24.6 0 33.9l96.4 96.4-96.4 96.4c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l136-136c9.4-9.2 9.4-24.4 0-33.8z"/>
				</svg>',
				'double_left'   => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
				<path d="M223.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L319.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L393.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34zm-192 34l136 136c9.4 9.4 24.6 9.4 33.9 0l22.6-22.6c9.4-9.4 9.4-24.6 0-33.9L127.9 256l96.4-96.4c9.4-9.4 9.4-24.6 0-33.9L201.7 103c-9.4-9.4-24.6-9.4-33.9 0l-136 136c-9.5 9.4-9.5 24.6-.1 34z"/>
				</svg>',
				'play_button'   => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
				<path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
				</svg>',
				'spinner'       => '<svg class="ctl-loader-spinner" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
				<path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"/>
				</svg>',
			);

			$data = isset( $icons_arr[ $icon ] ) ? $icons_arr[ $icon ] : '';
			return $data;
		}
	}

}



