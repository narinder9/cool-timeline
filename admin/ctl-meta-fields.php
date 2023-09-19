<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
Creating Meta boxes for timeline stories section
*/
// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {
        
    // Set a unique slug-like ID
    $prefix = 'ctl_post_meta';
  
    //
    // Create a metabox
    CSF::createMetabox( $prefix, array(
      'title'     => 'Timeline Story Settings',
      'post_type' => 'cool_timeline',
      'data_type' => 'unserialize',
      'context'   => 'normal', // The context within the screen where the boxes should display. `normal`, `side`, `advanced`
    ) );


        // Create a section
        CSF::createSection( $prefix, array(
            //'title'  => 'Add Icon',
            'data_type' => 'unserialize',
            'fields' => array( 
                //Story Date
                array(
                    'id'     => 'story_type',
                    'type'   => 'fieldset',
                    'title'  => 'Story Type',
                    'fields' => array(
                        array(
                            'id'    => 'ctl_story_date',
                            'type'  => 'datetime',
                            'title' => __('Story Date / Year <span class="ctl_required">*</span>','cool-timeline'),
                            'desc' =>'<p class="ctl_required">Please select story Story Date / Year / Time using datepicker only. <strong>Date Format( mm/dd/yy hh:mm )</strong></p>',
                            'default' => gmdate('m/d/Y h:i a'),  
                            'settings' => array(
                                'enableTime' =>true,
                                'dateFormat'=>'m/d/Y h:i K',
                                'minuteIncrement'=>'1'
                              ),
                           ),  
                        ),
                ),
               
                //ICON
                array(
                    'id'     => 'story_icon',
                    'type'   => 'fieldset',
                    'title'  => 'Story Icon',
                    'fields' => array(                    
                        array(
                            'id'    => 'fa_field_icon',
                            'type'  => 'icon',
                            'title' => 'Font Awesome Icon',
                        ),
                    ),
                ),
    
                array(
                    'id'     => 'story_media',
                    'type'   => 'fieldset',
                    'title'  => 'Story Media',
                    'fields' => array(                       
                        array(
                            'id'         => 'img_cont_size',
                            'type'       => 'button_set',
                            'title'      => 'Story image size',
                            'options'    => array(
                              'full'  => 'Full',
                              'small' => 'Small',
                            ),
                            'default'    => 'full',
                        ),
                                          
                        
                    ),
                ),
    
                array(
                    'id'=>'ctl_pro_screenshot',
                    'type'=>'content',
                    'content'=> '<div class="desc-field"><h4 >Premium Settings | <a target="_blank" href="'.CTL_BUY_PRO.'">Buy Pro</a></h4><img src="'.CTL_PLUGIN_URL.'/assets/images/pro-story-settings.png" style="max-width: 100%;border: 2px solid #ef2e2e;"></div>',
                    'class'=>'story_format_image',
                ),
    
            
            )
    
        )); 
     

    $pro_list = 'feautre_list';
    // Create a metabox
    CSF::createMetabox( $pro_list, array(
       'title'     => 'Cool Timeline Pro Features',
       'post_type' => 'cool_timeline',
       'priority'           => 'low',
       'data_type' => 'unserialize',
       'context'   => 'side', // The context within the screen where the boxes should display. `normal`, `side`, `advanced`
     ) );

       // Create a section
   CSF::createSection( $pro_list, array(
       //'title'  => 'Add Icon',
       'fields' => array(  
               array(
                   'id'=>'pro_feature_list_section',
                   'type'=>'content',
                   'content'=>' <ul style="list-style:disc;margin: 2px 16px;">
                       <li>40+ Timeline Designs</li>
                       <li>Multiple Timelines</li>
                       <li>Colors & Typography</li>
                       <li>Custom Label / Text</li>
                       <li>Video, Images & Slider</li>
                       <li>Post Timeline</li>
                       <li>Custom Story Color</li>
                       <li>Shortcode Generator</li>
                       <li>Gutenberg / Elementor / WPBakery</li>
                       <li>ASC / DESC Order</li>
                       <li>Category Filters</li>
                       <li>Ajax Load More / Pagination</li>
                       <li>Scrolling Navigation</li>
                       <li>Icons In Timeline</li>
                       <li>HTML / Links / Read More</li>
                       <li>Date Format</li>
                       <li>Animations</li>
                       <li>Premium Support</li>
                   </ul>', 
                   'class'=>'pro_features',
               ),
               array(
                   'id'=>'pro_buy_links',
                   'type'=>'content',
                   'content'=>'<div>
                   <strong class="ctl_add_head">'.__('Upgrade to Pro version','cool-timeline2').'</strong>
                  </br>
                  </br>
                   <a target="_blank" class="button button-primary" href="'.CTL_DEMO_URL.'&utm_content=add_stories">
                  View Demos
                   </a> 
                   <a style="background:#CD143B;font-weight:bold" target="_blank" class="button button-primary" href="'.CTL_BUY_PRO.'">
                   Buy Now 
                   </a>
               </div>', 
               'class'=>'pro_features',
               ),
              
         )
       )); 
       $review_us = 'review_us';
       // Create a metabox
       CSF::createMetabox( $review_us, array(
          'title'     => 'Please Share Your Feedback',
          'post_type' => 'cool_timeline',
          'data_type' => 'unserialize',
          'priority'           => 'low',
          'context'   => 'side', // The context within the screen where the boxes should display. `normal`, `side`, `advanced`
        ) );
  
          // Create a section
      CSF::createSection( $review_us, array(
       'fields' => array(  
           array(
               'id'=>'review_us_section',
               'type'=>'content',
               'content'=>'<div>'.
               __('If you find our plugin and support helpful.<br>Please rate and review us,It helps us grow <br>and improve our services','cool-timeline').'.<br>
               <a target="_blank" href="https://wordpress.org/support/plugin/cool-timeline/reviews/#new-post"><img src="'.CTL_PLUGIN_URL.'assets/images/stars5.png"></a><br>
               <a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/cool-timeline/reviews/#new-post">'.__('Submit Review ★★★★★','cool-timeline2').'</a>
           </div>', 
           'class'=>'pro_features',
           )
       )       
   )); 
   
}
  