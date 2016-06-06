<?php

if (!class_exists('CoolTimeline_Template')) {

    class CoolTimeline_Template {

        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
			add_action('init', array(&$this, 'cooltimeline_register_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'ctl_load_scripts_styles'));
			add_action('wp_enqueue_scripts', array(&$this, 'ctl_custom_style'));
			// Call actions and filters in after_setup_theme hook
			add_action( 'after_setup_theme',array(&$this, 'ctl_custom_read_more') );
			add_filter( 'excerpt_length',array(&$this,'ctl_custom_excerpt_length' ));
        }
		
		function ctl_custom_read_more() {
		   // add more link to excerpt
		   function ctl_custom_excerpt_more($more) {
			  global $post;
			   $ctl_options_arr = get_option('cool_timeline_options');
				if ($post->post_type == 'cool_timeline' && !is_single() ){
					 if( $ctl_options_arr['display_readmore']=='yes'){
							 return '..<a class="read_more ctl_read_more" href="'. get_permalink($post->ID) . '">'. __('Read More', 'cool_timeline') .'</a>';
					   }  
				 }else{
				  	return $more;
				 }
			}
		   add_filter('excerpt_more', 'ctl_custom_excerpt_more');
		}
		function ctl_custom_excerpt_length( $length ) {
			global $post;
			$ctl_options_arr = get_option('cool_timeline_options');
			$ctl_content_length = $ctl_options_arr['content_length']?$ctl_options_arr['content_length']:100;
			if ($post->post_type == 'cool_timeline' && !is_single() ){
				return $ctl_content_length;
				}
			return $length;
		}
		
        function cooltimeline_register_shortcode() {
            add_shortcode('cool-timeline', array(&$this, 'cooltimeline_view'));
		 }
	    function cooltimeline_view($atts, $content = null) {
			
			wp_enqueue_style('ctl_styles');
			
            $attribute = shortcode_atts(array(
                'class' => 'caption',
                'post_per_page' => 10,
                    ), $atts);
			$ctl_options_arr = get_option('cool_timeline_options');
			
		
			$output='';
			$ctl_html='';
			 /*
             * Gerneral options
             */

          //  $ctl_timeline_type = $ctl_options_arr['timeline_type'];
            $ctl_title_text = $ctl_options_arr['title_text'];
            $ctl_title_tag = $ctl_options_arr['title_tag'];
          //  $ctl_title_pos = $ctl_options_arr['title_pos'];
        	
				if(isset($ctl_options_arr['user_avatar']['id'])){
					$user_avatar =wp_get_attachment_image_src($ctl_options_arr['user_avatar']['id'],'ctl_avatar');
					}
			
			/*
			* content settings
			*/
	
            $ctl_post_per_page = $ctl_options_arr['post_per_page'];
			$story_desc_type = $ctl_options_arr['desc_type'];
			$ctl_no_posts= isset($ctl_options_arr['no_posts'])?$ctl_options_arr['no_posts']:"No timeline post found";
			$ctl_content_length = $ctl_options_arr['content_length'];
			$ctl_posts_orders = $ctl_options_arr['posts_orders']?$ctl_options_arr['posts_orders']:"DESC";
			$disable_months = $ctl_options_arr['disable_months']?$ctl_options_arr['disable_months']:"no";
			$title_alignment = $ctl_options_arr['title_alignment']?$ctl_options_arr['title_alignment']:"center";
		
			//$ctl_posts_order='date';
         
            /*
             * images sizes
             */
        
			
            $ctl_post_per_page=$ctl_post_per_page ? $ctl_post_per_page : 10;
            $ctl_title_text = $ctl_title_text ? $ctl_title_text : 'Timeline';
            $ctl_title_tag = $ctl_title_tag ? $ctl_title_tag : 'H2';
            //$ctl_title_pos = $ctl_title_pos ? $ctl_title_pos : 'left';
            $ctl_content_length ? $ctl_content_length : 100;
			
			
			$display_year = '';
            $format =__('d/M/Y','cool_timeline');
			$output='';
            $year_position = 2;
           $args = array(
		   'post_type' => 'cool_timeline', 
		   'posts_per_page' => $ctl_post_per_page,
		  'post_status' => array( 'publish', 'future' ),
			'orderby' => 'date',
			'order' =>$ctl_posts_orders
		   );
			$i = 0;
			$row = 1;
			
			  $ctl_loop = new WP_Query($args);

            if ($ctl_loop->have_posts()){
				
                while ($ctl_loop->have_posts()) : $ctl_loop->the_post();
                    $img_cont_size = get_post_meta(get_the_ID(), 'image_container_type', true);

						switch ($img_cont_size) {
                        case'Full':
                            $cont_size_cls = 'full';
							break;
				
                        case'small':
                            $cont_size_cls = 'small';
							break;
                        default;
                            $cont_size_cls = 'full';
							break;
                    }	
					
						 if (isset($cont_size_cls) && !empty($cont_size_cls)) {
                        $container_cls = $cont_size_cls;
                    } else {
							 $container_cls ='full';
                    }

					   /*
                         * Display By date
                         */
                        $post_date = explode('/', get_the_date($format));

                        $post_year = $post_date[$year_position];
                        if ($post_year != $display_year) {
                        
						 /* $post_year
						   $post_date[1] */
                            $display_year = $post_year;
							$ctl_html.=' <dt>'. $post_year.'</dt>';
                        }

					if($story_desc_type=='full'){
						$story_cont = get_the_content();
					}else{
						$story_cont =get_the_excerpt();
					}

					if ( '' != $story_cont ) {
						 $post_content= $story_cont;
					}else{
					 $post_content="";
					}
				
					$posted_date=get_the_date(__('M d','cool_timeline'));
					
					   if($cont_size_cls=="full"){
					$ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'large');
					}else{
					$ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
					}
			
                    $ctl_thumb_url = $ctl_thumb['0'];
                    $ctl_thumb_width = $ctl_thumb['1'];
                    $ctl_thumb_height = $ctl_thumb['2'];
				
                    if ($i % 2 == 0) {
                        $even_odd = "even";
                        $ctl_html .='<dd id="' . $row . '" class="pos-right clearfix ' . $even_odd . ' ' . $container_cls . '">';
						$ctl_html .='<div class="circ"></div>';
						
						if($disable_months=="no"){
							$ctl_html .='<div class="time">'.$posted_date.'</div>';
							} 
							
						$ctl_html .='<div class="events"><h4 class="events-heading">' . get_the_title() . '</h4>';
                   
						 $ctl_html   .= '<div class="ctl_info event-description '.$cont_size_cls.'">';
						if(isset($ctl_thumb_url)&& !empty($ctl_thumb_url)){
					   if($cont_size_cls=="full"){
								$ctl_html.='<div class="full-width"><img  width="100%" class="events-object" src="'.$ctl_thumb_url.'"></div>';
							}else{
							$ctl_html.='<div class="pull-left"><img  class="events-object left_small" src="'.$ctl_thumb_url.'"></div>';
							}
						}
					
						if(!empty($post_content)){
						$ctl_html.='<div class="events-body"><p>' . $post_content. '</p></div>';
						 }
						 
					    $ctl_html .='</div>';
					
                    } else {
                        $even_odd = "odd";
						$ctl_html .='<dd id="' . $row . '" class="pos-left clearfix ' . $even_odd . ' ' . $container_cls . '">';
						$ctl_html .='<div class="circ"></div>';
							if($disable_months=="no"){
							$ctl_html .='<div class="time">'.$posted_date.'</div>';
							}
							
						$ctl_html .='<div class="events"><h4  class="events-heading">' . get_the_title() . '</h4>';
						
						 $ctl_html   .= '<div class="ctl_info event-description '.$cont_size_cls.'">';
						if(isset($ctl_thumb_url)&& !empty($ctl_thumb_url)){
					   if($cont_size_cls=="full"){
								$ctl_html.='<div class="full-width"><img  width="100%" class="events-object" src="'.$ctl_thumb_url.'"></div>';
							}else{
							$ctl_html.='<div class="pull-left"><img  class="events-object left_small" src="'.$ctl_thumb_url.'"></div>';
							}
						}
						
							if(!empty($post_content)){
						$ctl_html.='<div class="events-body"><p>'.$post_content.'</p></div>';
						 }
					    $ctl_html .='</div>';
						
                        $ctl_html .='</dd>';
                    }
                    if ($row >= 3) {
                        $row = 0;
                    }
                    $row++;
                    $i++;
                endwhile;
                wp_reset_query();
		
			
			}else{
				$ctl_html.='<div class="no-content">';
				$ctl_html.=$ctl_no_posts;
				$ctl_html.='</div>';
				}
	 
		
	$output .='<div class="clearfix"></div>
	  <!-- Cool timeline
      ================================================== -->
    <div class="cool_timeline">';
		if(isset($user_avatar[0])&& !empty($user_avatar[0])){
			$output .='<div class="avatar_container row"><span title="'.$ctl_title_text.'"><img  class=" center-block img-responsive img-circle" alt="'.$ctl_title_text.'" src="'.$user_avatar[0].'"></span></div> ';
		  }
		  $output .='<' . $ctl_title_tag . '  class="timeline-main-title center-block">' . $ctl_title_text . '</' . $ctl_title_tag . '>';
         $output .='<div class="row">
		  <div class="col-md-12">
			  <div class="timeline cooltimeline_cont"><dl>';
		$output.=$ctl_html;
		$output.='</dl></div>
			</div>
		</div>
    </div>  <!-- end
      ================================================== -->';
		
			
            return $output ;
		}

		
		function ctl_custom_style(){
			
			$ctl_options_arr = get_option('cool_timeline_options');
			$disable_months = $ctl_options_arr['disable_months']?$ctl_options_arr['disable_months']:"no";
			$title_alignment = $ctl_options_arr['title_alignment']?$ctl_options_arr['title_alignment']:"center";
		
			/*
             * Style options
             */
           
            $background_type =isset($ctl_options_arr['background']['enabled'])?$ctl_options_arr['background']['enabled']:'';
			$first_post_color=isset($ctl_options_arr['first_post'])?$ctl_options_arr['first_post']:"#1b949e";
           	$second_post_color=isset($ctl_options_arr['second_post'])?$ctl_options_arr['second_post']:"#f58636";

			$third_post_color=isset($ctl_options_arr['third_post'])?$ctl_options_arr['third_post']:"#a086d3";
			$bg_color = $ctl_options_arr['background']['bg_color']?$ctl_options_arr['background']['bg_color']:'none';
            $bg_img = $ctl_options_arr['background']['bg_img'];
            $bg_img = $bg_img['src'] ? $bg_img['src'] : '';
            $content_bg_color = isset($ctl_options_arr['content_bg_color'])?$ctl_options_arr['content_bg_color']:'#fff';
            $cont_border_color =isset( $ctl_options_arr['circle_border_color'])? $ctl_options_arr['circle_border_color']:'#414a54';
			
			
			 /*
             * Typography options
             */
			$ctl_main_title_typo = $ctl_options_arr['main_title_typo'];
            $ctl_post_title_typo = $ctl_options_arr['post_title_typo'];
            $ctl_post_content_typo = $ctl_options_arr['post_content_typo'];

			$events_body_f=$ctl_post_content_typo['face']?$ctl_post_content_typo['face']:'inherit';
			$events_body_w=$ctl_post_content_typo['weight']?$ctl_post_content_typo['weight']:'inherit';
			$events_body_s=$ctl_post_content_typo['size']?$ctl_post_content_typo['size']:'inherit';

			$post_title_f=$ctl_post_title_typo['face']?$ctl_post_title_typo['face']:'inherit';
			$post_title_w=$ctl_post_title_typo['weight']?$ctl_post_title_typo['weight']:'inherit';
			$post_title_s=$ctl_post_title_typo['size']?$ctl_post_title_typo['size']:'inherit';

			$post_content_f=$ctl_post_content_typo['face']?$ctl_post_content_typo['face']:'inherit';
			$post_content_w=$ctl_post_content_typo['weight']?$ctl_post_content_typo['weight']:'inherit';
			$post_content_s=$ctl_post_content_typo['size']?$ctl_post_content_typo['size']:'inherit';

			//line_type = $ctl_options_arr['line_type'];
            $line_color =isset($ctl_options_arr['line_color'])?$ctl_options_arr['line_color']:'#414a54';
			  $styles = '';

            if ($background_type == 'on') {
                if ($bg_img) {
                    $styles.='.cool_timeline{background-image:url("' . $bg_img . '")};';
                }
                $styles.='.cool_timeline{background-color:' . $bg_color . ';}';
            }
				$styles .=' 
				.timeline dl dd.even .circ, .timeline dl dd.even .events h4 
				{ background-color:'.$first_post_color.'; }
				.timeline dl dd.odd .circ, .timeline dl dd.odd .events h4 
					{ background-color:'.$second_post_color.'; }';
				$styles .='
				.timeline dl dd.even .events:before {border-right-color:'.$first_post_color.';}
				.timeline dl dd.odd .events:before {border-left-color:'.$second_post_color.'; }
				.timeline dl dd.even .time { color:'.$first_post_color.'; }
				.timeline dl dd.odd .time { color:'.$second_post_color.'; }';
					$styles .='@media screen and (max-width:760px) {
				.timeline dl dd.odd .events::before{border-right-color:'.$second_post_color.';border-left-color:transparent; }
			}';
			
		
			$styles .='.ctl_info.event-description{background-color:'.$content_bg_color.';}';
            $styles .='.timeline dl dt{ background-color:'.$cont_border_color.';}';
            $styles .='.timeline dl::before{background-color:' .$line_color. ';}';
			 $styles .='.avatar_container img.center-block.img-responsive.img-circle{border:2px solid ' .$line_color. ';}';
			 
			
			/*
             * Typo styles
             */

			$styles .='.timeline dl dd .events .events-body{font-weight:' . $post_content_w.';'
				. 'font-family:' . $post_content_f . ';font-size:' .$post_content_s. ';}';

			$styles .='.timeline dl dd.even .events h4,.timeline dl dd.odd .events h4 {font-weight:' .$post_title_w. ';'
				. 'font-family:' . $post_title_f. ';font-size:' .$post_title_s. ';}';

			$styles .='.timeline dl dt , .timeline dl dd.pos-right .time ,{font-weight:' .$events_body_w. ';'
				. 'font-family:'.$events_body_f.';font-size:' .$events_body_s. ';}';

			$styles .='.cool_timeline h1.timeline-main-title{font-family:' .$events_body_f . ';}';
			$styles .='.cool_timeline .timeline-main-title{text-align:' .$title_alignment . ';}';
			
			//$styles .='a.read_more.ctl_read_more{display:'..';}';
			
			wp_add_inline_style( 'ctl_styles',$styles );
		}
		
		/*
		* Include this plugin's public JS & CSS files on posts.
		*/
		
     
        function ctl_load_scripts_styles() {
			/*
			 * google fonts
			 */
			
			$ctl_options_arr = get_option('cool_timeline_options');
		
			$post_content_face=$ctl_options_arr['post_content_typo']['face'];
			$post_title=$ctl_options_arr['post_title_typo']['face'];
			$main_title=$ctl_options_arr['main_title_typo']['face'];
			$selected_fonts = array($post_content_face,$post_title,$main_title);
			 // Remove any duplicates in the list
			$selected_fonts = array_unique($selected_fonts);
			// If it is a Google font, go ahead and call the function to enqueue it
			foreach ( $selected_fonts as $font ) {
				if ($font != 'inherit') {
			
			// Certain Google fonts need slight tweaks in order to load properly
			// Like our friend "Raleway"
			if ( $font == 'Raleway' )
				$font = 'Raleway:100';
			$font = str_replace(" ", "+", $font);
			wp_enqueue_style( "ctl_gfonts$font", "http://fonts.googleapis.com/css?family=$font", false, null, 'all' );
			wp_enqueue_style( "ctl_default_fonts","https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800",false,null,'all');
		
			/*
			 * End
			 * 
			 */
				}
			}
			wp_register_style('ctl_styles', COOL_TIMELINE_PLUGIN_URL . 'css/ctl_styles.css',null, null,'all' );
		
		
	}

	
    }

} // end class


