<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * 
 * Addon dashboard sidebar.
 */

 if( !isset($this->main_menu_slug) ):
    return false;
 endif;

 $cool_support_email = "https://coolplugins.net/support/";
?>

 <div class="cool-body-right">
    <a href="https://coolplugins.net" target="_blank"><img src="<?php echo esc_url(plugin_dir_url( $this->addon_file )) .'/assets/coolplugins-logo.png'; ?>"></a>
    <ul>
      <li>Cool Plugins develops best timeline plugins for WordPress.</li>
      <li>Our timeline plugins have <b>50000+</b> active installs.</li>
      <li>For any query or support, please contact plugin support team.
      <br><br>
      <a href="<?php echo esc_attr( $cool_support_email ); ?>" target="_blank" class="button button-secondary">Premium Plugin Support</a>
      <br><br>
      </li>
      <li>We also provide <b>timeline plugins customization</b> services.
      <br><br>
      <a href="<?php echo esc_attr( $cool_support_email ); ?>" target="_blank" class="button button-primary">Hire Developer</a>
      <br><br>
      </li>
   </ul>
    </div>

</div><!-- End of main container-->