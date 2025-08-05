<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class CTL_free_migrations {


	/**
	 * Constructor.
	 */
	public function __construct() {
		
		add_action( 'admin_init', array( $this, 'ctl_postmeta_migration' ) );
		add_action( 'admin_init', array( $this, 'ctl_settings_migration' ) );
		add_action( 'wp_ajax_ctl_migrate_stories', array( $this, 'ctl_migrate_stories' ) );
	}
	
	function ctl_postmeta_migration() {

		
		if ( get_option( 'ctl-postmeta-migration' ) ) {
			return;
		}
		
		if ( version_compare( get_option( 'cool-free-timeline-v' ), '2.1', '>' ) && ! ( get_option( 'cool-timelne-v' ) ) ) {
			return;
		}

		$args  = array(
			'post_type'   => 'cool_timeline',
			'post_status' => array( 'publish', 'future' ),
			'numberposts' => -1,
		);
		$posts = get_posts( $args );

		
		$story_type_key = array(
			'ctl_story_date',
		);
		
		$story_media_key = array(
			'img_cont_size',
		);
		$story_icon_key  = array(
			'fa_field_icon',
		);

		if ( isset( $posts ) && is_array( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				
				$post_id = intval( $post->ID );

				foreach ( $story_icon_key as $item ) {
					$item_value                         = get_post_meta( $post_id, $item, true );
					$array_icon_type[ $item ]           = $item_value;
					$array_icon_type['story_icon_type'] = 'fontawesome';
				}

				foreach ( $story_type_key as $item ) {
					$item_value                         = get_post_meta( $post_id, $item, true );
					$array_story_type[ $item ]          = $item_value;
					$array_story_type['story_based_on'] = 'default';
				}

				foreach ( $story_media_key as $item ) {
					$item_value                        = get_post_meta( $post_id, $item, true );
					$array_story_media[ $item ]        = $item_value;
					$array_story_media['story_format'] = 'default';
				}

				update_post_meta( $post_id, 'story_type', $array_story_type );
				update_post_meta( $post_id, 'story_media', $array_story_media );
				update_post_meta( $post_id, 'story_icon', $array_icon_type );
				update_option( 'ctl-postmeta-migration', 'done' );
			}
		}
	}


	function ctl_settings_migration() {
		if ( ! get_option( 'cool_timeline_options' ) ) {
			return;
		}

		$old_settings = get_option( 'cool_timeline_options' );

		$new_settings = $this->ctl_save_settings(
			$old_settings,
			array(
				'face'   => 'font-family',
				'size'   => 'font-size',
				'weight' => 'font-weight',
				'src'    => 'url',
			)
		);

		update_option( 'cool_timeline_settings', $new_settings );
		update_option( 'ctl_settings_migration_status', 'done' );
		delete_option( 'cool_timeline_options' );
	}

	function ctl_recursive_change_key( $arr, $set ) {
		if ( is_array( $arr ) && is_array( $set ) ) {
			$newArr = array();
			foreach ( $arr as $k => $v ) {
				$key            = array_key_exists( $k, $set ) ? $set[ $k ] : $k;
				$newArr[ $key ] = is_array( $v ) ? $this->ctl_recursive_change_key( $v, $set ) : $v;
				if ( $key == 'font-size' ) {
					$newArr[ $key ] = str_replace( 'px', '', $v );
				}
			}

			return $newArr;
		}
		return $arr;
	}

	function ctl_save_settings( $arr, $set ) {
		if ( is_array( $arr ) && is_array( $set ) ) {
			$newArr                 = array();
			$timeline_header        = array();
			$story_date_settings    = array();
			$story_content_settings = array();

			$timeline_header_key = array( 'title_text', 'user_avatar' );

			$story_date_settings_key    = array( 'year_label_visibility' );
			$story_content_settings_key = array( 'content_length', 'display_readmore' );
			$arr                        = $this->ctl_recursive_change_key( $arr, $set );
			foreach ( $arr as $key => $value ) {
				if ( in_array( $key, $timeline_header_key ) ) {
					if ( $key == 'user_avatar' ) {
						if ( ! empty( $value ) ) {
							$value            = $this->ctl_recursive_change_key( $value, array( 'src' => 'url' ) );
							$thumbnail_img    = wp_get_attachment_image_src( $value['id'], 'thumbnail' );
							$value           += array(
								'thumbnail' => $thumbnail_img[0],
								'width'     => '843',
								'height'    => '450',
							);
							$timeline_header += array( $key => $value );
						}
					} else {
						$timeline_header += array( $key => $value );
					}
				} elseif ( in_array( $key, $story_date_settings_key ) ) {
					$story_date_settings += array( $key => $value );
				} elseif ( in_array( $key, $story_content_settings_key ) ) {
					$story_content_settings += array( $key => $value );
				} elseif ( $key == 'main_title_typo' ) {
					$title_alignment           = isset( $arr['title_alignment'] ) ? $arr['title_alignment'] : 'center';
					$value                    += array(
						'text-align' => $title_alignment,
						'type'       => 'google',
					);
					$newArr['main_title_typo'] = $value;
				} elseif ( $key == 'post_title_text_style' ) {
					$newArr['post_title_typo']['text-transform'] = $value;
				} elseif ( $key == 'background' ) {
					if ( isset( $value['enabled'] ) ) {
						$newArr['timeline_background'] = '1';
						$newArr['timeline_bg_color']   = $value['bg_color'];
					} else {
						$newArr['timeline_background'] = '0';
					}
				} elseif ( $key == 'post_title_typo' ) {
					$value                                 += array( 'type' => 'google' );
					$newArr['ctl_date_typo']['font-family'] = $value['font-family'];
					$newArr['ctl_date_typo']['font-weight'] = $value['font-weight'];
					$newArr['ctl_date_typo']['font-size']   = '21';
					$newArr['ctl_date_typo']['type']        = 'google';
					$newArr['post_title_typo']              = $value;
				} elseif ( $key == 'post_content_typo' ) {
					$value                      += array( 'type' => 'google' );
					$newArr['post_content_typo'] = $value;
				} else {
					$newArr[ $key ] = $value;
				}
			}

			$newArr['timeline_header']        = $timeline_header;
			$newArr['story_date_settings']    = $story_date_settings;
			$newArr['story_content_settings'] = $story_content_settings;
			return $newArr;
		}
		return $arr;
	}

	/**
	 * Migrate data from Timeline Express to Cool Timeline
	 */
	public function migrate_timeline_express_to_cool_timeline() {

		if ( get_option( 'timeline_express_migrated' ) ) {
			return;
		}
	
		$args = array(
			'post_type'      => 'te_announcements',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);
		
		$timeline_express_posts = get_posts( $args );
        if ( empty( $timeline_express_posts ) ) {
			return ;
		}

		$migrate_stories = 0;

		$timeline_settings     = get_option('timeline_express_storage');
		$cooltimeline_settings = get_option('cool_timeline_settings', []);
	
		if (!is_array($timeline_settings)) {
			$timeline_settings = array();
		}
		
		$cooltimeline_settings = (array) get_option('cool_timeline_settings', []);
		
		// Initialize all required array keys
		if (!isset($cooltimeline_settings['story_content_settings']) || !is_array($cooltimeline_settings['story_content_settings'])) {
			$cooltimeline_settings['story_content_settings'] = array();
		}
		
		// Ensure all settings are properly initialized
		$cooltimeline_settings = array_merge(array(
			'story_content_settings' => array(),
			'first_post' => '',
			'content_bg_color' => '',
			'line_color' => ''
		), $cooltimeline_settings);
		
		if ( ! empty( $timeline_settings['excerpt-trim-length'] ) ) {
			$cooltimeline_settings['story_content_settings']['content_length'] = (string) (int) $timeline_settings['excerpt-trim-length'];
		}
		
		if (isset($timeline_settings['read-more-visibility'])) {
			$cooltimeline_settings['story_content_settings']['display_readmore'] = $timeline_settings['read-more-visibility'] === '1' ? 'yes' : 'no';
		}

		if (isset($timeline_settings['default-announcement-color'])) {
			$cooltimeline_settings['first_post'] = $timeline_settings['default-announcement-color'];
		}

		if (isset($timeline_settings['announcement-bg-color'])) {
			$cooltimeline_settings['content_bg_color'] = $timeline_settings['announcement-bg-color'];
		}
		
		if (isset($timeline_settings['announcement-background-line-color'])) {
			$cooltimeline_settings['line_color'] = $timeline_settings['announcement-background-line-color'];
		}
       	
		foreach ( $timeline_express_posts as $old_post ) {

			if ( empty( $old_post->ID ) ) {
				continue;
			}

			$migrate_stories++;
			$event_timestamp = intval( get_post_meta( $old_post->ID, 'announcement_date', true ) );
			$icon_raw        = get_post_meta( $old_post->ID, 'announcement_icon', true );
			$color_raw       = get_post_meta( $old_post->ID, 'announcement_color', true );
			$attachment_id   = intval( get_post_meta( $old_post->ID, 'announcement_image_id', true ) );
			$excerpt         = wp_kses_post(get_post_meta($old_post->ID,'announcement_custom_excerpt',true));
			
			$formatted_for_meta = $event_timestamp ? date( 'm/d/Y h:i A', $event_timestamp ) : '';
			$color = sanitize_text_field( $color_raw );

			if (strpos($icon_raw, 'fa-') === false) {
				$icon_class = 'fa fa-' . sanitize_html_class($icon_raw);
			} else {
				$icon_class = 'fa ' . sanitize_html_class($icon_raw);
			}
			
			$new_post = array(
				'post_title'   => sanitize_text_field( $old_post->post_title ),
				'post_content' => wp_kses_post( $old_post->post_content ),
				'post_excerpt'=>$excerpt,
				'post_type'    => 'cool_timeline',
				'post_status'  => $old_post->post_status,
				'post_date'    => $old_post->post_date,
				'post_name'    => sanitize_title( $old_post->post_title ),
			);

			$new_post_id = wp_insert_post( $new_post );
			
			if ( ! is_wp_error( $new_post_id ) ) {

				clean_post_cache( $new_post_id );
				
				if ( $attachment_id && get_post_type( $attachment_id ) === 'attachment' ) {
					set_post_thumbnail( $new_post_id, $attachment_id );
				}

				wp_update_post([
					'ID' => $new_post_id,
					'post_status' => 'publish',
				]);
			
				update_post_meta( $new_post_id, '_ctl_visible', 'yes' );
				
				if ( ! empty( $formatted_for_meta ) ) {

					$story_type_serialized = [
						'ctl_story_date' => $formatted_for_meta,
					];

					update_post_meta( $new_post_id, 'ctl_story_timestamp', $event_timestamp );
					update_post_meta( $new_post_id, 'story_date', $formatted_for_meta );
					update_post_meta( $new_post_id, 'story_type', $story_type_serialized );
				}

				if ( ! empty( $color ) ) {

					update_post_meta( $new_post_id, 'story_color', $color );
				}

				if ( ! empty( $icon_class ) ) {

					$story_icon_serialized = [
						'fa_field_icon' => $icon_class,
					];
					update_post_meta( $new_post_id, 'story_icon', $story_icon_serialized );
				}
			}
		}
		
		update_option( 'timeline_express_migrated', 1 );
		update_option('cool_timeline_settings', $cooltimeline_settings);
		return $migrate_stories;
		
	}
	
	public function ctl_migrate_stories() {

		check_ajax_referer( 'ctl_migrate_nonce', 'nonce' );
	
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized', 'cool-timeline' ) ] );
			wp_die();
		}
	
		$total_stories = $this->migrate_timeline_express_to_cool_timeline();
	
		if ( empty( $total_stories ) || $total_stories === 0 ) {
		
			wp_send_json_error( [ 'message' => __( 'No Attachemnt Found To Migrate.', 'cool-timeline' ) ] );
			wp_die();
		}
	
		wp_send_json_success([
			'message' => __( 'Migration Completed', 'cool-timeline' ),
			'total_stories' => $total_stories
		]);
	}
	
	
}
new CTL_free_migrations();
