<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class CoolTimelinePosttypeFree
{
    /**
     * Registers our plugin with WordPress.
     */
    public static function register()
    {
        $postTypeCls = new self();
        // register hooks
        add_action( 'init', array($postTypeCls,'cooltimeline_custom_post_type' ));
        add_filter('manage_edit-cool_timeline_columns',array($postTypeCls,'add_new_cool_timeline_columns'));
        add_action( 'manage_cool_timeline_posts_custom_column' , array($postTypeCls,'ctl_custom_columns'), 10, 2 );
        add_filter('display_post_states', array($postTypeCls, 'ctl_generted_page_label'));
        // custom message in publish metabox
        add_action('post_submitbox_misc_actions', array($postTypeCls, 'ctl_submitbox_metabox'));
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Setup your plugin object here
    }

	// Register Cool Timeline Post Type
	public function cooltimeline_custom_post_type(){
		$labels = array(
			'name'                => _x( 'Timeline Stories', 'Post Type General Name', 'cool-timeline2' ),
			'singular_name'       => _x( 'Timeline Stories', 'Post Type Singular Name', 'cool-timeline2' ),
			'menu_name'           => __( 'Timeline Stories', 'cool-timeline2' ),
			'name_admin_bar'      => __( 'Timeline Stories', 'cool-timeline2' ),
			'parent_item_colon'   => __( 'Parent Item:', 'cool-timeline2' ),
			'all_items'           => __( 'Cool Timeline Stories', 'cool-timeline2' ),
			'add_new_item'        => __( 'Add New Story', 'cool-timeline2' ),
			'add_new'             => __( 'Add New', 'cool-timeline2' ),
			'new_item'            => __( 'New Story', 'cool-timeline2' ),
			'edit_item'           => __( 'Edit Story', 'cool-timeline2' ),
			'update_item'         => __( 'Update Story', 'cool-timeline2' ),
			'view_item'           => __( 'View Story', 'cool-timeline2' ),
			'search_items'        => __( 'Search Story', 'cool-timeline2' ),
			'not_found'           => __( 'Not found', 'cool-timeline2' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'cool-timeline2' ),
		);
		$args = array(
			'label'               => __( 'cool_timeline', 'cool-timeline2' ),
			'description'         => __( 'Timeline Post Type Description', 'cool-timeline2' ),
			'labels'              => $labels,
			'supports'            => array('title','editor','thumbnail'),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'cool-plugins-timeline-addon',
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
		    //'show_in_rest' => true, 
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'=>CTL_PLUGIN_URL.'assets/images/timeline-icon-small.png',
		);
		register_post_type( 'cool_timeline', $args );
	}

	// custom columns for all stories
	public function add_new_cool_timeline_columns($gallery_columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Story Title', 'column name');
		$new_columns['story_year'] = __('Story Year','cool-timeline');
		$new_columns['story_date'] = __('Story Date','cool-timeline');
	    $new_columns['icon'] =__('Story Icon','cool-timeline');
		$new_columns['date'] = _x('Published Date', 'column name');			
	    return $new_columns;
    }

	// clt column handlers
	public function ctl_custom_columns( $column, $post_id ){
		$ctl_story_type = get_post_meta($post_id, 'story_type', true);		
		$ctl_story_date = isset($ctl_story_type['ctl_story_date'])?$ctl_story_type['ctl_story_date']:'';
		switch ( $column ) {
		    case "story_year":			    
			    $story_timestamp=strtotime($ctl_story_date);
			    if( $story_timestamp!==false){
				   $story_year=gmdate("Y", $story_timestamp);
				   echo"<p><strong>" . esc_html($story_year) . "</strong></p>";
			    }else{
				   $ctl_story_date = trim( str_ireplace(array('am','pm'),'',$ctl_story_date) );
				   $dateobj = DateTime::createFromFormat('m/d/Y H:i',$ctl_story_date ,wp_timezone());
				    if($dateobj){
					    echo"<p><strong>" . wp_kses_post($dateobj->format(__("Y", 'cool-timeline1'))). "</strong></p>";
				    }
			    }
			break;
		    case "story_date":			    
			    echo"<p><strong>" . esc_html($ctl_story_date) . "</strong></p>";
			break;
		    case "icon":
				$icon = get_post_meta( $post_id, 'story_icon', true );
				$icon = isset($icon['fa_field_icon'])?$icon['fa_field_icon']:'';			    
			    if($icon){
			    echo '<i style="font-size:32px;" class="'.esc_attr($icon).'" aria-hidden="true"></i>';
				}else{
			    echo '<i  style="font-size:32px;" class="fa fa-clock-o" aria-hidden="true"></i>';
				}
            break;
	        default:
		    	echo "<p>".esc_html_e( 'Not Matched', 'cool-timeline' )."</p>";
		}
    }

	public function ctl_generted_page_label( $states ){
		if( isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'cool_timeline' ){
			unset($states['scheduled']);
		}
		return $states;
	}

	public function ctl_submitbox_metabox(){
		if( isset($_REQUEST['post']) && get_post_type( $_REQUEST['post'] ) == 'cool_timeline' ||
		isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'cool_timeline'){
			$html  = '<div class="misc-pub-section ctl-notice">';
			$html .= '<span style="color:red;font-weight:bold;">*Please select story Date / Year from settings below the story content.';
			$html .= ' <a href="#ctl_post_meta"><br/>- Timeline Story Settings (Date/Year)</a>';
			$html .= '</span>';
			$html .= '</div>';
			echo wp_kses_post($html);		
		}
	}

}
CoolTimelinePosttypeFree::register();