<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CTL_stories_migration {

	/**
	 * Registers our plugin with WordPress.
	 */
	public static function register() {
		 $migration = new self();
		// migrate old version stories
		add_action( 'init', array( $migration, 'ctl_migrate_old_stories' ) );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		 // Setup your plugin object here
	}


	// run migration from old version since version 1.7
	public function ctl_migrate_old_stories() {
		if ( get_option( 'ctl-upgraded' ) !== false ) {
			return;
		}
		$ctl_version = get_option( 'cool-timelne-v' );
		$ctl_type    = get_option( 'cool-timelne-type' );
		if ( version_compare( $ctl_version, '1.7', '<' ) ) {
			self::ctl_run_migration();
		}
		update_option( 'ctl-upgraded', 'yes' );
	}

	/*
		Migrate old stories
	*/
	public function ctl_run_migration() {
		$args  = array(
			'post_type'   => 'cool_timeline',
			'post_status' => array( 'publish', 'future', 'scheduled' ),
			'numberposts' => -1,
		);
		$posts = get_posts( $args );

		if ( isset( $posts ) && is_array( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$published_date = get_the_date( 'm/d/Y H:i', $post->ID );
				if ( $published_date ) {
					// Sanitize the published date
					$published_date = sanitize_text_field( $published_date );
					update_post_meta( $post->ID, 'ctl_story_date', $published_date );
					$story_timestamp = CTL_Helpers::ctlfree_generate_custom_timestamp( $published_date );
					update_post_meta( $post->ID, 'ctl_story_timestamp', $story_timestamp );
				}
			}
		}
	}

}
CTL_stories_migration::register();
