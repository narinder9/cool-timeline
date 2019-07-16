<?php
/*
  Plugin Name:Cool Timeline 
  Plugin URI:https://cooltimeline.com
  Description:Cool Timeline is a responsive WordPress timeline plugin that allows you to create beautiful vertical storyline. You simply create posts, set images and date then Cool Timeline will automatically populate these posts in chronological order, based on the year and date
  Version:1.8
  Author:Cool Plugins
  Author URI:https://coolplugins.net/our-cool-plugins-list/
  License:GPLv2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain:cool-timeline
 */

/** Configuration * */
if (!defined('COOL_TIMELINE_CURRENT_VERSION')){
    define('COOL_TIMELINE_CURRENT_VERSION', '1.8');
}
// define constants for further use
define('COOL_TIMELINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    define('COOL_TIMELINE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 	define( 'CT_FA_DIR', COOL_TIMELINE_PLUGIN_DIR.'/fa-icons/' );
	define( 'CT_FA_URL', COOL_TIMELINE_PLUGIN_URL.'/fa-icons/'  );

//if (!class_exists('CoolTimeline')) {

    class CoolTimeline {

        /**
         * Construct the plugin object
         */
        public function __construct() {
       
            $this->plugin_path = COOL_TIMELINE_PLUGIN_DIR;
          /*
            Including required files
          */
          add_action('plugins_loaded', array($this, 'clt_include_files'));
      
        //gutenberg block integartion
        require COOL_TIMELINE_PLUGIN_DIR . 'includes/gutenberg-block/ctl-block.php';

         // Cool Timeline all hooks integrations
         if(is_admin()){
            $plugin = plugin_basename(__FILE__);
            // plugin settings links hook
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));
            // integrated shortcode generator on text editor
          	add_action( 'after_setup_theme', array($this , 'ctl_add_tinymce' ) );
           // save extra story meta for timeline sorting
            add_action( 'save_post',array($this,'ctl_save_story_meta'), 10, 3 );
           
            add_action( 'add_meta_boxes_cool_timeline',array($this,'ctl_buy_pro_metabox'));
          }

            //loading plugin translation files
            add_action('plugins_loaded', array($this, 'clt_load_plugin_textdomain'));
		        //Fixed bridge theme confliction using this action hook
            add_action( 'wp_print_scripts', array($this,'ctl_deregister_javascript'), 100 );
        
            add_action( 'init', array($this,'ctlfree_migrate_stories' ) );
          }
		
        /*
          Including required files
        */
      public function clt_include_files(){

           // cooltimeline post type
            add_action( 'init', array($this, 'include_files' ) );
            require COOL_TIMELINE_PLUGIN_DIR . 'includes/cool-timeline-posttype.php';
            $cool_timeline_posttype = new CoolTimelinePosttype();

            /*
             *  Frontend files
             */
             // contains helper funciton for timeline
            include_once COOL_TIMELINE_PLUGIN_DIR . 'includes/ctl-helper-functions.php';

            //Cool Timeline Main shortcode
            require COOL_TIMELINE_PLUGIN_DIR . 'includes/cool-timeline-shortcode.php';
            
            new CoolTimelineShortcode();
            add_action('wp_enqueue_scripts','ctl_custom_style');

            /*
              Loaded Backend files only 
            */
            if(is_admin()){
            //metaboxes for cooltimeline post type
          //  include_once COOL_TIMELINE_PLUGIN_DIR . 'includes/metaboxes.php';
            
            // including timeline stories meta boxes class 
            require_once COOL_TIMELINE_PLUGIN_DIR . "meta-box-class/my-meta-box-class.php";
            require COOL_TIMELINE_PLUGIN_DIR .'/includes/ctl-meta-fields.php';
            clt_meta_boxes();
            /*
             Plugin Settings panel 
            */
            require_once(plugin_dir_path(__FILE__) ."admin-page-class/admin-page-class.php");
           
            require COOL_TIMELINE_PLUGIN_DIR.'includes/cool-timeline-settings.php';
            // Initialize Settings
            ctl_option_panel();

            // icon picker for post type
            require COOL_TIMELINE_PLUGIN_DIR.'fa-icons/fa-icons-class.php';
            new Ctl_Fa_Icons();
            
            //feedback notice in  admin side 
             require COOL_TIMELINE_PLUGIN_DIR .'/includes/cool-tm-feedback-notice.php';
             new CoolTMFeedbackNotice();

            }
			 require COOL_TIMELINE_PLUGIN_DIR .'/gutenberg-instant-builder/cooltimeline-instant-builder.php';
			CoolTimelineInstantBuilder::get_instance();
      }
      
    /**
     * Save post metadata when a story is saved.
     *
     * @param int $post_id The post ID.
     * @param post $post The post object.
     * @param bool $update Whether this is an existing post being updated or not.
     */
    function ctl_save_story_meta( $post_id, $post, $update ) {
      $post_type = get_post_type($post_id);
      // If this isn't a 'cool_timeline' post, don't update it.
      if ( "cool_timeline" != $post_type ) return;
      // - Update the post's metadata.
      if ( isset($_POST['ctl_story_date'] ) ) {
          $story_timestamp= ctlfree_generate_custom_timestamp($_POST['ctl_story_date']);
          update_post_meta($post_id,'ctl_story_timestamp',$story_timestamp );
        
          }
    }

      // loading language files
       function clt_load_plugin_textdomain() {
              $rs = load_plugin_textdomain('cool-timeline', FALSE, basename(dirname(__FILE__)) . '/languages/');
          }

        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=cool_timeline_page">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }
 
       /*
        * Fixed Bridge theme confliction
        */
        function ctl_deregister_javascript() {

            if(is_admin()) {
                $screen = get_current_screen();
                if ($screen->base == "toplevel_page_cool_timeline_page") {
                    wp_deregister_script('default');
                }
            }
        }
        
		  public function include_files() {
        //flush rewrite rules after activation
        if ( get_option( 'ctl_flush_rewrite_rules_flag' ) ) {
            flush_rewrite_rules();
           delete_option( 'ctl_flush_rewrite_rules_flag' );
        }

            // Files specific for the front-end
            if ( ! is_admin() ) {
                // Load template tags (always last)
                include COOL_TIMELINE_PLUGIN_DIR .'fa-icons/includes/template-tags.php';
            }
        }

    // integrated shortcode generator button in text editor
		public function ctl_add_tinymce() {
         global $typenow;
         if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
              return;
        }
        if(get_cpt()=="cool_timeline"){
          return ;
        }
       if ( get_user_option('rich_editing') == 'true' ) {
            add_filter('mce_external_plugins', array($this, 'ctl_add_tinymce_plugin'));
            add_filter('mce_buttons', array($this, 'ctl_add_tinymce_button'));
          }    

        }

        //loading tinymce plugin  js
			public function ctl_add_tinymce_plugin( $plugin_array ) {
            $plugin_array['cool_timeline'] =COOL_TIMELINE_PLUGIN_URL.'js/admin-js/tinymce-custom-btn.js';
			       return $plugin_array;
			}
      //added shortcode button in array
		function ctl_add_tinymce_button( $buttons ) {
            array_push( $buttons, 'cool_timeline_btn' );
			return $buttons;
			}
          
       	/**
         * Activating plugin and adding some info
         */
        public static function activate() {
              update_option("cool-timelne-v",COOL_TIMELINE_CURRENT_VERSION);
              update_option("cool-timelne-type","FREE");
              update_option("cool-timelne-installDate",date('Y-m-d h:i:s') );
              update_option("cool-timelne-ratingDiv","no");
              update_option("ctl_flush_rewrite_rules_flag",true);
        }

	  // run migration from old version since version 1.7
    function ctlfree_migrate_stories(){
      if(get_option('ctl-upgraded')!==false){
          return;
      }
      $ctl_version = get_option('cool-timelne-v');
      $ctl_type = get_option('cool-timelne-type');
      if(version_compare( $ctl_version,'1.7', '<' )){
         ctl_run_migration();   
      }
      update_option('ctl-upgraded','yes');
  }

        /**
         * Deactivate the plugin
         */
        public static function deactivate() {
            // Do nothing
        } 

     /**
     * Add meta box
     *
     * @param post $post The post object
     */
    function ctl_buy_pro_metabox( $post ){
      add_meta_box(
              'ctl-pro-banner',
              __( 'Please Give Us Your Feedback','cool-timeline'),
              array($this,'ctl_buypro_section'),
              'cool_timeline',
              'side',
              'low'
          );
   }
   // buy pro meta section in cool timeline post type
  function ctl_buypro_section($post, $callback){
      $pro_add='';
      $pro_add .='<div><div>'.
      __('If you find our plugin and support helpful.<br>Please rate and review us,It helps us grow <br>and improve our services','cool-timeline').'.<br>
      <a target="_blank" href="https://wordpress.org/support/plugin/cool-timeline/reviews/#new-post"><img src="https://res.cloudinary.com/cooltimeline/image/upload/v1504097450/stars5_gtc1rg.png"></a><br>
      <a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/cool-timeline/reviews/#new-post">'.__('Submit Review ★★★★★','cool-timeline').'</a>
      </div>';
      $pro_add .='</div><hr><div><strong class="ctl_add_head">'.__('Upgrade to Pro version','cool-timeline').'</strong>
      <a target="_blank" href="https://cooltimeline.com/demo/">
      <img src="https://res.cloudinary.com/cooltimeline/image/upload/v1503490189/website-images/cool-timeline-demos.png"></a> 
      <a target="_blank" href="https://1.envato.market/7QLxy">
      <img src="https://res.cloudinary.com/cooltimeline/image/upload/v1468242487/6-buy-cool-timeline_vabou4.png"></a></div>';
     $pro_add.=' <h2 class="ctl_add_head">Cool Timeline PRO features</h2>
     <ul style="list-style:disc;margin: 2px 16px;">
     <li>40+ Timeline Designs</li>
     <li>Colors & Typography</li>
     <li>Video, Images & Slider</li>
         <li>Custom Story Color</li>
         <li>Multiple Timelines</li>
         <li>Shortcode Generator</li>
         <li>Gutenberg / Elementor / WPBakery</li>
         <li>Custom Label / Text</li>
         <li>ASC / DESC Order</li>
         <li>Category Filters</li>
            <li>Post Timeline</li>
            <li>Ajax Load More / Pagination</li>
            <li>Scrolling Navigation</li>
            <li>Icons In Timeline</li>
            <li>HTML / Links / Read More</li>
            <li>Date Format</li>
            <li>Animations</li>
            <li>Premium Support</li>
     </ul>';
      echo $pro_add ;
  }



  
 } //end class

    //}

   // get current page post type
    function get_cpt() {
        global $post, $typenow, $current_screen;
       if ( $post && $post->post_type )
            return $post->post_type;
       elseif( $typenow )
            return $typenow;
       elseif( $current_screen && $current_screen->post_type )
            return $current_screen->post_type;
        elseif( isset( $_REQUEST['post_type'] ) )
            return sanitize_key( $_REQUEST['post_type'] );
       return null;
      }


    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('CoolTimeline', 'activate'));
    register_deactivation_hook(__FILE__, array('CoolTimeline', 'deactivate'));

    // instantiate the plugin class
    $cool_timeline = new CoolTimeline();
