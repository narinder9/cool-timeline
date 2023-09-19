<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CoolTmVCAddon
{
    /**
     * Registers our plugin with WordPress.
     */
    public static function register()
    {
        $vc_addon = new self();
        // We safely integrate with VC with this hook
        add_action( 'init', array($vc_addon, 'ctl_vc_addon' ) );
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Setup your plugin object here
    }

    public function ctl_vc_addon(){
        if (  defined( 'WPB_VC_VERSION' ) ) {
            $date_formats= array(
                "Default" => "default",
                "F j" => "F j",
                "F j Y" => "F j Y",
                "Y-m-d" => "Y-m-d",
                "m/d/Y" => "m/d/Y",
                "d/m/Y" => "d/m/Y",
                "F j Y g:i A" => "F j Y g:i A",
                "Y" => "Y",
            );
            $animation_effects=array(
                "none" =>"none",
                "fade-up" =>"fadeInUp",
            );
            
            vc_map(
                array(
                    "name" => __("Cool Timeline", 'cool-timeline'),
                    "description" => __("Create Stories Timeline", 'cool-timeline'),
                    "base" => "cool-timeline",
                    "class" => "",
                    "controls" => "full",
                    "icon" => CTL_PLUGIN_URL.'assets/images/timeline-icon2-32x32.png', // or css class name which you can reffer in your css file later. Example: "cool-timeline_my_class"
                    "category" => __('Cool Timeline', 'cool-timeline'),
                    "params" => array(
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Select Timeline Layout",'cool-timeline'),
                            "param_name" => "layout",
                            "value" => array(
                                __( "Vertical Timeline (Default)",'cool-timeline' ) => "default",
                                __( "Vertical one sided",'cool-timeline') => "one-side",
                                __( "Compact Layout",'cool-timeline') => "compact",
                                __( "Horizontal Timeline",'cool-timeline') => "horizontal"
                            ),
                            "description" => __('','cool-timeline' ),
                            'save_always' => true,
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => __("Show number of Stories", 'cool-timeline'),
                            "param_name" => "show-posts",
                            "value" => __(10,'cool-timeline'),
                            'save_always' => true,
                            "description" => __("You Can Show Pagination After These Posts In Vertical Timeline.", 'cool-timeline')   
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Order",'cool-timeline'),
                            "param_name" => "order",
                            "value" => array(
                                __( "DESC",'cool-timeline' ) => "DESC",
                                __( "ASC",'cool-timeline') => "ASC",
                            ),
                            "description" => __( "Timeline Stories order like:- DESC(2017-1900) , ASC(1900-2017)",'cool-timeline' ),
                            'save_always' => true,
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Date Formats",'cool-timeline'),
                            "param_name" => "date-format",
                            "value" =>$date_formats,
                            "description" => __( "Timeline Stories dates custom formats",'cool-timeline' ),
                            'save_always' => true,
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Story Content",'cool-timeline'),
                            "param_name" => "story-content",
                            "value" => array(
                                __( "Summary",'cool-timeline' ) => "short",
                                __( "Full Text",'cool-timeline') => "full"
                            ),
                            "description" => __('','cool-timeline' ),
                            'save_always' => true,
                            "dependency" => array("element" => "layout", "value" => array("default","one-side","compact"))
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Timeline skin",'cool-timeline'),
                            "param_name" => "skin",
                            "value" => array(
                                __( "Default",'cool-timeline' ) => "default",
                                __( "Clean",'cool-timeline') => "clean",
                            ),
                            "description" => __( "Create Light, Dark or Colorful Timeline",'cool-timeline' ),
                            'save_always' => true,
                            "dependency" => array("element" => "layout", "value" => array("default","one-side","compact"))
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Icons",'cool-timeline'),
                            "param_name" => "icons",
                            "value" => array(
                                __( "YES",'cool-timeline' ) => "YES",
                                __( "NO",'cool-timeline') => "NO",
                            ),
                            "description" => __( "Display Icons In Timeline Stories. By default Is Dot.",'cool-timeline' ),
                            'save_always' => true,
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => __( "Animations Effect",'cool-timeline'),
                            "param_name" => "animation",
                            "value" =>$animation_effects,
                            "description" => __( "Add Animations Effect Inside Timeline. You Can Check Effects Demo From <a  target='_blank' href='http://michalsnik.github.io/aos/'>AOS</a>",'cool-timeline' ),
                            'save_always' => true,
                            "dependency" => array("element" => "layout", "value" => array("default","one-side","compact"))
                        )
                    )
                )
            );

        }
    }
}
CoolTmVCAddon::register();