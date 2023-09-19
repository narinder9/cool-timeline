<?php
/**
 *
 * This file is responsible for creating all admin settings in Timeline Builder (post)
 */
if (!defined("ABSPATH")) {
    exit;
}

if (!class_exists('CSF_free_shortcode_generator')) {
    class CSF_free_shortcode_generator
    {

/**
 * The unique instance of the plugin.
 *
 */
        private static $instance;

        /**
         * Gets an instance of our plugin.
         *
         */
        public static function get_instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions

          
           
            $this->CSF_free_shortcode_generator();
            add_action('admin_print_styles', array($this, 'ctl_custom_shortcode_style'));

        }


        public function ctl_custom_shortcode_style()
        {
            echo '<style>
        span.dashicon.dashicons.dashicons-ctl-custom-icon:before {
        content:"";
        background: url(' . CTL_PLUGIN_URL . 'assets/images/timeline-icon2-32x32.png);
        background-size: contain;
        background-repeat: no-repeat;
        height: 20px;
        display: block;

        }
       #wp-content-wrap a[data-modal-id="ctl_timeline_shortcode"]:before {
        content: "";
        background: url(' . CTL_PLUGIN_URL . 'assets/images/cool-timeline-icon.svg);
        background-size: contain;
        background-repeat: no-repeat;
        height: 17px;
        display: inline-block;
        margin: 0px 1px -3px 0;
        width: 20px;
        }
        #wp-content-wrap a[data-modal-id="ctl_timeline_shortcode"] {
       // background: #000;
       // border-color: #000;
        }
        .csf-shortcode-single .csf-modal-content {
            height: 655px !important;

        }
        
        #csf-modal-ctl_timeline_shortcode .csf-modal-inner {
            height: 500px !important;
            // overflow: auto;          
        }
        #csf-modal-ctl_timeline_shortcode .csf-modal-content {            
            // overflow: hidden !important; 
            height:400px !important;        
            // min-height: -webkit-fill-available;
        }  
       
        </style>';
        }

        public function CSF_free_shortcode_generator()
        {
            $id = isset($GLOBALS['_GET']['post'])?$GLOBALS['_GET']['post']:'';
            $post_type = isset($GLOBALS['_GET']['post_type'])?$GLOBALS['_GET']['post_type']:get_post_type($id);


            // change block name if older block exists in current page condition start
            $block_name='ctl-gutenberg-block';
            if( $id != '' ){
                $ctl_post_id=(int)$id;
                $all_blocks=[];
                $post_content = get_post( $ctl_post_id );
                if( $post_content != null ){
                    $parse_data=parse_blocks( $post_content->post_content );
                    foreach( $parse_data as $parse ){
                        if( $parse['blockName'] != null ){
                            array_push( $all_blocks,$parse['blockName'] );
                        };
                    };
                };
    
                if( !in_array( "csf/ctl-timeline-shortcode",$all_blocks ) ){
                    $block_name='ctl-gutenberg-block';
                }else{
                    $block_name='ctl_timeline_shortcode';
                };
            }
            // change block name if older block exists in current page condition end

            if($post_type!=='page' && $post_type!=='post' && $post_type!='') { 
                return;
            }
            if (class_exists('CSF')) {

                //
                // Set a unique slug-like ID
                $prefix = 'ctl_timeline_shortcode';              

                //
                // Create a shortcoder
                CSF::createShortcoder($prefix, array(
                    'button_title' => 'Add Shortcode',
                    'insert_title' => 'Insert shortcode',
                    'gutenberg' => array(
                        'block_name'=>$block_name,
                        'title' => 'Cool Timeline Shortcode Generator',
                        'icon' => 'ctl-custom-icon',
                        'description' => 'Cool Timeline Shortcode Generator Block.',
                        'category' => 'widgets',
                        'keywords' => array('shortcode', 'ctl','cooltimeline','timeline'),
                        'previewImage' => CTL_PLUGIN_URL.'includes/cool-timeline-block/images/timeline.27d3f3c7.png',
                    ),
                ));

                //
                // A basic shortcode

                CSF::createSection($prefix, array(
                    'title' => 'Cool Timeline Shortcode',
                    'view' => 'normal', // View model of the shortcode. `normal` `contents` `group` `repeater`
                    'shortcode' => 'cool-timeline', // Set a unique slug-like name of shortcode.
                    'fields' => array(

                        array(
                            'id' => 'layout',
                            'type' => 'select',
                            'title' => 'Select Layout',                                                                                
                            'default' => 'default',
                            'desc'=>'Select your timeline Layout',                            
                            'options' => array(
                                'default' => 'Vertical',
                                'horizontal' => 'Horizontal',
                                'one-side' => 'One Side Layout',
                                'simple' => 'Simple Layout',
                                'compact' => 'Compact Layout',
                            ),
                             'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),
                         array(
                            'id' => 'skin',
                            'type' => 'select',
                            'title' => 'Select Skin',                                   
                            'default' => 'default', 
                            'dependency' => array( 'layout', '!=', 'horizontal' ),
                            'options' => array(
                                'default' => 'Default',
                                'clean' => 'Clean',                               
                            ),
                            'desc'=>'Create Light, Dark or Colorful Timeline', 
                             'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id' => 'date-format',
                            'type' => 'select',
                            'title' => 'Select Date Formats', 
                            'default' => 'F j',                           
                             'options' => array(
                                'F j' => 'F j',
                                'F j Y' => 'F j Y',
                                'Y-m-d' => 'Y-m-d',
                                'm/d/Y' => 'm/d/Y',  
                                'd/m/Y' => 'd/m/Y', 
                                 'F j Y g:i A' => 'F j Y g:i A',  
                                 'Y' => 'Y',
                            ),
                            'desc'=>'Timeline Stories dates formats', 
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id' => 'show-posts',
                            'type' => 'text',
                            'title' => 'Display Pers Page?',                           
                            'default' => '10',              
                             'desc'=>'You Can Show Pagination After These Posts In Vertical Timeline.',            
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id' => 'animation',
                            'type' => 'select',
                            'title' => 'Animation',
                            'default' =>'none',
                            'dependency' => array( 'layout', '!=', 'horizontal' ),
                            'options' => array(
                                'none' =>'none',
                                'fade-up' => 'fadeInUp',
                            ),
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),
                          array(
                            'id' => 'icons',
                            'type' => 'select',
                            'title' => 'Icons',
                            'default' =>'NO',                            
                            'options' => array(
                                'NO' => 'NO',
                                'YES' => 'YES',                                
                            ),
                            'desc'=>'Display Icons In Timeline Stories. By default Is Dot',
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                        ),

                        array(
                            'id' => 'order',
                            'type' => 'select',
                            'title' => 'Stories Order?',
                            'default' =>'DESC',                            
                            'options' => array(
                                'DESC' => 'DESC',
                                'ASC' => 'ASC',
                            ),
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),
                             'desc'=>'<span>Timeline Stories order like:- DESC(2017-1900) , ASC(1900-2017)</span>',
                        ),
                        array(
                            'id' => 'story-content',
                            'type' => 'select',
                            'title' => 'Stories Description?',
                            'default' =>'short',
                            'options' => array(
                                'short' => 'Summary',
                                'full' => 'Full Text',                                
                            ),
                           'desc'=>'<span>Summary:- Short description<br>Full:- All content with formated text.</span>',
                            'attributes' => array(
                                'style' => 'width: 50%;',
                            ),

                        ),                      
                        

                    )
                    ));

              

            }
        

            
        }

    }

}

new CSF_free_shortcode_generator();
