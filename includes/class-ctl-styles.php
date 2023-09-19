<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class CTL_styles
{
   
    /* Constructor */
    public function __construct()
    {
       
    }

    /**
     * Registers our plugin with WordPress.
     */
    public static function register()
    {
        $thisCls = new self();
     // Setup your plugin object here
        //adding required assets
		add_action('wp_enqueue_scripts', array($thisCls, 'ctl_load_scripts_styles'));
           // generate custom styles for timeline 
		add_action('wp_enqueue_scripts',array($thisCls,'ctl_custom_style'));
    }

    // loading js and css according to the layout type in shortcode	 
	public static function ctl_load_assets($layout,$ctl_animation){
		// all common assets	
		wp_enqueue_style('ctl-default-fonts');
        wp_enqueue_style("ctl-gfonts");
			
		wp_enqueue_script('ctl-glightbox-jquery');	
		wp_enqueue_style('ctl-glightbox-jquery');
		wp_enqueue_script('ctl-glightbox');	
		wp_enqueue_style('ctl-styles');
		// vertical layout only assets.
		if($layout!="horizontal"){
			if($ctl_animation !='none'){
				wp_enqueue_script('aos-js');
				wp_enqueue_style('aos-css');
			}			
			wp_enqueue_script('ctl-scripts');
		}
		// compact layout dynamic Js
		if($layout == "compact"){
			wp_enqueue_script('ctl-masonry');
			wp_enqueue_script( 'ctl-compact-js');		
		}else if($layout== "horizontal"){
			// Horizontal Timeline Assets.
			wp_enqueue_style('ctl-slick-css');
			wp_enqueue_script('ctl-slick-js');
			wp_enqueue_script('ctl-horizontal-tm-js');
		}	
		    // include fontawesome 5 version
            wp_enqueue_style( 'fa5', 'https://use.fontawesome.com/releases/v5.15.0/css/all.css', array(), '5.13.0', 'all' );
            wp_enqueue_style( 'fa5-v4-shims', 'https://use.fontawesome.com/releases/v5.15.0/css/v4-shims.css', array(), '5.13.0', 'all' );
	}	 


    /*
	 * Registered All assets 
	*/
    public static function ctl_load_scripts_styles(){
		
		$ctl_options_arr = get_option('cool_timeline_settings');	
        $selected_fonts = array();

        if(isset($ctl_options_arr['post_content_typo']['font-family']))
        {
            $post_content_typo=$ctl_options_arr['post_content_typo'];
           if(isset($post_content_typo['type'])
            && $post_content_typo['type']=='google' )
            {
                $selected_fonts[]=$post_content_typo['font-family'];   
            }
          
        }
      
        if(isset($ctl_options_arr['post_title_typo']['font-family']))
        {
            $post_title_typo=$ctl_options_arr['post_title_typo'];
           if(isset($post_title_typo['type'])
            && $post_title_typo['type']=='google' )
            {
                $selected_fonts[]=$post_title_typo['font-family'];   
            }
        }
        
        if(isset($ctl_options_arr['main_title_typo']['font-family']))
        {
            $main_title_typo=$ctl_options_arr['main_title_typo'];
           if(isset($main_title_typo['type'])
            && $main_title_typo['type']=='google' )
            {
                $selected_fonts[]=$main_title_typo['font-family'];   
            }
        }
		if(isset($ctl_options_arr['ctl_date_typo']['font-family']))
        {
            $ctl_date_typo=$ctl_options_arr['ctl_date_typo'];
           if(isset($ctl_date_typo['type'])
            && $ctl_date_typo['type']=='google' )
            {
                $selected_fonts[]=$ctl_date_typo['font-family'];   
            }
        }
		
	    /*
        * google fonts
        */
        // Remove any duplicates in the list
        $selected_fonts = array_unique($selected_fonts);
        // If it is a Google font, go ahead and call the function to enqueue it
        $gfont_arr = array();

	    if(is_array($selected_fonts)){
	        foreach ($selected_fonts as $font) {
	            if($font && $font != 'inherit'){
	                if ($font == 'Raleway'){
                        $font = 'Raleway:100';
					}
                    $font = str_replace(" ", "+", $font);
                    $gfont_arr[]=$font;
	            }
	        }
	        if(is_array($gfont_arr)){
	            $allfonts=implode("|",$gfont_arr);
	            if($allfonts){
                    wp_register_style("ctl-gfonts", "//fonts.googleapis.com/css?family=$allfonts", false, CTL_V, 'all');
                }
	        }	       
		}

	  	// includes google fonts
        wp_register_style("ctl-default-fonts", "https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800", false, CTL_V, 'all');

		wp_register_style('ctl-styles', CTL_PLUGIN_URL . 'assets/css/ctl_styles.min.css',null, CTL_V,'all' );	

        // register glightbox popup assets
		wp_register_style('ctl-glightbox-jquery', CTL_PLUGIN_URL . 'assets/css/glightbox.min.css', null, CTL_V, 'all');
		wp_register_script('ctl-glightbox-jquery', CTL_PLUGIN_URL . 'assets/js/jquery.glightbox.min.js', array('jquery'), CTL_V, true);  
		wp_register_script('ctl-glightbox', CTL_PLUGIN_URL . 'assets/js/ctl_glightbox.min.js', array('jquery'), CTL_V, true);  
		// includes slick slider for horizontal timeline
		wp_register_script('ctl-slick-js',CTL_PLUGIN_URL . 'assets/js/slick.min.js', array('jquery'), CTL_V, true);
		wp_register_style("ctl-slick-css", CTL_PLUGIN_URL."assets/css/slick.css");
		
		wp_register_script('ctl-scripts', CTL_PLUGIN_URL . 'assets/js/ctl_scripts.min.js', array('jquery'), CTL_V, false);
		wp_register_script('ctl-horizontal-tm-js', CTL_PLUGIN_URL . 'assets/js/ctl_hori_scripts.min.js', array('jquery'), CTL_V, false);
		// load settings for compacy layout
		wp_register_script('ctl-masonry', CTL_PLUGIN_URL . 'assets/js/masonry.pkgd.min.js', array('jquery'), CTL_V, false);
		wp_register_script('ctl-compact-js', CTL_PLUGIN_URL . 'assets/js/ctl_compact_scripts.min.js', array('jquery','ctl-masonry'), CTL_V, false);
	  	// on scroll animations 
		wp_register_style('aos-css',CTL_PLUGIN_URL. 'assets/css/aos.css', null, CTL_V, 'all');
		wp_register_script('aos-js', CTL_PLUGIN_URL . 'assets/js/aos.js', array('jquery'), CTL_V, true);
  
	}
    

    //generate dyamic CSS styles
    public static function ctl_custom_style()
    {                  
        $ctl_options_arr = get_option('cool_timeline_settings');

        /* Style options */         
        $timeline_background = isset($ctl_options_arr['timeline_background'])?$ctl_options_arr['timeline_background']:'0';
        $first_post_color = isset($ctl_options_arr['first_post'])?$ctl_options_arr['first_post']:"#29b246";
        $second_post_color = isset($ctl_options_arr['second_post'])?$ctl_options_arr['second_post']:"#ce792f";
        $timeline_bg_color = isset($ctl_options_arr['timeline_bg_color'])?$ctl_options_arr['timeline_bg_color']:'';
         
        $content_bg_color = isset($ctl_options_arr['content_bg_color'])?$ctl_options_arr['content_bg_color']:'#fff';
        $cont_border_color =isset( $ctl_options_arr['circle_border_color'])? $ctl_options_arr['circle_border_color']:'#38aab7';
        $custom_styles ='';
        $custom_styles = isset( $ctl_options_arr['custom_styles'])?$ctl_options_arr['custom_styles']:"";

        /* Typography options */
        $main_title_typo =  isset($ctl_options_arr['main_title_typo'])?CTL_styles::ctl_get_typeo_output($ctl_options_arr['main_title_typo']):'';
        $story_title_typo= isset($ctl_options_arr['post_title_typo'])?CTL_styles::ctl_get_typeo_output($ctl_options_arr['post_title_typo']):'';		
        $story_content_typo =  isset($ctl_options_arr['post_content_typo'])?CTL_styles::ctl_get_typeo_output($ctl_options_arr['post_content_typo']):'';
      
        $story_title_font_family = isset( $ctl_options_arr['post_title_typo']['font-family'])?$ctl_options_arr['post_title_typo']['font-family']:"";
        $story_title_font_size = isset( $ctl_options_arr['post_title_typo']['font-size'])?$ctl_options_arr['post_title_typo']['font-size']:"";
        $story_title_font_weight = isset( $ctl_options_arr['post_title_typo']['font-weight'])?$ctl_options_arr['post_title_typo']['font-weight']:"";
       
        $ctl_date_typo =''; $story_date_font_family ='';
        if(isset($ctl_options_arr['ctl_date_typo'])){
            $ctl_date_typo =  CTL_styles::ctl_get_typeo_output($ctl_options_arr['ctl_date_typo']);
            $story_date_font_family = isset( $ctl_options_arr['ctl_date_typo']['font-family'])?$ctl_options_arr['ctl_date_typo']['font-family']:"";
      
        }
       

        $line_color =isset($ctl_options_arr['line_color'])?$ctl_options_arr['line_color']:'#025149';
        $styles = '';
      
        if ($timeline_background) {
            $styles.='.cool_timeline.cool-timeline-wrapper {background:'.$timeline_bg_color.'; padding-inline: 15px;}';
        }
          
        $styles.=' 
            .cool_timeline .timeline-main-title,
            .cool_timeline h4.timeline-main-title.center-block { 
             '.$main_title_typo.';
            }'; 
      
        if($line_color){
            $styles.=' .cool-timeline.white-timeline:before, .cool-timeline.white-timeline.one-sided:before {
                background-color:'.$line_color.';
                background-image: -webkit-linear-gradient(top,'.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
                background-image: -moz-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
                background-image: -ms-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
            }
            .cool_timeline_horizontal ul.ctl_road_map_wrp:before	{
                background: '.$line_color.' !important;
            }
            .ctl_road_map_wrp .clt_h_nav_btn i {
                color: '.$line_color.';
            }';
        
            $styles.=' .cool-timeline.white-timeline .timeline-year{
                -webkit-box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
                box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
            }';    
            $styles.=' .cool-timeline .timeline-year::before,
            .cool-timeline.one-sided .timeline-year::before{
             background-color:'.$line_color.';   
            }';        
            $styles.='.cool-timeline.white-timeline .timeline-post .iconbg-turqoise{
                box-shadow: 0 0 0 4px white, inset 0 0 0 2px #fff, 0 0 0 8px '.$line_color.';
            }';      
            $styles.='.cool-timeline.white-timeline.compact .timeline-post .icon-color-white {
                box-shadow: 0px 0px 0 3px '.$line_color.' !Important;
            }
            .cooltimeline_cont .center-line:before,.cooltimeline_cont .center-line:after{
                border-color:'.$line_color.';
            }
            .cooltimeline_cont .center-line{
                background:'.$line_color.';
            }';      
        }     
        
        if($cont_border_color){   
            $styles.=' .cool-timeline.white-timeline .timeline-year{
                background:'.$cont_border_color.';
            }';
        }

        if($content_bg_color){       
            $styles.='
            .cool-timeline .timeline-post .timeline-content {
                background:'.$content_bg_color.';
            }';
        }
        

        if($story_title_font_family!=''){
            $styles.=' .cool-timeline .timeline-year .icon-placeholder span {
                font-family:'.$story_date_font_family.';
            }';
        }
      
        $styles.='
        .cool-timeline .timeline-post .timeline-content h2.content-title,
        .ctl_glightbox_container .ctl_glightbox_title{
            '.$story_title_typo.';
        } 
        .ctl_glightbox_content .ctl_glightbox_date {
            font-family:'.$story_title_font_family.';           
        }';
        $styles.=' .cool-timeline .timeline-post .timeline-content .content-details,
        .cool-timeline .timeline-post .timeline-content .content-details p,
        .ctl_glightbox_container .ginlined-content{
           '.$story_content_typo.';
        }';
       
        if($ctl_date_typo !=''){
            $styles.='.cool-timeline-wrapper .cool-timeline .timeline-post .timeline-meta .meta-details{
                '.$ctl_date_typo.';
            }
            .cool-timeline-wrapper .cool-timeline .timeline-post .timeline-meta .meta-details{
                font-family:'.$story_date_font_family.'!important;
            }';            
        }else{
            $styles.='.cool-timeline .timeline-post .timeline-meta .meta-details{
                font-family:'.$story_title_font_family.';
                font-weight:'.$story_title_font_weight.';
            }';
        }
        
      
        if($first_post_color){
            $styles.='.cool-timeline.white-timeline .timeline-post.even .timeline-content .content-title:before{
                border-right-color:'.$first_post_color.';
            }          
            .cool-timeline.white-timeline.one-sided .timeline-post.even .timeline-content .content-title:before {
                border-right-color:'.$first_post_color.';
            }          
            .ctl_road_map_wrp li.even .ctl-story-year:before,
            .cool_timeline_horizontal .ctl_road_map_wrp li.even span.icon-placeholder {
                border-color:'.$first_post_color.';
                background:'.$first_post_color.';
            }';
          
            $styles.='
            .cool-timeline.white-timeline  .timeline-post.even .icon-dot-full, .cool-timeline.one-sided.white-timeline .timeline-post.even .icon-dot-full{
                background:'.$first_post_color.';
            }';
            $styles.='
            .cool-timeline.white-timeline  .timeline-post.even .icon-color-white, .cool-timeline.one-sided.white-timeline .timeline-post.even .icon-color-white{
                background:'.$first_post_color.';
            } 
            .cool-timeline.white-timeline  .timeline-post.even .timeline-meta .meta-details,
            .ctl_road_map_wrp li.even .ctl-story-year,
            .ctl_road_map_wrp li.even .ctl-story-title,
            .ctl_road_map_wrp li.even .ctl-story-title a{
                color:'.$first_post_color.';
            }
            .cool-timeline.white-timeline  .timeline-post.even .timeline-content .content-title{
                background:'.$first_post_color.';
            }
            .clean-skin-tm .cool-timeline.white-timeline  .timeline-post.even .timeline-content h2.content-title{
                color:'.$first_post_color.';
            }
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-left .timeline-content h2.content-title{
                color:'.$first_post_color.';
            }
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-left .timeline-content {
                border-right:3px solid '.$first_post_color.';
                border-radius:0;
            }
            .ctl_road_map_wrp li.even .ctl-story-year:after {
                background: -moz-linear-gradient(top, '.$first_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
                background: -webkit-linear-gradient(top, '.$first_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
                background: linear-gradient(to bottom, '.$first_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
            }
            .cool-timeline.white-timeline.compact .timeline-post.ctl-left .icon-dot-full{
                background:'.$first_post_color.';
            }';
              
            /*
              compact timeline styles:
            */
            $styles.='.ultimate-style .timeline-post.timeline-mansory.ctl-left .timeline-content .content-title:after {
                border-left-color:'.$first_post_color.';
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-left .timeline-content .content-title,.cool-timeline.white-timeline.compact .timeline-post.ctl-left .icon-color-white{
                background:'.$first_post_color.';
            }';
        }
      
        if($second_post_color){
            $styles.='.cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title:before{
                border-left-color:'.$second_post_color.';
            }
            .cool-timeline.white-timeline.one-sided .timeline-post.odd .timeline-content .content-title:before {
                border-right-color:'.$second_post_color.';
                border-left-color: transparent;
            }
            .ctl_road_map_wrp li.odd .ctl-story-year:before,
            .cool_timeline_horizontal .ctl_road_map_wrp li.odd span.icon-placeholder {
                border-color:'.$second_post_color.';
                background:'.$second_post_color.';
            }';
      
            $styles.='
            .cool-timeline.white-timeline  .timeline-post.odd .icon-dot-full, .cool-timeline.one-sided.white-timeline .timeline-post .icon-dot-full{
                background:'.$second_post_color.';
            }';
            $styles.='
            .cool-timeline.white-timeline  .timeline-post.odd .icon-color-white, .cool-timeline.one-sided.white-timeline .timeline-post .icon-color-white{
                background:'.$second_post_color.';
            }';
           $styles.='      
            .cool-timeline.white-timeline  .timeline-post.odd .timeline-meta .meta-details,
            .ctl_road_map_wrp li.odd .ctl-story-year,
            .ctl_road_map_wrp li.odd .ctl-story-title,
            .ctl_road_map_wrp li.odd .ctl-story-title a{
                color:'.$second_post_color.';
            }';
            $styles.='
            .cool-timeline.white-timeline  .timeline-post.odd .timeline-content .content-title {
                background:'.$second_post_color.';
            }      
            .clean-skin-tm .cool-timeline.white-timeline  .timeline-post.odd .timeline-content h2.content-title {
                color:'.$second_post_color.';
            }      
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-right .timeline-content h2.content-title {
                color:'.$second_post_color.';
            }
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-right .timeline-content {
                border-left:3px solid '.$second_post_color.';
                border-radius:0;
            }
            .ultimate-style .timeline-post.timeline-mansory.ctl-right .timeline-content .content-title:after{
                border-right-color:'.$second_post_color.';
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-right .timeline-content .content-title,.cool-timeline.white-timeline.compact .timeline-post.ctl-right .icon-color-white {
                background:'.$second_post_color.';
            }      
            .ctl_road_map_wrp li.odd .ctl-story-year:after {
                background: -moz-linear-gradient(top, '.$second_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
                background: -webkit-linear-gradient(top, '.$second_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
                background: linear-gradient(to bottom, '.$second_post_color.' 0%, rgba(229, 229, 229, 0) 100%);
            }          
            .cool-timeline.white-timeline.compact .timeline-post.ctl-right .icon-dot-full{
                background:'.$second_post_color.';
            } ';      
        }

        $styles.='@media (max-width: 860px) {
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.even .timeline-content h2.content-title{
                color:'.$first_post_color.';
            } 
            .clean-skin-tm .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.odd .timeline-content h2.content-title {
                color:'.$second_post_color.';
            }
        }';
      
      
        $styles.='
        .cool-timeline.white-timeline.compact  .timeline-post .timeline-content h2.content-title{
            '.$story_title_typo.';
        }
        .ctl_road_map_wrp li .ctl-story-title {
            font-size:calc('.$story_title_font_size.'px - 3px)!important;
            '.$story_title_typo.';
        }
        .ctl-story-year .rm_year {
            font-family:'.$story_title_font_family.';
        }';
      
        $styles.='
        @media (max-width: 860px) {
            .cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title:before {
                border-right-color:'.$second_post_color.';
                border-left-color: transparent;
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.even .timeline-content .content-title,
            .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .icon-color-white,
            .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .icon-dot-full{
                background:'.$first_post_color.';
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.odd .timeline-content .content-title,
            .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .icon-color-white,
            .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .icon-dot-full{
                background:'.$second_post_color.';
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.even .timeline-content .content-title:after {
                border-right-color:'.$first_post_color.';
            }
            .cool-timeline.white-timeline.compact  .timeline-post.ctl-left.odd .timeline-content .content-title:after {
                border-right-color:'.$second_post_color.';
            }
            .ultimate-style .timeline-post.timeline-mansory.ctl-left .timeline-content .content-title:after {
                border-left-color:transparent;
            }
        }';
      
       $custom_css = preg_replace('/\\\\/', '', $custom_styles);
     $final_css = CTL_styles::clt_minify_css($styles);
  
      wp_add_inline_style( 'ctl-styles',$final_css.' '.$custom_css);
      
    }


    // compress CSS
    public static function clt_minify_css($css){
        $buffer = $css;
        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // Remove space after colons
        $buffer = str_replace(': ', ':', $buffer);
        // Remove whitespace
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
        $buffer = preg_replace(" {2,}", ' ',$buffer);
        // Write everything out
        return $buffer;
    }
  
  
    public static function ctl_get_typeo_output($settings) {
        $output    = '';
        $important ='';
        $font_family   = ( ! empty($settings['font-family'] ) ) ? $settings['font-family'] : '';
        $backup_family = ( ! empty( $settings['backup-font-family'] ) ) ? ', '. $settings['backup-font-family'] : '';
        if ( $font_family ) {
          $output .= 'font-family:'. $font_family .''. $backup_family . $important .';';
        }
        // Common font properties
        $properties = array(
          'color',
          'font-weight',
          'font-style',
          'font-variant',
          'text-align',
          'text-transform',
          'text-decoration',
        );
  
        foreach ( $properties as $property ) {
          if ( isset($settings[$property] ) && $settings[$property] !== '' ) {
            $output .= $property .':'.$settings[$property] . $important .';';
          }
        }
  
        $properties = array(
          'font-size',
          'line-height',
          'letter-spacing',
          'word-spacing',
        );
  
        $unit = ( ! empty($settings['unit'] ) ) ? $settings['unit'] : 'px';
      
        $line_height_unit = ( ! empty( $settings['line_height_unit'] ) ) ? $settings['line_height_unit'] : 'px';
     
        foreach ( $properties as $property ) {
          if ( isset($settings[$property] ) && $settings[$property] !== '' ) {
            $unit = ( $property === 'line-height' ) ? $line_height_unit : $unit;
            $output .= $property .':'. $settings[$property] . $unit . $important .';';
          }
        }
        return $output;
    }
}
