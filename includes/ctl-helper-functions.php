<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CTL_functions
{
  
    /* Constructor */
    public function __construct()
    {
        // Setup your plugin object here
    }

  
    public static function get_fa( $format = false, $post_id = null ) {
            if ( ! $post_id ) {
                global $post;
                if ( ! is_object( $post ) ) {
                    return;
                }
                $post_id = $post->ID;
            }
            $icon='';
            $ctl_story_icon = get_post_meta($post_id, 'story_icon', true);
            $icon = isset($ctl_story_icon['fa_field_icon'])?$ctl_story_icon['fa_field_icon']:'';

          
            if ( ! $icon ) {
                return;
            }
            if ( $format ) {
                if(strpos($icon, '-o') !==false){
                    $icon="fa ".$icon;
                 }else if(strpos($icon, 'fas')!==false || strpos($icon, 'fab') !==false) {
                      $icon=$icon;
                 }else{
                    $icon="fa ".esc_attr($icon);
                 }
                $output = '<i class="' . esc_attr($icon) . '"></i>';
            } else {
                $output = $icon;
            }
            return $output;
        }
    
   
    // getting story date 
    public static function ctlfree_get_story_date($post_id,$date_formats){        
        $ctl_story_type = get_post_meta($post_id, 'story_type', true);		
		$ctl_story_date = $ctl_story_type['ctl_story_date'];
        if ($ctl_story_date) {
            if (strtotime($ctl_story_date)!==false) {
                $posted_date = date_i18n(__("$date_formats", 'cool-timeline'), strtotime("$ctl_story_date"));
            }else {
                $ctl_story_date = trim( str_ireplace(array('am','pm'),'',$ctl_story_date) );
                $dateobj = DateTime::createFromFormat('m/d/Y H:i',$ctl_story_date ,wp_timezone());
                if($dateobj){
                    $posted_date = $dateobj->format(__("$date_formats", 'cool-timeline'));
                }
            }
            return  $posted_date;
        }
    }

    /*
        Create own custom timestamp for stories
    */
    public static function ctlfree_generate_custom_timestamp($story_date){
       
        if(!empty($story_date)){            
            $ctl_story_date=strtotime($story_date);      
            if( $ctl_story_date!==false){
                $story_timestamp =gmdate('YmdHi',$ctl_story_date);
            } 
            return $story_timestamp;  
        }
    }  
            
    //get post type from url
    public static function ctlfree_get_ctp(){
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

    // Timeline stories pagination handler
    public static function ctl_pagination($numpages = '', $pagerange = '', $paged='') {
        if (empty($pagerange)) {
            $pagerange = 2;
        }
      
        if ( get_query_var('paged') ) { 
            $paged = get_query_var('paged'); 
        } elseif ( get_query_var('page') ) { 
            $paged = get_query_var('page'); 
        } else { 
            $paged = 1; 
        }
       
        if ($numpages == '') {
            global $wp_query;
            $numpages = $wp_query->max_num_pages;  
            if(!$numpages) {
               $numpages = 1;
            }
        }
      
        $big = 999999999; 
        $of_lbl = __( ' of ', 'cool-timeline' ); 
        $page_lbl = __( ' Page ', 'cool-timeline' ); 
        $pagination_args = array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => False,
            'end_size'        => 1,
            'mid_size'        => $pagerange,
            'prev_next'       => True,
            'prev_text'       => __('&laquo;'),
            'next_text'       => __('&raquo;'),
            'type'            => 'plain',
            'add_args'        => false,
            'add_fragment'    => '' 
        );

        $paginate_links = paginate_links($pagination_args);
        $ctl_pagi='';
        if ($paginate_links) {
            $ctl_pagi .= "<nav class='custom-pagination'>";
            $ctl_pagi .= "<span class='page-numbers page-num'> ".$page_lbl . $paged . $of_lbl . $numpages . "</span> ";
            $ctl_pagi .= $paginate_links;
            $ctl_pagi .= "</nav>";
            return $ctl_pagi;
        }
      
    }
  
}
