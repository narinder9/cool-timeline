<?php

// Hook scripts function into block editor hook
add_action( 'enqueue_block_editor_assets', 'ctl_gutenberg_scripts' );

function ctl_gutenberg_scripts() {
	$blockPath = '/dist/block.js';
	$stylePath = '/dist/block.css';

	// Enqueue the bundled block JS file
	wp_enqueue_script(
		'ctl-block-js',
		plugins_url( $blockPath, __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor','wp-data','wp-api' ),
		//array('wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-api'),
		filemtime( plugin_dir_path(__FILE__) . $blockPath )
	);
	// Enqueue frontend and editor block styles
	wp_enqueue_style(
		'ctl-block-css',
		plugins_url( $stylePath, __FILE__ ),
		'',
		filemtime( plugin_dir_path(__FILE__) . $stylePath )
	);
	wp_localize_script( 'ctl-block-js', 'ctlUrl', array(CTL_PLUGIN_URL) );

}

/**
 * Block Initializer.
 */
add_action( 'plugins_loaded', function () {
	if ( function_exists( 'register_block_type' ) ) {
		// Hook server side rendering into render callback
		register_block_type(
			'cool-timleine/shortcode-block', array(
				'render_callback' => 'ctl_block_callback',
				'attributes'	  => array(
					'layout'	 => array(
						'type' => 'string',
						'default' =>'vertical',
					),
					'skin'	 => array(
						'type' => 'string',
						'default' =>'default',
					),
					'dateformat'	=> array(
						'type'	=> 'string',
						'default' => 'F j',
					),
					'postperpage'	=> array(
						'type'	=> 'string',
						'default' => 10,
					),
					'slideToShow'=> array(
						'type' => 'string',
						'default' => 4
					),
					'animation'	 => array(
						'type' => 'string',
						'default' =>'none',
					),
					'icons'	 => array(
						'type' => 'string',
						'default' =>'NO',
					),
					'order'=> array(
						'type' => 'string',
						'default' =>'DESC',
					),
					'storycontent'=> array(
						'type' => 'string',
						'default' =>'short',
					)
				),
			)
		);
	}
} );

/**
 * Block Output.
 */
function ctl_block_callback( $attr ) {
	extract( $attr );
	if ( isset( $layout ) ) {
		$showpost=$layout=='horizontal'?$slideToShow:$postperpage;
		$shortcode_string = '[cool-timeline layout="%s" skin="%s"
		show-posts="%s" date-format="%s" icons="%s" animation="%s" order="%s" story-content="%s"]';
		 $shortcode= sprintf( $shortcode_string, $layout, $skin, 
		$showpost,$dateformat,$icons,$animation,$order,$storycontent);
		return $shortcode;
	}
}
