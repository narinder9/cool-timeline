<?php


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'enqueue_block_assets', 'timeline_block_editor_assets' );

function timeline_block_editor_assets() {

	$id = get_the_ID();

	if ( has_block( 'cp-timeline/content-timeline', $id ) ) {
		wp_enqueue_style(
			'cp_timeline-cgb-style', // Handle.
			plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
			is_admin() ? array( 'wp-editor' ) : null,
			null
		);
		wp_enqueue_script( 'ctl_block_common_script' );
	} else {
		if ( ! is_admin() ) {
			wp_dequeue_style( 'cp_timeline-cgb-style' );
		}
	}
}

add_action( 'enqueue_block_editor_assets', 'editor_side_css' );
function editor_side_css() {
		// Common Editor style.
		wp_enqueue_style(
			'timeline-block
		-block-common-editor-css', // Handle.
			plugin_dir_url( __FILE__ ) . '../assets/common-block-editor.css', // Block editor CSS.
			array( 'wp-edit-blocks' )// Dependency to include the CSS after it.
		);
		wp_enqueue_style( 'ctl_block_swiper_style' );
		wp_enqueue_script( 'ctl_block_swiper_script' );
}

add_action( 'wp_head', 'timeline_block_load_post_assets' );
function timeline_block_load_post_assets() {
	global $post;
	$this_post = $post;
	if ( ! is_object( $this_post ) ) {
		return;
	}
	$this_post = apply_filters( 'timeline-block_post_for_stylesheet', $this_post );
	if ( ! is_object( $this_post ) ) {
		return;
	}

	if ( ! isset( $this_post->ID ) ) {
		return;
	}

	if ( has_blocks( $this_post->ID ) && isset( $this_post->post_content ) ) {

		$blocks      = parse_blocks( $this_post->post_content );
		$page_blocks = $blocks;

		if ( ! is_array( $page_blocks ) || empty( $page_blocks ) ) {
			return;
		}
		foreach ( $page_blocks as $i => $block ) {

			if ( is_array( $block ) ) {

				if ( '' === $block['blockName'] ) {
					continue;
				}
				$default_Fonts = array( '', 'Arial', 'Helvetica', 'Times New Roman', 'Georgia' );
				if ( isset( $block['attrs']['headFontFamily'] ) ) {
					if ( ! in_array( $block['attrs']['headFontFamily'], $default_Fonts ) ) {
						$headFont = array();
						array_push( $headFont, sanitize_text_field( $block['attrs']['headFontFamily'] ) ); // Sanitize font family
						if ( isset( $block['attrs']['headFontWeight'] ) ) {
							array_push( $headFont, sanitize_text_field( $block['attrs']['headFontWeight'] ) ); // Sanitize font weight
						}
						if ( isset( $block['attrs']['headFontSubset'] ) ) {
							array_push( $headFont, sanitize_text_field( $block['attrs']['headFontSubset'] ) ); // Sanitize font subset
						}
						$head_link = 'https://fonts.googleapis.com/css?family=' . esc_attr( implode( ':', $headFont ) ) . '';
						wp_enqueue_style(
							'timeline-block-font-head', // Handle
							esc_url( $head_link ) // Escape URL
						);
					}
				}
				if ( isset( $block['attrs']['subHeadFontFamily'] ) ) {
					if ( ! in_array( $block['attrs']['subHeadFontFamily'], $default_Fonts ) ) {
						$subheadFont = array();
						array_push( $subheadFont, $block['attrs']['subHeadFontFamily'] );
						if ( isset( $block['attrs']['subHeadFontWeight'] ) ) {
							array_push( $subheadFont, $block['attrs']['subHeadFontWeight'] );
						}
						if ( isset( $block['attrs']['subHeadFontSubset'] ) ) {
							array_push( $subheadFont, $block['attrs']['subHeadFontSubset'] );
						}
						$subHead_link = 'https://fonts.googleapis.com/css?family=' . esc_attr( implode( ':', $subheadFont ) ) . '';
						wp_enqueue_style(
							'timeline-block-font-subHead', // Handle
							esc_url( $subHead_link ) // Escape URL
						);
					}
				}
				if ( isset( $block['attrs']['dateFontFamily'] ) ) {
					if ( ! in_array( $block['attrs']['dateFontFamily'], $default_Fonts ) ) {
						$dateFont = array();
						array_push( $dateFont, $block['attrs']['dateFontFamily'] );
						if ( isset( $block['attrs']['dateFontWeight'] ) ) {
							array_push( $dateFont, $block['attrs']['dateFontWeight'] );
						}
						if ( isset( $block['attrs']['dateFontSubset'] ) ) {
							array_push( $dateFont, $block['attrs']['dateFontSubset'] );
						}
						$date_font_link = 'https://fonts.googleapis.com/css?family=' . esc_attr( implode( ':', $dateFont ) ) . '';
						wp_enqueue_style(
							'timeline-block-font-date', // Handle
							$date_font_link
						);
					}
				}
			}
		}
	}

}


function cp_timeline_cgb_block_assets() {
	wp_register_style(
		'cp_timeline-cgb-style', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
		is_admin() ? array( 'wp-editor' ) : null,
		null
	);

	wp_register_script(
		'cp_timeline-cgb-block-js', // Handle.
		plugins_url( 'dist/blocks.build.js', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		null,
		true
	);

	wp_register_style(
		'cp_timeline-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
		array( 'wp-edit-blocks' ),
		null
	);
	wp_register_script( 'ctl_block_common_script', CTL_PLUGIN_URL . 'includes/cool-timeline-block/assets/js/common.js', array( 'jquery' ), CTL_V, false );
	wp_localize_script(
		'cp_timeline-cgb-block-js',
		'cgbGlobal',
		array(
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => esc_url( plugin_dir_url( __DIR__ ) ), // Escape URL
		)
	);
	if ( function_exists( 'register_block_type' ) ) {

		register_block_type(
			'cp-timeline/content-timeline',
			array(
				'api_version'   => 2,
				// Enqueue blocks.style.build.css on both frontend & backend.
				'style'         => 'cp_timeline-cgb-style',
				// Enqueue blocks.build.js in the editor only.
				'editor_script' => 'cp_timeline-cgb-block-js',
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'  => 'cp_timeline-cgb-block-editor-css',
			)
		);
		register_block_type(
			'cp-timeline/content-timeline-child',
			array(
				'api_version' => 2,
			)
		);
	}
}

// Hook: Block assets.
add_action( 'init', 'cp_timeline_cgb_block_assets' );
