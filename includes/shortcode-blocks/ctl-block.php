<?php

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Hook scripts function into block editor hook
add_action( 'enqueue_block_editor_assets', 'ctl_gutenberg_scripts' );

function ctl_gutenberg_scripts() {
	$blockPath = '/dist/block.js';
	$stylePath = '/dist/block.css';

	// Enqueue the bundled block JS file
	wp_enqueue_script(
		'ctl-block-js',
		plugins_url( $blockPath, __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-data', 'wp-api' ),
		filemtime( plugin_dir_path( __FILE__ ) . $blockPath )
	);

	// Enqueue frontend and editor block styles
	wp_enqueue_style(
		'ctl-block-css',
		plugins_url( $stylePath, __FILE__ ),
		'',
		filemtime( plugin_dir_path( __FILE__ ) . $stylePath )
	);

	// Localize script for safe URL usage
	wp_localize_script( 'ctl-block-js', 'ctlUrl', array( esc_url( CTL_PLUGIN_URL ) ) ); // Escape URL
}

/**
 * Block Initializer.
 */
add_action(
	'plugins_loaded',
	function () {
		if ( function_exists( 'register_block_type' ) ) {
			// Hook server side rendering into render callback
			register_block_type(
				'cool-timleine/shortcode-block',
				array(
					'render_callback' => 'ctl_block_callback',
					'attributes'      => array(
						'layout'       => array(
							'type'    => 'string',
							'default' => 'default',
						),
						'skin'         => array(
							'type'    => 'string',
							'default' => 'default',
						),
						'dateformat'   => array(
							'type'    => 'string',
							'default' => 'F j',
						),
						'postperpage'  => array(
							'type'    => 'string',
							'default' => 10,
						),
						'slideToShow'  => array(
							'type'    => 'string',
							'default' => '',
						),
						'animation'    => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'icons'        => array(
							'type'    => 'string',
							'default' => 'NO',
						),
						'order'        => array(
							'type'    => 'string',
							'default' => 'DESC',
						),
						'storycontent' => array(
							'type'    => 'string',
							'default' => 'short',
						),
					),
				)
			);
		}
	}
);

/**
 * Block Output.
 */
function ctl_block_callback( $attr ) {
	// Sanitize attributes
	$layout = isset( $attr['layout'] ) ? sanitize_text_field( $attr['layout'] ) : 'default'; // Sanitize layout

	extract( $attr );
	if ( isset( $layout ) ) {
		$shortcode_string = '[cool-timeline layout="%s" skin="%s"
		show-posts="%s" date-format="%s" icons="%s" animation="%s" order="%s" story-content="%s" items="%s"]';
		$shortcode        = sprintf(
			$shortcode_string,
			$layout,
			$skin,
			$postperpage,
			$dateformat,
			$icons,
			$animation,
			$order,
			$storycontent,
			$slideToShow
		);
		return $shortcode;
	}
}
