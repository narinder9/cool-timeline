<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * This php file render HTML header for addons dashboard page
 */
    if( !isset( $this->main_menu_slug ) ):
        return;
    endif;

    $cool_plugins_docs = "https://docs.coolplugins.net/";
    $cool_plugins_more_info ='https://cooltimeline.com/demo/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=demo&utm_content=dashboard';
?>

<div id="cool-plugins-container" class="<?php echo esc_attr($this->main_menu_slug) ; ?>">
    <div class="cool-header">
        <h2 style=""><?php echo esc_html($this->dashboar_page_heading); ?></h2>
    <a href="<?php echo esc_url($cool_plugins_docs) ?>" target="_docs" class="button">Docs</a>
    <a href="<?php echo esc_url($cool_plugins_more_info) ?>" target="_info" class="button">Demos</a>
</div>