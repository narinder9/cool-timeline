<?php

namespace CTL\feedback;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class UsersFeedback{

	private $plugin_url = CTL_PLUGIN_URL;
	private $plugin_version = CTL_V;
	private $plugin_name = 'Cool Timeline';
	private $plugin_slug = 'ctl';
    /*
    |-----------------------------------------------------------------|
    |   Use this constructor to fire all actions and filters          |
    |-----------------------------------------------------------------|
    */
    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_feedback_scripts') );
    
        add_action('wp_ajax_'.$this->plugin_slug.'_submit_deactivation_response', array($this, 'submit_deactivation_response' ));
        add_action('admin_init',array($this,'onInit'));
    }
    public function onInit(){
        add_action('admin_head', array( $this, 'show_deactivate_feedback_popup') );
    }
    /*
    |-----------------------------------------------------------------|
    |   Enqueue all scripts and styles to required page only          |
    |-----------------------------------------------------------------|
    */
    function enqueue_feedback_scripts(){
        $screen = get_current_screen();
        if( isset( $screen ) && $screen->id == 'plugins' ){
            wp_enqueue_script(__NAMESPACE__.'feedback-script', $this->plugin_url .'admin/feedback/js/admin-feedback.js',array('jquery'),$this->plugin_version );
            wp_enqueue_style('cool-plugins-feedback-style', $this->plugin_url .'admin/feedback/css/admin-feedback.css',null,$this->plugin_version );
        }
    }

    /*
    |-----------------------------------------------------------------|
    |   HTML for creating feedback popup form                         |
    |-----------------------------------------------------------------|
    */
    public function show_deactivate_feedback_popup() {
		$screen = get_current_screen();
		if( !isset( $screen ) || $screen->id != 'plugins' ){
			return;
		}
		$deactivate_reasons = [
            'didnt_work_as_expected' => [
                'title' => esc_html(__( 'The plugin didn\'t work as expected', 'cool-plugins' )),
                'input_placeholder' => 'What did you expect?',
            ],
            'found_a_better_plugin' => [
                'title' => esc_html(__( 'I found a better plugin', 'cool-plugins' )),
                'input_placeholder' =>esc_html( __( 'Please share which plugin', 'cool-plugins' )),
            ],
            'couldnt_get_the_plugin_to_work' => [
                'title' => esc_html(__( 'The plugin is not working', 'cool-plugins' )),
                'input_placeholder' => 'Please share your issue. So we can fix that for other users.',
            ],
            'temporary_deactivation' => [
                'title' => esc_html(__( 'It\'s a temporary deactivation', 'cool-plugins' )),
                'input_placeholder' => '',
            ],
            'other' => [
                'title' => esc_html(__( 'Other', 'cool-plugins' )),
                'input_placeholder' => esc_html(__( 'Please share the reason', 'cool-plugins') ),
            ],
        ];

		?>
		<div id="cool-plugins-deactivate-feedback-dialog-wrapper" class="hide-feedback-popup">
			            
            <div class="cool-plugins-deactivation-response">
            <div id="cool-plugins-deactivate-feedback-dialog-header">
				<span id="cool-plugins-feedback-form-title"><?php echo esc_html(__( 'Quick Feedback', 'cool-plugins' )); ?></span>
            </div>
            <div id="cool-plugins-loader-wrapper">
				<div class="cool-plugins-loader-container">
                    <img class="cool-plugins-preloader" src="<?php echo esc_url($this->plugin_url); ?>admin/feedback/images/cool-plugins-preloader.gif">
                </div>
            </div>
            <div id="cool-plugins-form-wrapper" class="cool-plugins-form-wrapper-cls">
			<form id="cool-plugins-deactivate-feedback-dialog-form" method="post">
				<?php
				wp_nonce_field( '_cool-plugins_deactivate_feedback_nonce',"$this->plugin_slug-wpnonce" );
				?>
				<input type="hidden" name="action" value="cool-plugins_deactivate_feedback" />
                <div id="cool-plugins-deactivate-feedback-dialog-form-caption"><?php echo esc_html(__( 'If you have a moment, please share why you are deactivating this plugin.', 'cool-plugins' )); ?></div>
				<div id="cool-plugins-deactivate-feedback-dialog-form-body">
					<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
						<div class="cool-plugins-deactivate-feedback-dialog-input-wrapper">
							<input id="cool-plugins-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="cool-plugins-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo esc_attr( $reason_key ); ?>" />
							<label for="cool-plugins-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="cool-plugins-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?></label>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<textarea class="cool-plugins-feedback-text" type="textarea" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>"></textarea>
							<?php endif; ?>
							<?php if ( ! empty( $reason['alert'] ) ) : ?>
								<div class="cool-plugins-feedback-text"><?php echo esc_html( $reason['alert'] ); ?></div>
							<?php endif; ?>
						</div>
                    <?php endforeach; ?>
                    <input class="cool-plugins-GDPR-data-notice" id="cool-plugins-GDPR-data-notice" type="checkbox"><label for="cool-plugins-GDPR-data-notice"><?php echo esc_html(__('I consent to having Cool Plugins store my all submitted information via this form, they can also respond to my inquiry.','cool-plugins'));?></label>
                </div>
                <div class="cool-plugin-popup-button-wrapper">
                    <a class="cool-plugins-button button-deactivate" id="cool-plugin-submitNdeactivate">Submit and Deactivate</a>
                    <a class="cool-plugins-button" id="cool-plugin-skipNdeactivate">Skip and Deactivate</a>
                </div>
            </form>
            </div>
           </div>
		</div>
		<?php
    }
    

    function submit_deactivation_response(){
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], '_cool-plugins_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}else{
            $reason = sanitize_text_field($_POST['reason']);
            $deactivate_reasons = [
                'didnt_work_as_expected' => [
                    'title' => esc_html(__( 'The plugin didn\'t work as expected', 'cool-plugins' )),
                    'input_placeholder' => 'What did you expect?',
                ],
                'found_a_better_plugin' => [
                    'title' => esc_html(__( 'I found a better plugin', 'cool-plugins' )),
                    'input_placeholder' =>esc_html( __( 'Please share which plugin', 'cool-plugins' )),
                ],
                'couldnt_get_the_plugin_to_work' => [
                    'title' => esc_html(__( 'The plugin is not working', 'cool-plugins' )),
                    'input_placeholder' => 'Please share your issue. So we can fix that for other users.',
                ],
                'temporary_deactivation' => [
                    'title' => esc_html(__( 'It\'s a temporary deactivation', 'cool-plugins' )),
                    'input_placeholder' => '',
                ],
                'other' => [
                    'title' => esc_html(__( 'Other', 'cool-plugins' )),
                    'input_placeholder' => esc_html(__( 'Please share the reason', 'cool-plugins') ),
                ],
            ];
    
            $deativation_reason = array_key_exists( $reason, $deactivate_reasons ) ? $reason : 'other'; 
          			
            $sanitized_message = sanitize_text_field($_POST['message'])==''?'N/A':sanitize_text_field($_POST['message']);
            $admin_email = sanitize_email(get_option('admin_email'));
            $site_url = esc_url(site_url());
            $feedback_url = esc_url('http://feedback.coolplugins.net/wp-json/coolplugins-feedback/v1/feedback');
			$response = wp_remote_post( $feedback_url , [
                'timeout' => 30,
                'body' => [
                    'plugin_version' => $this->plugin_version,
                    'plugin_name' => $this->plugin_name,
					'reason' => $deativation_reason,
					'review' => $sanitized_message,
					'email'	=>	$admin_email,
					'domain' => $site_url,
                ],
			] );
			
            die( json_encode( array('response'=>$response) ) );
        }

    }
}
new UsersFeedback();