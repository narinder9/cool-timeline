<?php
/*
  Plugin Name:Cool Timeline 
  Plugin URI:http://www.cooltimeline.com
  Description: Cool Timeline is a responsive wordpress plugin that allows you to create beautiful verticle storyline. You simply create posts, set images and date then Cool Timeline will automatically populate these posts in chronological order, based on the year and date
  Version:1.0.8
  Author: Narinder singh
  Author URI:http://www.cooltimeline.com
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain: cool_timeline
 */

/*
  Copyright 2015  Narinder singh (email :narinder99143@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 * 
 */



/** Configuration * */
if (!defined('COOL_TIMELINE_VERSION_CURRENT'))
    define('COOL_TIMELINE_VERSION_CURRENT', '1.0.8');
     define('COOL_TIMELINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
     define('COOL_TIMELINE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if (!class_exists('Cool_Timeline')) {

    class Cool_Timeline {

        /**
         * Construct the plugin object
         */
        public function __construct() {
            // Initialize Settings
            $this->plugin_path = plugin_dir_path(__FILE__);
         
            // cooltimeline post type
           require plugin_dir_path(__FILE__) . 'includes/cool_timeline_posttype.php';
            $cool_timeline_posttype = new CoolTimeline_Posttype();

            //metaboxes for cooltimeline post type
            require plugin_dir_path(__FILE__) . 'includes/metaboxes.php';

            /*
             * View
             */
            require plugin_dir_path(__FILE__) . 'includes/cool_timline_template.php';
            new CoolTimeline_Template();

            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));
            
			// add a tinymce button that generates our shortcode for the user
			add_action( 'admin_head', array( &$this , 'ctl_add_tinymce' ) );
			add_image_size( 'ctl_avatar', 250, 250,true ); // Hard crop left top
            // Register a new custom image size
            add_image_size('cool_timeline_custom_size', '350', '120', true);
            

            //include the main class file
            require_once(plugin_dir_path(__FILE__) ."admin-page-class/admin-page-class.php");
            $this->ctl_option_panel();
        }

       

// END public static funct
        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=cool_timeline_page">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }
      function ctl_option_panel() {

            /**
             * configure your admin page
             */
            $config = array(
                'menu' => array('top' => 'cool_timeline'), //sub page to settings page
                'page_title' => __('Cool Timeline', 'apc'), //The name of this page 
                'capability' => 'edit_themes', // The capability needed to view the page 
                'option_group' => 'cool_timeline_options', //the name of the option to create in the database
                'id' => 'cool_timeline_page', // meta box id, unique per page
                'fields' => array(), // list of fields (can be added by field arrays)
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );

            /**
             * instantiate your admin page
             */
            $options_panel = new BF_Admin_Page_Class($config);
            $options_panel->OpenTabs_container('');

            /**
             * define your admin page tabs listing
             */
            $options_panel->TabsListing(array(
                'links' => array(
                    'options_1' => __('General Settings', 'apc'),
					'options_2' => __('Style Settings', 'apc'),
					'options_3' => __('Typography Settings', 'apc'),
                )
            ));

            /**
             * Open admin page first tab
             */
    $options_panel->OpenTab('options_1');

            /**
             * Add fields to your admin page first tab
             * 
             * Simple options:
             * input text, checbox, select, radio 
             * textarea
             */
            //title
        $options_panel->Title(__("General Settings", "apc"));
            //An optionl descrption paragraph
         //   $options_panel->addParagraph(__("This is a simple paragraph", "apc"));
            //text field
            $options_panel->addText('title_text', array('name' => __('Title ', 'apc'), 'std' => 'Cool Timeline', 'desc' => __('', 'apc')));

            //select field
            $options_panel->addSelect('title_tag', array('h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6'), array('name' => __('Title Heading Tag ', 'apc'), 'std' => array('h1'), 'desc' => __('', 'apc')));

            $options_panel->addText('post_per_page', array('name' => __('Number of post to display ', 'apc'), 'std' => 10, 'desc' => __('', 'apc')));
			$options_panel->addText('content_length', array('name' => __('Content Length ', 'apc'), 'std' => 50, 'desc' => __('', 'apc')));
			//Image field
		
			$options_panel->addImage('user_avatar',array('name'=> __('Profile Image','apc'), 'desc' => __('','apc')));

          $options_panel->addRadio('desc_type', array('short' => 'Short (Default)',
              'full' => 'Full (with HTML)'), array('name' => __('Stories Description?', 'apc'), 'std' => array('short'), 'desc' => __('', 'apc')));

          $options_panel->addRadio('display_readmore', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Display read more ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));
			
			$options_panel->addRadio('posts_orders', array('DESC' => 'DESC',
                'ASC' => 'ASC'), array('name' => __('Stories Order ?', 'apc'), 'std' => array('DESC'), 'desc' => __('', 'apc')));
			
			$options_panel->addRadio('disable_months', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Disable Stoires Months ?', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));
			
			$options_panel->addRadio('title_alignment', array('left' => 'Left',
                'center' => 'Center','right'=>'Right'), array('name' => __('Title Alignment ?', 'apc'), 'std' => array('center'), 'desc' => __('', 'apc')));
			//select field
           
           
			 /**
			   *Editor options:
			   *WYSIWYG (tinyMCE editor)
			   *Syntax code editor (css,html,js,php)
			   */
			 
			  //title
			//  $options_panel->Title(__("Editor Options","apc"));
			  //wysiwyg field
			  $options_panel->addWysiwyg('no_posts',array('name'=> __('No Timeline Posts content','apc'), 'desc' => __('','apc')));
			

		   $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
		$options_panel->OpenTab('options_3');
			
			//title
            $options_panel->Title(__("Typography Settings", "apc"));
            $options_panel->addTypo('main_title_typo', array('name' => __("Main Title", "apc"), 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('', 'apc')));
            $options_panel->addTypo('post_title_typo', array('name' => __("Post Title", "apc"), 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('', 'apc')));
            $options_panel->addTypo('post_content_typo', array('name' => __("Post Content", "apc"), 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('', 'apc')));
             $options_panel->CloseTab();


            /**
             * Open admin page third tab
             */
    $options_panel->OpenTab('options_2');
            $options_panel->Title(__("Style Settings", "apc"));
            /**
             * To Create a Conditional Block first create an array of fields (just like a repeater block
             * use the same functions as above but add true as a last param
             */
         //   $Conditinal_fields[] = $options_panel->addText('con_text_field_id', array('name' => __('My Text ', 'apc')), true);
            $Conditinal_fields[] =$options_panel->addColor('bg_color', array('name' => __('Background Color', 'apc')), true);
            $Conditinal_fields[] = $options_panel->addImage('bg_img', array('name' => __('Background Image', 'apc')), true);
         
            /**
             * Then just add the fields to the repeater block
             */
            //conditinal block 
            $options_panel->addCondition('background', array(
                'name' => __('Container Background ', 'apc'),
                'desc' => __('', 'apc'),
                'fields' => $Conditinal_fields,
                'std' => false
            ));
         
           //Color field
             $options_panel->addColor('content_bg_color',array('name'=> __('Post Background Color','apc'),'std'=>array('#000000'), 'desc' => __('','apc')));
            
       //     $options_panel->addColor('content_color',array('name'=> __('Content font color','apc'),'std'=>array('#000000'), 'desc' => __('','apc')));
             
            $options_panel->addColor('circle_border_color',array('name'=> __('Circle Color','apc'),'std'=>array('#000000'), 'desc' => __('','apc')));
            
            $options_panel->addColor('line_color',array('name'=> __('Line Color','apc'),'std'=>array('#000000'), 'desc' => __('','apc')));
				//Color field
            $options_panel->addColor('first_post',array('name'=> __('First Post','apc'),'std'=>array('#000'), 'desc' => __('','apc')));
            $options_panel->addColor('second_post',array('name'=> __('Second Post','apc'),'std'=>array('#000'), 'desc' => __('','apc')));
           // $options_panel->addColor('third_post',array('name'=> __('Third Post','apc'),'std'=>array('#000'), 'desc' => __('','apc')));
			 
		
		
  //Now Just for the fun I'll add Help tabs
            $options_panel->HelpTab(array(
                'id' => 'tab_id',
                'title' => __('My help tab title', 'apc'),
                'content' => '<p>' . __('This is my Help Tab content', 'apc') . '</p>'
            ));
            $options_panel->HelpTab(array(
                'id' => 'tab_id2',
                'title' => __('My 2nd help tab title', 'apc'),
                'callback' => 'help_tab_callback_demo'
            ));

            //help tab callback function
            function help_tab_callback_demo() {
                echo '<p>' . __('This is my 2nd Help Tab content from a callback function', 'apc') . '</p>';
            }

        }

        /* TinyMCE Button Functions */
			 
        // register our button for the custom post type
         public function ctl_add_tinymce() {
            global $typenow;
            // only on Post Type: post and page
            if( ! in_array( $typenow, array( 'page' , 'post' ) ) )
                    return;
            add_filter( 'mce_external_plugins', array( &$this , 'ctl_add_tinymce_plugin' ) );
            add_filter( 'mce_buttons', array( &$this , 'ctl_add_tinymce_button' ) );
                 }

         // inlcude the js for tinymce
        public function ctl_add_tinymce_plugin( $plugin_array ) {
            $plugin_array['cool_timeline'] = plugins_url( 'cool-timeline/includes/js/cooltimeline-button-script.js' );
            // Print all plugin js path
            // var_dump( $plugin_array );
            return $plugin_array;
    }

        // Add the button key for address via JS
        function ctl_add_tinymce_button( $buttons ) {
            array_push( $buttons, 'cool_timeline_shortcode_button' );
            // Print all buttons
            // var_dump( $buttons );
            return $buttons;
    }
        // end tinymce button functions           
            
       	/**
         * Activate the plugin
         */
        public static function activate() {
            // Do nothing
        }

		// END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate() {
            // Do nothing
        }     
            
        } //end class

    }

    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('Cool_Timeline', 'activate'));
    register_deactivation_hook(__FILE__, array('Cool_Timeline', 'deactivate'));

    // instantiate the plugin class
    $cool_timeline = new Cool_Timeline();
    ?>
