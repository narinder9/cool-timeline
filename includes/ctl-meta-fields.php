<?php 
         /*
            Creating Meta boxes for timeline stories section
         */

       function clt_meta_boxes() {
            /*
             * configure your meta box
             */
            $config = array(
                'id' => 'demo_meta_box', // meta box id, unique per meta box 
                'title' => __('Timeline Story Settings', 'cool-timeline'), // meta box title
                'pages' => array('cool_timeline'), // post types, accept custom post types as well, default is array('post'); optional
                'context' => 'normal', // where the meta box appear: normal (default), advanced, side; optional
                'priority' => 'high', // order of meta box: high (default), low; optional
                'fields' => array(), // list of meta fields (can be added by field arrays) or using the class's functions
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false            //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );
            /*
             * Initiate your meta box
             */
            $story_meta = new CTP_AT_Meta_Box($config);
            $story_meta->addDate('ctl_story_date', array('name' =>__('Story Date / Year <span class="ctl_required">*</span>','cool-timeline'), 'desc' =>__('<p class="ctl_required">Please select story Story Date / Year / Time using datepicker only. <strong>Date Format( mm/dd/yy hh:mm )</strong></p>','cool-timeline'),
             'std' => date('m/d/Y h:m a'),
             'format' =>__('d MM yy','cool-timeline')
             ));
            $story_meta->addRadio('img_cont_size', array('full' => __('Full', 'cool-timeline'), 'small' => __('Small', 'cool-timeline')), array('name' => __('Story image size', 'cool-timeline'),
            'desc' =>__('<div class="desc-field"><strong style="width: 100%;display: inline-block;margin-top: 40px;">Premium Settings | <a target="_blank" href="https://1.envato.market/7QLxy">Buy Pro</a></strong><img src="https://res.cloudinary.com/pinkborder/image/upload/v1556354078/cool-timeline-pro-wp-plugin/timeline-premium-features.png" style="max-width: 100%;border: 2px solid #ef2e2e;"></div>', 'cool-timeline'),
            'class'=>'story_format_image',
            'std' => array('full')));
            $story_meta->Finish();
            
        }
