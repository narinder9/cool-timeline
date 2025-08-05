<?php

if ( ! defined( 'ABSPATH' )) exit;

class CPFM_Feedback_Notice {
    
    private static $registered_notices = [];
    
    public function __construct() {
        
        add_action('admin_init', [ $this, 'cpfm_listen_for_external_notice_registration' ]);
        add_action('admin_enqueue_scripts', [ $this, 'cpfm_enqueue_assets' ]);
        add_action('wp_ajax_cpfm_handle_opt_in', [ $this, 'cpfm_handle_opt_in_choice' ]);

        add_action('admin_footer', [ $this, 'cpfm_render_notice_panel' ]);
    }
    
    public static function cpfm_register_notice($key, $args) {
        
        if (!current_user_can('manage_options')) {
            
            return;
        }
        
        if (!isset(self::$registered_notices[$key])) {
            self::$registered_notices[$key] = wp_parse_args($args, [
                'title'   => '',
                'message' => '',
                'pages'   => [],
                'always_show_on' => [],
            ]);
        }
         self::$registered_notices[$key][] = $args;
    }
    
    public function cpfm_listen_for_external_notice_registration() {
        

        if (!current_user_can('manage_options')) {

            return;
        }

        /**
         * Allow other plugins to register notices dynamically.
         * Example usage in other plugins:
         * do_action('cpf_cpfm_register_notice', 'crypto', [
         *     'title' => 'Crypto Plugin Notice',
         *     'message' => 'This is a crypto dashboard setup notice.',
         *     'pages' => ['dashboard', 'cpfm_'],
         * ]);
         */
        do_action('cpfm_register_notice');
    }

    public function cpfm_enqueue_assets() {

        if (!current_user_can('manage_options')) {

            return;

        }
       

       $current_page   = isset($_GET['page'])? sanitize_key($_GET['page']):'';

        
        $allowed_pages = [];
        
        foreach (self::$registered_notices as $notice) {
            if (!empty($notice['pages']) && is_array($notice['pages'])) {
                $allowed_pages = array_merge($allowed_pages, $notice['pages']);
            }
        }
    
        // Early return if not needed
        if (!in_array($current_page, array_unique($allowed_pages))) {
            return;
        }
        wp_enqueue_style(
            'cpfm-common-review-style',
            CTL_PLUGIN_URL . 'admin/feedback/css/cpfm-admin-feedback.css',
            [],
            CTL_V
        );
        
        wp_enqueue_script(
            'cpfm-common-review-script', 
            CTL_PLUGIN_URL . 'admin/feedback/js/cpfm-admin-feedback.js', 
            ['jquery'], 
            CTL_V, 
            true

        );
        wp_localize_script('cpfm-common-review-script', 'adminNotice', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('dismiss_admin_notice'),
            'autoShowPages' => array_unique(
                array_merge(
                    [],
                    ...array_filter(
                        array_column(self::$registered_notices, 'always_show_on'),
                        function($pages) { return !empty($pages); }
                    )
                )
                    ),
        ]);
    }
  
    public function cpfm_handle_opt_in_choice() {

        if (!current_user_can('manage_options')) {

            wp_send_json_error('Unauthorized access.');
        }

        check_ajax_referer('dismiss_admin_notice', 'nonce');

        $category   = isset($_POST['category']) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ): '';
        $opt_in_raw = isset($_POST['opt_in']) ? sanitize_text_field( wp_unslash( $_POST['opt_in'] ) ) : '';
        $opt_in = ($opt_in_raw === 'yes') ? 'yes' : 'no';
        $category_notices   = self::$registered_notices;
        $registered_notices = isset($GLOBALS['cool_plugins_feedback'])? $GLOBALS['cool_plugins_feedback']:$category_notices;

        if (!$category || !isset(self::$registered_notices[$category])) {

            wp_send_json_error('Invalid notice category.');
        }

        update_option("cpfm_opt_in_choice_{$category}", $opt_in);

        $review_option = get_option("cpfm_opt_in_choice_{$category}");
       
        if ($review_option === 'yes') {
            
             foreach (self::$registered_notices[$category] as $notice) {

                    $plugin_name = isset($notice['plugin_name'])?sanitize_key($notice['plugin_name']):'';

                    if($plugin_name){

                        do_action('cpfm_after_opt_in_' . $plugin_name, $category);
                    }
              
            }
          
        }

        wp_send_json_success();
    }


    public function cpfm_render_notice_panel() {
    
        
        if (!current_user_can('manage_options') || !function_exists('get_current_screen')) { 
            return;
        }

        $screen         = get_current_screen();
        $current_page   = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';

       
        $unread_count   = 0;
        $auto_show      = false;
    
        foreach (self::$registered_notices as $notice) {

            if (!empty($notice['always_show_on']) && in_array($current_page, (array) $notice['always_show_on'])) {
                $auto_show = true;
                break;
            }
        }
    
        $output = '';
        $output .= '<div id="cpfNoticePanel" class="notice-panel"' . ($auto_show ? ' data-auto-show="true"' : '') . '>';
        $output .= '<div class="notice-panel-header">' . esc_html__('Help Improve Plugins', 'ccpw') . ' <span class="dashicons dashicons-no" id="cpfm_remove_notice"></span></div>';
        $output .= '<div class="notice-panel-content">';
    
        foreach (self::$registered_notices as $key => $notice) {

            $choice = get_option("cpfm_opt_in_choice_{$key}");

            if ($choice !== false) continue;
    
            $should_show = false;
            foreach ($notice['pages'] as $match) {
                
                if ($current_page === $match || strpos($current_page, $match) === 0) {
                
                    $should_show = true;
                    break;
                }
            }
    
            if (!$should_show) continue;
            $unread_count++;
    
            $output .= '<div class="notice-item unread" data-notice-id="' . esc_attr($key) . '">';
            $output .= '<strong>' . esc_html($notice['title']) . '</strong>';
            
            $output .= '<div class="notice-message-with-toggle">';
            $output .= '<p>' . esc_html($notice['message']) . '<a href="#" class="cpf-toggle-extra">' . esc_html__(' More info', 'ccpw') . '</a></p>';
            $output .= '</div>';
            
            $output .= '<div class="cpf-extra-info">';
            $output .= '<p>' . esc_html__('Opt in to receive email updates about security improvements, new features, helpful tutorials, and occasional special offers. We\'ll collect:', 'ccpw') . '</p>';
            $output .= '<ul>';
            $output .= '<li>' . esc_html__('Your website home URL and WordPress admin email.', 'ccpw') . '</li>';
            $output .= '<li>' . esc_html__('To check plugin compatibility, we will collect the following: list of active plugins and themes, server type, MySQL version, WordPress version, memory limit, site language and database prefix.', 'ccpw') . '</li>';
            $output .= '</ul>';
            
            $output .= '</div>';
            
            $output .= '<div class="notice-actions">';
            $output .= '<button class="button button-primary opt-in-yes" data-category="' . esc_attr($key) . '" id="yes-share-data" value="yes">' . esc_html__("Yes, I Agree", 'ccpw') . '</button>';
            $output .= '<button class="button opt-in-no" data-category="' . esc_attr($key) . '" id="no-share-data" value="no">' . esc_html__('No, Thanks', 'ccpw') . '</button>';
            $output .= '</div>';
            
            $output .= '</div>';
        }
    
        $output .= '</div>';
        $output .= '</div>'; 
     
        if ($unread_count > 0) {
            echo wp_kses_post($output);
        }
    }
}
new CPFM_Feedback_Notice();
