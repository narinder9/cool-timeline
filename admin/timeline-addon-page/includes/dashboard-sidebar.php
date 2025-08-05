<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 *
 * Addon dashboard sidebar.
 */

if ( ! isset( $this->main_menu_slug ) ) {
	return false;
}

 $cool_support_email = 'https://coolplugins.net/support/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=support&utm_content=dashboard';
?>

 <div class="cool-body-right">
	<a href="https://coolplugins.net/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=author_page&utm_content=dashboard" target="_blank">
		<img src="<?php echo esc_url( plugin_dir_url( $this->addon_file ) ) . '/assets/coolplugins-logo.png'; ?>" alt="<?php esc_attr__( 'Cool Plugins Logo', 'cool-timeline' ); ?>">
	</a>
	<ul>
	  <li><?php echo esc_html__( 'Cool Plugins develops best timeline plugins for WordPress.', 'cool-timeline' ); ?></li>
	  <li><?php printf( esc_html__( 'Our timeline plugins have %1$s50000+%2$s active installs.', 'cool-timeline' ), '<b>', '</b>' ); ?></li>
	  <li><?php echo esc_html__( 'For any query or support, please contact plugin support team.', 'cool-timeline' ); ?>
	  <br><br>
	  <a href="<?php echo esc_url( $cool_support_email ); ?>" target="_blank" class="button button-secondary"><?php echo esc_html__( 'Premium Plugin Support', 'cool-timeline' ); ?></a>
	  <br><br>
	  </li>
   </ul>
</div>

</div><!-- End of main container -->
