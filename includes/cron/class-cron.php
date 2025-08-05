<?php
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('CTL_CRONJOB')) {
    class CTL_CRONJOB
    {
    

        public function __construct() {
           
       
          // Register cron jobs
            add_filter('cron_schedules', array($this, 'ctl_cron_schedules'));
            add_action('ctl_extra_data_update', array($this, 'ctl_cron_extra_data_autoupdater'));
        }
        
        function ctl_cron_extra_data_autoupdater() {
       
                if (class_exists('CTL_CRONJOB')) {
                    CTL_CRONJOB::ctl_send_data();
                }

        }
           
       static public function ctl_send_data() {
                   
            $feedback_url = CTL_FEEDBACK_API.'wp-json/coolplugins-feedback/v1/site';
            require_once CTL_PLUGIN_DIR . 'admin/feedback/users-feedback.php';
            
            if (!defined('CTL_PLUGIN_DIR')  ) {
                
                return;
            }
           
            $extra_data_details = CoolTimeline::ctl_get_user_info();
       
            $server_info    = $extra_data_details['server_info'];
            $extra_details  = $extra_data_details['extra_details'];
            $site_url       = get_site_url();
            $install_date   = get_option('ctl-install-date');
            $uni_id         = '31';
            $site_id        = $site_url . '-' . $install_date . '-' . $uni_id;
            $initial_version = get_option('ctl_initial_save_version');
            $initial_version = is_string($initial_version) ? sanitize_text_field($initial_version) : 'N/A';
            $plugin_version = defined('CTL_V') ? CTL_V : 'N/A';
            $admin_email    = sanitize_email(get_option('admin_email') ?: 'N/A');
            
            $post_data = array(

                'site_id'           => md5($site_id),
                'plugin_version'    => $plugin_version,
                'plugin_name'       => 'Cool Timeline ',
                'plugin_initial'    => $initial_version,
                'email'             => $admin_email,
                'site_url'          => esc_url_raw($site_url),
                'server_info'       => $server_info,
                'extra_details'     => $extra_details,
            );
            
            $response = wp_remote_post($feedback_url, array(

                'method'    => 'POST',
                'timeout'   => 30,
                'headers'   => array(
                    'Content-Type' => 'application/json',
                ),
                'body'      => wp_json_encode($post_data),
            ));

            
            if (is_wp_error($response)) {

                error_log('ctl Feedback Send Failed: ' . $response->get_error_message());
                return;
            }
            
            $response_body  = wp_remote_retrieve_body($response);
            $decoded        = json_decode($response_body, true);
            if (!wp_next_scheduled('ctl_extra_data_update')) {

                wp_schedule_event(time(), 'every_30_days', 'ctl_extra_data_update');
            }
        }
          
        /**
         * Cron status schedule(s).
         */
        public function ctl_cron_schedules($schedules)
        {
            // 30days schedule for update information
            if (!isset($schedules['every_30_days'])) {

                $schedules['every_30_days'] = array(
                    'interval' => 30 * 24 * 60 * 60, // 2,592,000 seconds
                    'display'  => __('Once every 30 days'),
                );
            }

            return $schedules;
        }

      
    }

    $cron_init = new CTL_CRONJOB();
}
