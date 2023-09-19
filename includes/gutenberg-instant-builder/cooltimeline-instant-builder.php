<?php

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('GCTL_PLUGIN_FILE', __FILE__);
define('GCTL_PLUGIN_URL', plugin_dir_url(GCTL_PLUGIN_FILE));
define('GCTL_PLUGIN_DIR', plugin_dir_path(__FILE__));

define("GCTL_TIMELINE", __DIR__);


class CoolTimelineInstantBuilder {
   
    /** Refers to a single instance of this class. */
    private static $instance = null;
 
    /**
     * Creates or returns an instance of this class.
     *
     * @return  CoolTimelineInstantBuilder A single instance of this class.
     */
    public static function get_instance() {
 
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
 
    } // end get_instance;
 

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 *
	 * @since 1.0.0
	 */

    private function __construct() {

		// Hook: Frontend assets.
		add_action( 'enqueue_block_assets', array($this,'gctl_timeline_fronted_block_assets'));
		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets',array($this, 'ctl_timeline_editor_backend_assets'));

    } // end constructor
 
    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/
			
		function gctl_timeline_fronted_block_assets() {		
			
			// check ctl/instant-timeline exists or not					
			$id = get_the_ID();			
			if (has_block('ctl/instant-timeline', $id)) {
				wp_enqueue_style(
					'gctl-timeline-styles-css',
					GCTL_PLUGIN_URL.'dist/blocks.style.build.css',
					array()
				);
			}

		}


	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
	 * `wp-i18n`: To internationalize the block's text.
	 *
	 * @since 1.0.0
	 */
	function ctl_timeline_editor_backend_assets() { 
		// Scripts.
		wp_enqueue_script(
			'gctl-timeline-js', // Handle.
			GCTL_PLUGIN_URL.'/dist/blocks.build.js', // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ), // Dependencies, defined above.
			true // Enqueue the script in the footer.
		);

		// Styles.
		wp_enqueue_style(
			'gctl-timeline-css', // Handle.
			GCTL_PLUGIN_URL.'dist/blocks.editor.build.css', // Block editor CSS.
			array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
		);
		wp_enqueue_style(
			'gctl-timeline-styles-css',
			GCTL_PLUGIN_URL.'dist/blocks.style.build.css',
			array()
		);
		$pathToPlugin =GCTL_PLUGIN_URL.'dist/';
		wp_add_inline_script( 'wp-blocks', 'var ctl_timeline_gutenberg_path = "' .$pathToPlugin.'"', 'before');
	}
	
 
} // end class









