<?php
if(!class_exists('CoolTimelinePosttype'))
{
    class CoolTimelinePosttype
    {
        
         /**
    	 * The Constructor
    	 */
    	public function __construct()
    	{
    		// register hooks
            add_action( 'init', array($this,'cooltimeline_custom_post_type' ));
		      	add_filter('manage_edit-cool_timeline_columns',array($this,'add_new_cool_timeline_columns'));
		        add_action( 'manage_cool_timeline_posts_custom_column' , array($this,'ctl_custom_columns'), 10, 2 );
            add_filter('display_post_states', array($this, 'ctl_generted_page_label'));
           // custom message in publish metabox
            add_action('post_submitbox_misc_actions', array($this, 'ctl_submitbox_metabox'));
    
          } // END public function __construct())

          // Register Cool Timeline Post Type
        function cooltimeline_custom_post_type() {

                    $labels = array(
                            'name'                => _x( 'Timeline Stories', 'Post Type General Name', 'cool-timeline' ),
                            'singular_name'       => _x( 'Timeline Stories', 'Post Type Singular Name', 'cool-timeline' ),
                            'menu_name'           => __( 'Timeline Stories', 'cool-timeline' ),
                            'name_admin_bar'      => __( 'Timeline Stories', 'cool-timeline2' ),
                            'parent_item_colon'   => __( 'Parent Item:', 'cool-timeline' ),
                            'all_items'           => __( 'All Stories', 'cool-timeline' ),
                            'add_new_item'        => __( 'Add New Story', 'cool-timeline' ),
                            'add_new'             => __( 'Add New', 'cool-timeline' ),
                            'new_item'            => __( 'New Story', 'cool-timeline' ),
                            'edit_item'           => __( 'Edit Story', 'cool-timeline' ),
                            'update_item'         => __( 'Update Story', 'cool-timeline' ),
                            'view_item'           => __( 'View Story', 'cool-timeline' ),
                            'search_items'        => __( 'Search Story', 'cool-timeline' ),
                            'not_found'           => __( 'Not found', 'cool-timeline' ),
                            'not_found_in_trash'  => __( 'Not found in Trash', 'cool-timeline' ),
                    );
                    $args = array(
                            'label'               => __( 'cool_timeline', 'cool-timeline' ),
                            'description'         => __( 'Timeline Post Type Description', 'cool-timeline' ),
                            'labels'              => $labels,
                            'supports'            => array('title','editor','thumbnail'),
                            'taxonomies'          => array(),
                            'hierarchical'        => false,
                            'public'              => true,
                            'show_ui'             => true,
                            'show_in_menu'        => true,
                            'menu_position'       => 5,
                            'show_in_admin_bar'   => true,
                            'show_in_nav_menus'   => true,
                            'can_export'          => true,
                            'has_archive'         => true,
                            'exclude_from_search' => false,
                         //   'show_in_rest' => true, 
                            'publicly_queryable'  => true,
                            'capability_type'     => 'page',
						       'menu_icon'=>COOL_TIMELINE_PLUGIN_URL.'/images/timeline-icon-small.png',
                    );
                    register_post_type( 'cool_timeline', $args );

            }

            // custom columns for all stories
			function add_new_cool_timeline_columns($gallery_columns) {
			  	$new_columns['cb'] = '<input type="checkbox" />';
          $new_columns['title'] = _x('Story Title', 'column name');
          $new_columns['story_year'] = __('Story Year','cool-timeline');
			  	$new_columns['story_date'] = __('Story Date','cool-timeline');
			    $new_columns['icon'] =__('Story Icon','cool-timeline');
          $new_columns['date'] = _x('Published Date', 'column name');
			
				return $new_columns;
			}	

		// clt column handlers
		function ctl_custom_columns( $column, $post_id ) {
			 switch ( $column ) {
        case "story_year":
        $ctl_story_date = get_post_meta($post_id, 'ctl_story_date', true);
        $story_timestamp=strtotime($ctl_story_date);
        if( $story_timestamp!==false){
        $story_year=date("Y", $story_timestamp);
        echo"<p><strong>" . $story_year . "</strong></p>";
        }
         break;

         case "story_date":
         $ctl_story_date = get_post_meta($post_id, 'ctl_story_date', true);
         echo"<p><strong>" . $ctl_story_date . "</strong></p>";
				  break;
				case "icon":
                $icon = get_post_meta( $post_id, 'fa_field_icon', true );
        		if($icon){
                echo '<i style="font-size:32px;" class="fa '.$icon.'" aria-hidden="true"></i>';
                 }else{
                echo '<i  style="font-size:32px;" class="fa fa-clock-o" aria-hidden="true"></i>';
                  }
              
				break;
			  }
		}

    public function ctl_generted_page_label( $states ){
      if( isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'cool_timeline' ){
          unset($states['scheduled']);
      }
      return $states;
   }
      
   function ctl_submitbox_metabox(){
    if( isset($_REQUEST['post']) && get_post_type( $_REQUEST['post'] ) == 'cool_timeline' ||
    isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'cool_timeline'
){
        $html  = '<div class="misc-pub-section ctl-notice">';
        $html .= '<span style="color:red;font-weight:bold;">*Please select story Date / Year from settings below the story content.';
        $html .= ' <a href="#normal-sortables"><br/>- Timeline Story Settings (Date/Year)</a>';
        $html .= '</span>';
        $html .= '</div>';
      
        echo $html;
    
    }
}
  }
    
}