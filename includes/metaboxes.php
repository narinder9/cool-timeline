<?php
add_action( 'admin_head','metaboxes_options_array' );  
add_action( 'admin_menu', 'weothemes_metabox_add', 10 ); // Triggers cool_timeline_metabox_create()
add_action( 'admin_print_styles', 'ct_custom_enqueue_css', 10 );
add_action( 'admin_enqueue_scripts', 'ct_custom_enqueue', 10, 1 );

add_action( 'edit_post', 'weothemes_metabox_handle', 10 ); //save postmeta


function metaboxes_options_array()
		{
// Woo Metabox Options
$ct_metaboxes = array();
if( get_post_type() == 'cool_timeline' || !get_post_type() ){
	    $ptype=get_post_type();

//        $ct_metaboxes[] = array('name' => 'text',
//            'label' => __('text', 'cool_timeline'),
//            'type' => 'text',
//            'desc' => __('Upload an image or enter an URL.', 'cool_timeline'));

        $ct_metaboxes[] = array('name' => 'image_container_type',
            'std' => __('default', 'cool_timeline'),
            'label' => __('Image Size', 'cool_timeline'),
            'type' => 'select',
            'desc' => __('You can update these size from admin plugin option panel', 'cool_timeline'),
            'options' => array(
                 'large' => 'Full',
               // 'Medium' => 'Medium',
                'small' => 'small'));
    }

	if ( get_option('ct_metaboxes') != $ct_metaboxes) update_option('ct_metaboxes',$ct_metaboxes);   

}


/**
 * cool_timeline_metabox_add()
 *
 * Add meta boxes for the WooFramework's custom fields.
 * 
 * @access public
 * @since 1.0.0
 * @return void
 */
function weothemes_metabox_add() {
    $ct_metaboxes = get_option('ct_metaboxes', array());
    if (function_exists('add_meta_box')) {
        if (function_exists('get_post_types')) {
            $custom_post_list = get_post_types();

            // Get the theme name for use in multiple meta boxes.
            $theme_name ='Cool Timeline';

            foreach ($custom_post_list as $type) {

                $settings = array(
                    'id' => 'weo-meta-settings',
                    'title' => sprintf(__('%s Custom Settings', 'cool_timeline'), $theme_name),
                    'callback' => 'ct_metabox_create',
                    'page' => $type,
                    'priority' => 'normal',
                    'callback_args' => ''
                );

                // Allow child themes/plugins to filter these settings.
                $settings = apply_filters('weothemes_metabox_settings', $settings, $type, $settings['id']);
                add_meta_box($settings['id'], $settings['title'], $settings['callback'], $settings['page'], $settings['priority'], $settings['callback_args']);
            }

//add_meta_box('weo-side-settings',sprintf(__('%s Custom Settings', 'cool_timeline'),'ct_metabox_create','cool_timeline','side','low');
			add_meta_box(
				'ctl-pro-banner',
				__( 'Support Cool Timeline','cool-timeline'),
				'ctl_right_section',
				'cool_timeline',
				'side',
				'low'
			);
        }/* else {
          add_meta_box( 'weo-settings', sprintf( __( '%s Custom Settings', 'cool_timeline' ), $theme_name ), 'ct_metabox_create', 'post', 'normal' );
          add_meta_box( 'weo-settings', sprintf( __( '%s Custom Settings', 'cool_timeline' ), $theme_name ), 'ct_metabox_create', 'page', 'normal' );
          } */
    }
}


function ctl_right_section($post, $callback){
	global $post;
	$pro_add='<div>
	<strong class="ctl_add_head">Leave A Review</strong>';
	$pro_add .='<div><a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/cool-timeline"><img src="'.COOL_TIMELINE_PLUGIN_URL.'/images/stars5.png"></a></div>';

	$pro_add .='</div><hr><div><strong class="ctl_add_head">Upgrade to Pro version</strong><a target="_blank" href="http://www.cooltimeline.com"><img src="'.COOL_TIMELINE_PLUGIN_URL.'/images/7-cool-timeline-demos.png"></a> <a target="_blank" href="https://codecanyon.net/item/cool-timeline-pro-wordpress-responsive-timeline-plugin/17046256?ref=CoolHappy"><img src="'.COOL_TIMELINE_PLUGIN_URL.'/images/6-buy-cool-timeline.png"></a></div>';
	echo $pro_add ;
}

// End cool_timeline_metabox_add()

/**
 * ct_metabox_create()
 *
 * Create the markup for the meta box.
 *
 * @access public
 * @param object $post
 * @param array $callback
 * @return void
 */
function ct_metabox_create($post, $callback) {
    global $post;
// Allow child themes/plugins to act here.
    do_action('ct_metabox_create', $post, $callback);
    $template_to_show = $callback['args'];

    $ct_metaboxes = get_option('ct_metaboxes', array());

    //var_dump( $ct_metaboxes);
// Array sanity check.
    if (!is_array($ct_metaboxes)) {
        $ct_metaboxes = array();
    }

    // Determine whether or not to display general fields.
    $display_general_fields = true;
    if (count($ct_metaboxes) <= 0) {
        $display_general_fields = false;
    }
    $output = '';
    // Add nonce for custom fields.
    $output .= wp_nonce_field('weo-custom-fields', 'weo-custom-fields-nonce', true, false);

    if ($callback['id'] == 'weo-meta-settings') {
        // Add tabs.
        $output .= '<div class="wooframework-tabs">' . "\n";
        $output .= '<ul class="tabber hide-if-no-js">' . "\n";
        if ($display_general_fields) {
           // $output .= '<li class="wf-tab-general"><a href="#wf-tab-general">' . __('Extra Settings', 'cool_timeline') . '</a></li>' . "\n";
        }
        // Allow themes/plugins to add tabs to WooFramework custom fields.
        $output .= apply_filters('wooframework_custom_field_tab_headings', '');
        $output .= '</ul>' . "\n";
    }
    if ($display_general_fields) {
        $output .= cool_timeline_metabox_create_fields($ct_metaboxes, $callback, 'general');
    }
    // Allow themes/plugins to add tabs to WooFramework custom fields.
    $output = apply_filters('wooframework_custom_field_tab_content', $output);

    $output .= '</div>' . "\n";

    echo $output;
}

// End cool_timeline_metabox_create()

/**
 * cool_timeline_metabox_create_fields()
 *
 * Create markup for custom fields based on the given arguments.
 * 
 * @access public
 * @since 5.3.0
 * @param array $metaboxes
 * @param array $callback
 * @param string $token (default: 'general')
 * @return string $output
 */
function cool_timeline_metabox_create_fields ( $metaboxes, $callback, $token = 'Extra Meta' ) {
global $post;

    if ( ! is_array( $metaboxes ) ) { return; }
	$template_to_show = $token;
	
	$output = '';
	$output .= '<div id="wf-tab-' . esc_attr( $token ) . '">' . "\n";
	$output .= '<table class="ct_metaboxes_table">'."\n";
	 foreach ( $metaboxes as $k => $ct_metabox ) {
	 
	 // Setup CSS classes to be added to each table row.
    	$row_css_class = 'woo-custom-field';
    	if ( ( $k + 1 ) == count( $metaboxes ) ) { $row_css_class .= ' last'; }
    $ct_id = 'ct_' . $ct_metabox['name'];
    	$ct_name = $ct_metabox['name'];
	
		if( isset( $ct_metabox['type'] ) && ( in_array( $ct_metabox['type'], $ct_metabox) ) ) {
			$ct_metaboxvalue = get_post_meta($post->ID,$ct_name,true);
			}
				
				
				// Make sure slashes are stripped before output.
				foreach ( array( 'label', 'desc', 'std' ) as $k ) {
					if ( isset( $ct_metabox[$k] ) && ( $ct_metabox[$k] != '' ) ) {
						$ct_metabox[$k] = stripslashes( $ct_metabox[$k] );
					}
				}
				if ( $ct_metaboxvalue == '' && isset( $ct_metabox['std'] ) ) {

        	        $ct_metaboxvalue = $ct_metabox['std'];
        	    } 
				// Add a dynamic CSS class to each row in the table.
        	    $row_css_class .= ' woo-field-type-' . strtolower( $ct_metabox['type'] );
				
				if( $ct_metabox['type'] == 'text' ) {

        	    	$add_class = ''; $add_counter = '';
        	    	if($template_to_show == 'seo'){$add_class = 'words-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>';}
        	        $output .= "\t".'<tr class="' . $row_css_class . '">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="'.esc_attr( $ct_id ).'">'.$ct_metabox['label'].'</label></th>'."\n";
        	        $output .= "\t\t".'<td><input class="ct_input_text '.$add_class.'" type="'.$ct_metabox['type'].'" value="'.esc_attr( $ct_metaboxvalue ).'" name="'.$ct_name.'" id="'.esc_attr( $ct_id ).'"/>';
        	        $output .= '<span class="ct_metabox_desc">'.$ct_metabox['desc'] .' '. $add_counter .'</span></td>'."\n";
        	        $output .= "\t".'</tr>'."\n";

        	    }
				elseif ( $ct_metabox['type'] == 'textarea' ) {

        	   		$add_class = ''; $add_counter = '';
        	    	if( $template_to_show == 'seo' ){ $add_class = 'words-count'; $add_counter = '<span class="counter">0 characters, 0 words</span>'; }
        	        $output .= "\t".'<tr class="' . $row_css_class . '">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="'.$ct_metabox.'">'.$ct_metabox['label'].'</label></th>'."\n";
        	        $output .= "\t\t".'<td><textarea class="ct_input_textarea '.$add_class.'" name="'.$ct_name.'" id="'.esc_attr( $ct_id ).'">' . esc_textarea(stripslashes($ct_metaboxvalue)) . '</textarea>';
        	        $output .= '<span class="ct_metabox_desc">'.$ct_metabox['desc'] .' '. $add_counter.'</span></td>'."\n";
        	        $output .= "\t".'</tr>'."\n";

        	    }
				  elseif ( $ct_metabox['type'] == 'select' ) {

        	        $output .= "\t".'<tr class="' . $row_css_class . '">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="' . esc_attr( $ct_id ) . '">' . $ct_metabox['label'] . '</label></th>'."\n";
        	        $output .= "\t\t".'<td><select class="ct_input_select" id="' . esc_attr( $ct_id ) . '" name="' . esc_attr( $ct_name ) . '">';
        	        $output .= '<option value="">Select option</option>';

        	        $array = $ct_metabox['options'];

        	        if( $array ) {

        	            foreach ( $array as $id => $option ) {
        	                $selected = '';

        	                if( isset( $ct_metabox['default'] ) )  {
								if( $ct_metabox['default'] == $option && empty( $ct_metaboxvalue ) ) { $selected = 'selected="selected"'; }
								else  { $selected = ''; }
							}

        	                if( $ct_metaboxvalue == $option ){ $selected = 'selected="selected"'; }
        	                else  { $selected = ''; }

        	                $output .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . $option . '</option>';
        	            }
        	        }

        	        $output .= '</select><span class="ct_metabox_desc">' . $ct_metabox['desc'] . '</span></td>'."\n";
        	        $output .= "\t".'</tr>'."\n";
        	    }
				  else if( $ct_metabox['type'] == 'info' ) {

        	        $output .= "\t".'<tr class="' . $row_css_class . '" style="background:#f8f8f8; font-size:11px; line-height:1.5em;">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="'. esc_attr( $ct_id ) .'">'.$ct_metabox['label'].'</label></th>'."\n";
        	        $output .= "\t\t".'<td style="font-size:11px;">'.$ct_metabox['desc'].'</td>'."\n";
        	        $output .= "\t".'</tr>'."\n";

        	    }
				  elseif ( $ct_metabox['type'] == 'select2' ) {

        	        $output .= "\t".'<tr class="' . $row_css_class . '">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="' . esc_attr( $ct_id ) . '">' . $ct_metabox['label'] . '</label></th>'."\n";
        	        $output .= "\t\t".'<td><select class="ct_input_select" id="' . esc_attr( $ct_id ) . '" name="' . esc_attr( $ct_name ) . '">';
        	        $output .= '<option value="">Select to return to default</option>';

        	        $array = $ct_metabox['options'];

        	        if( $array ) {

        	            foreach ( $array as $id => $option ) {
        	                $selected = '';

        	                if( isset( $ct_metabox['default'] ) )  {
								if( $ct_metabox['default'] == $id && empty( $ct_metaboxvalue ) ) { $selected = 'selected="selected"'; }
								else  { $selected = ''; }
							}

        	                if( $ct_metaboxvalue == $id ) { $selected = 'selected="selected"'; }
        	                else  {$selected = '';}

        	                $output .= '<option value="'. esc_attr( $id ) .'" '. $selected .'>' . $option . '</option>';
        	            }
        	        }

        	        $output .= '</select><span class="ct_metabox_desc">'.$ct_metabox['desc'].'</span></td>'."\n";
        	        $output .= "\t".'</tr>'."\n";
        	    }
				 elseif ( $ct_metabox['type'] == 'checkbox' ){

        	        if( $ct_metaboxvalue == 'true' ) { $checked = ' checked="checked"'; } else { $checked=''; }

        	        $output .= "\t".'<tr class="' . $row_css_class . '">';
        	        $output .= "\t\t".'<th class="ct_metabox_names"><label for="'.esc_attr( $ct_id ).'">'.$ct_metabox['label'].'</label></th>'."\n";
        	        $output .= "\t\t".'<td><input type="checkbox" '.$checked.' class="ct_input_checkbox" value="true"  id="'.esc_attr( $ct_id ).'" name="'. esc_attr( $ct_name ) .'" />';
        	        $output .= '<span class="ct_metabox_desc" style="display:inline">'.$ct_metabox['desc'].'</span></td>'."\n";
        	        $output .= "\t".'</tr>'."\n";
        	    }
				  elseif ( $ct_metabox['type'] == 'radio' ) {

        	    $array = $ct_metabox['options'];

        	    if( $array ) {

        	    $output .= "\t".'<tr class="' . $row_css_class . '">';
        	    $output .= "\t\t".'<th class="ct_metabox_names"><label for="' . esc_attr( $ct_id ) . '">' . $ct_metabox['label'] . '</label></th>'."\n";
        	    $output .= "\t\t".'<td>';

        	        foreach ( $array as $id => $option ) {
        	            if($ct_metaboxvalue == $id) { $checked = ' checked'; } else { $checked=''; }

        	                $output .= '<input type="radio" '.$checked.' value="' . $id . '" class="ct_input_radio"  name="'. esc_attr( $ct_name ) .'" />';
        	                $output .= '<span class="ct_input_radio_desc" style="display:inline">'. $option .'</span><div class="ct_spacer"></div>';
        	            }
        	            $output .= "\t".'</tr>'."\n";
        	         }
        	    } elseif ( $ct_metabox['type'] == 'images' ) {

				$i = 0;
				$select_value = '';
				$layout = '';

				foreach ( $ct_metabox['options'] as $key => $option ) {
					 $i++;

					 $checked = '';
					 $selected = '';
					 if( $ct_metaboxvalue != '' ) {
					 	if ( $ct_metaboxvalue == $key ) { $checked = ' checked'; $selected = 'woo-meta-radio-img-selected'; }
					 }
					 else {
					 	if ( isset( $option['std'] ) && $key == $option['std'] ) { $checked = ' checked'; }
						elseif ( $i == 1 ) { $checked = ' checked'; $selected = 'woo-meta-radio-img-selected'; }
						else { $checked = ''; }

					 }

						$layout .= '<div class="woo-meta-radio-img-label">';
						$layout .= '<input type="radio" id="woo-meta-radio-img-' . $ct_name . $i . '" class="checkbox woo-meta-radio-img-radio" value="' . esc_attr($key) . '" name="' . $ct_name . '" ' . $checked . ' />';
						$layout .= '&nbsp;' . esc_html($key) . '<div class="ct_spacer"></div></div>';
						$layout .= '<img src="' . esc_url( $option ) . '" alt="" class="woo-meta-radio-img-img '. $selected .'" onClick="document.getElementById(\'woo-meta-radio-img-'. esc_js( $ct_metabox["name"] . $i ) . '\').checked = true;" />';
					}

				$output .= "\t".'<tr class="' . $row_css_class . '">';
				$output .= "\t\t".'<th class="ct_metabox_names"><label for="' . esc_attr( $ct_id ) . '">' . $ct_metabox['label'] . '</label></th>'."\n";
				$output .= "\t\t".'<td class="ct_metabox_fields">';
				$output .= $layout;
				$output .= '<span class="ct_metabox_desc">' . $ct_metabox['desc'] . '</span></td>'."\n";
        	    $output .= "\t".'</tr>'."\n";

				}
				  elseif( $ct_metabox['type'] == 'upload' )
        	    {
					if( isset( $ct_metabox['default'] ) ) $default = $ct_metabox['default'];
					else $default = '';

        	    	// Add support for the WooThemes Media Library-driven Uploader Module // 2010-11-09.
        	    	if ( function_exists( 'cool_timeline_medialibrary_uploader' ) ) {

        	    		$_value = $default;

        	    		$_value = get_post_meta( $post->ID, $ct_metabox['name'], true );

        	    		$output .= "\t".'<tr class="' . $row_css_class . '">';
	    	            $output .= "\t\t".'<th class="ct_metabox_names"><label for="'.$ct_metabox['name'].'">'.$ct_metabox['label'].'</label></th>'."\n";
	    	            $output .= "\t\t".'<td class="ct_metabox_fields">'. cool_timeline_medialibrary_uploader( $ct_metabox['name'], $_value, 'postmeta', $ct_metabox['desc'], $post->ID );
	    	            $output .= '</td>'."\n";
	    	            $output .= "\t".'</tr>'."\n";

        	    	} 
        	    }
        	    
				
		}
		 $output .= '</table>'."\n\n";
    $output .= '</div><!--/#wf-tab-' . $token . '-->' . "\n\n";
    
    return $output;
} // End cool_timeline_metabox_create_fields()

 function weothemes_metabox_handle($post_id) {
    $pID = '';
    global $globals, $post;
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }
    $ct_metaboxes = get_option('ct_metaboxes', array());
     // Sanitize post ID.
    if (isset($_POST['post_ID'])) {
        $pID = intval($_POST['post_ID']);
    }


    // Don't continue if we don't have a valid post ID.
    if ($pID == 0)
        return;
    if (( get_post_type() != '' ) && ( get_post_type() != 'nav_menu_item' ) && wp_verify_nonce($_POST['weo-custom-fields-nonce'], 'weo-custom-fields')) {


        /* var_dump( $_POST);
          die(); */
        foreach ($ct_metaboxes as $k => $ct_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
            if (isset($ct_metabox['type'])) {
                $var = $ct_metabox['name'];
                // Get the current value for checking in the script.
                $current_value = '';
                $current_value = get_post_meta($pID, $var, true);
                if (isset($_POST[$var])) {
                    // Sanitize the input.
                    $posted_value = '';
                    $posted_value = $_POST[$var];
                    // If it doesn't exist, add the post meta.
                    if (get_post_meta($pID, $var) == "") {
                        add_post_meta($pID, $var, $posted_value, true);
                    }
                    // Otherwise, if it's different, update the post meta.
                    elseif ($posted_value != get_post_meta($pID, $var, true)) {
                        update_post_meta($pID, $var, $posted_value);
                    }
                    // Otherwise, if no value is set, delete the post meta.
                    elseif ($posted_value == "") {
                        delete_post_meta($pID, $var, get_post_meta($pID, $var, true));
                    } // End IF Statement
                } elseif (!isset($_POST[$var]) && $ct_metabox['type'] == 'checkbox') {
                    update_post_meta($pID, $var, 'false');
                } else {
                    delete_post_meta($pID, $var, $current_value); // Deletes check boxes OR no $_POST
                }
            } // End IF Statement	
        }
    }
}

if ( ! function_exists( 'ct_custom_enqueue' ) ) {
/**
 * ct_custom_enqueue()
 * 
 * Enqueue JavaScript files used with the custom fields.
 *
 * @access public
 * @param string $hook
 * @since 2.6.0
 * @return void
 */
function ct_custom_enqueue ( $hook ) {

	wp_register_script( 'woo-custom-fields',plugin_dir_url( __FILE__ ). 'js/woo-custom-fields.js', array( 'jquery', 'jquery-ui-tabs' ) );
		
  	if ( in_array( $hook, array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' ) ) ) {
	
  		wp_enqueue_script( 'woo-custom-fields' );
  	}
} // End ct_custom_enqueue()
}



if ( ! function_exists( 'ct_custom_enqueue_css' ) ) {
/**
 * ct_custom_enqueue_css()
 *
 * Enqueue CSS files used with the custom fields.
 *
 * @access public
 * @author Matty
 * @since 4.8.0
 * @return void
 */
function ct_custom_enqueue_css () {
	global $pagenow;
	wp_register_style( 'woo-custom-fields',plugin_dir_url( __FILE__ )  . 'css/woo-custom-fields.css' );
	if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' ) ) ) {
		wp_enqueue_style( 'woo-custom-fields' );
	}
} // End ct_custom_enqueue_css()
}


?>
