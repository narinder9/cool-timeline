<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Control core classes for avoid errors
if ( class_exists( 'CSF' ) ) {

	//
	// Set a unique slug-like ID
	$prefix = 'cool_timeline_settings';

	//
	// Create options
	CSF::createOptions(
		$prefix,
		array(
			'framework_title'    => 'Timeline Settings',
			'menu_title'         => 'Timeline Settings',
			'menu_slug'          => 'cool_timeline_settings',
			'menu_type'          => 'submenu',
			'menu_parent'        => 'cool-plugins-timeline-addon',
			'menu_capability'    => 'manage_options', // The capability needed to view the page
			'menu_icon'          => CTL_PLUGIN_URL . 'assets/images/cool-timeline-icon.svg',
			'menu_position'      => 6,
			'nav'                => 'inline',
			'show_reset_section' => false,
			'show_reset_all'     => false,
			'show_bar_menu'      => false,

			'footer_text'        => '',
		)
	);

	//
	// Create a section
	CSF::createSection(
		$prefix,
		array(
			'title'  => 'General Settings',
			'fields' => array(



				// Create a Fieldset
				array(
					'id'     => 'timeline_header',
					'type'   => 'fieldset',
					'title'  => 'Content Before Timeline',
					'fields' => array(
						array(
							'id'    => 'title_text',
							'type'  => 'text',
							'title' => __( 'Timeline Title', 'cool-timeline' ),
						),
					),
				), // End Fieldset


			// Create a Fieldset
				array(
					'id'     => 'story_content_settings',
					'type'   => 'fieldset',
					'title'  => 'Story Content',
					'fields' => array(
						array(
							'id'      => 'content_length',
							'type'    => 'text',
							'title'   => __( 'Content Length', 'cool-timeline' ),
							'default' => '50',
							'desc'    => __( 'Please enter no of words', 'cool-timeline' ),
						),
						array(
							'id'      => 'display_readmore',
							'type'    => 'radio',
							'title'   => __( 'Display read more?', 'cool-timeline' ),
							'options' => array(
								'yes' => __( 'Yes', 'cool-timeline' ),
								'no'  => __( 'No', 'cool-timeline' ),
							),
							'desc'    => __( 'It will also disable link from story title.', 'cool-timeline' ),
							'inline'  => true,
							'default' => 'yes',
						),
						array(
							'id'         => 'story_link_target',
							'type'       => 'radio',
							'title'      => __( 'Open read more link in?', 'cool-timeline' ),
							'options'    => array(
								'_self'  => __( 'Same Tab', 'cool-timeline' ),
								'_blank' => __( 'New Tab', 'cool-timeline' ),
							),
							'inline'     => true,
							'default'    => '_self',
							'dependency' => array( 'display_readmore', '==', 'yes' ),
						),

					),
				), // End Fieldset


				array(
					'id'     => 'story_date_settings',
					'type'   => 'fieldset',
					'title'  => 'Story Date',
					'fields' => array(

						array(
							'id'         => 'year_label_visibility',
							'type'       => 'switcher',
							'title'      => __( 'Year Label', 'cool-timeline' ),
							'text_on'    => __( 'Show', 'cool-timeline' ),
							'text_off'   => __( 'Hide', 'cool-timeline' ),
							'text_width' => 100,
							'default'    => true,
							'desc'       => __( 'Only for Vertical and One sided layout', 'cool-timeline' ),
						),

					),
				), // End Fieldset


				array(
					'id'      => 'first_story_position',
					'type'    => 'button_set',
					'title'   => 'Vertical Timeline Stories Starts From',
					'desc'    => 'Not for Compact and Horizontal layout',
					'options' => array(
						'left'  => 'Left',
						'right' => 'Right',
					),
					'default' => 'right',
				),

			),
		)
	);


	// Create a section
	CSF::createSection(
		$prefix,
		array(
			'title'  => 'Style Settings',
			'fields' => array(

				array(
					'id'      => 'content_bg_color',
					'type'    => 'color',
					'title'   => 'Story Background Color',
					'default' => '#ffffff',
				),

				array(
					'id'      => 'circle_border_color',
					'type'    => 'color',
					'title'   => 'Circle Color',
					'default' => '#38aab7',
				),

				array(
					'id'      => 'line_color',
					'type'    => 'color',
					'title'   => 'Line Color',
					'default' => '#025149',
				),

				array(
					'id'      => 'first_post',
					'type'    => 'color',
					'title'   => 'First Color',
					'default' => '#29b246',
				),

				array(
					'id'      => 'second_post',
					'type'    => 'color',
					'title'   => 'Second Color',
					'default' => '#ce792f',
				),

				array(
					'id'       => 'custom_styles',
					'type'     => 'code_editor',
					'title'    => 'Custom Styles',
					'settings' => array(
						'theme' => 'mbo',
						'mode'  => 'css',
					),

				),

			),
		)
	);

	// Create a section
	CSF::createSection(
		$prefix,
		array(
			'title'  => 'Typography Setings',
			'fields' => array(

				array(
					'id'         => 'ctl_date_typo',
					'type'       => 'typography',
					'title'      => 'Story Date',
					'default'    => array(
						'font-family' => 'Maven Pro',
						'font-size'   => '21',
						'line-height' => '',
						'unit'        => 'px',
						'type'        => 'google',
						'font-weight' => '700',
					),
					'text_align' => false,
					'color'      => false,
				),

				array(
					'id'      => 'post_title_typo',
					'type'    => 'typography',
					'title'   => 'Story Title',
					'default' => array(
						'font-family' => 'Maven Pro',
						'font-size'   => '20',
						'line-height' => '',
						'unit'        => 'px',
						'type'        => 'google',
						'font-weight' => '700',
					),
					'color'   => false,
				),

				// A textarea field


				array(
					'id'      => 'post_content_typo',
					'type'    => 'typography',
					'title'   => 'Post Content',
					'default' => array(
						'font-family' => 'Maven Pro',
						'font-size'   => '16',
						'line-height' => '',
						'unit'        => 'px',
						'type'        => 'google',
					),
					'color'   => false,
				),


			),


		)
	);

	// Create a section
	CSF::createSection(
		$prefix,
		array(
			'title'  => 'Advance Settings',
			'fields' => array(

				array(
					'id'      => 'advanced-features',
					'type'    => 'content',
					'content' => '<div class="advance_options" style="text-align:center"><a target="_blank" href="' . CTL_BUY_PRO . '?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=settings">
          <img src="' . CTL_PLUGIN_URL . 'assets/images/pro-features-list.png" ></a></div>',

				),

			),
		)
	);


	function ctl_demo_page_content() {

		ob_start();
		?>
		<div class="ctl_started-section">
			<div class="ctl_tab_btn_wrapper">
				<button class="button ctl_class_post_button ctl_tab_active">Classic Timeline Shortcode</button>
				<button class="button ctl_block_timeline_button">Modern Timeline Block</button>
				<button class="button button-info ctl_elementor_addon_button">Elementor Timeline Widgets</button>
			</div>
			<div class="tab_panel">
				<div class="wrapper_first">
					<div class="ctl_step">
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2><a href="post-new.php?post_type=cool_timeline">1. Create Timeline Story</a></h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Open timeline addons and add a new story.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Craft a compelling <b>Title </b>and
										<b>Description</b> for your story.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Specify the date for your timeline story.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Personalize your timeline with icons and images.</span>
									</li>
								</ol>
							</div>
						</div>
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
		
					<div class="ctl_step ctl_col-rev">
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=71"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2><a href="post-new.php?post_type=page">2. Add Shortcode (Gutenberg)</a></h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
		
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Search for <b>Cool Timeline</b> in the block search box.
										</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Access settings in the shortcode block to generate the timeline shortcode.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Publish the page and preview the output on the frontend.
										</span>
									</li>
		
								</ol>
							</div>
						</div>
					</div>

					<div class="ctl_step">
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2><a href="post-new.php?post_type=page">2. Add Shortcode (Classic Editor)</a></h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Create or edit a page.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Click the <b>Add Timeline</b> button.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Access General Settings to configure options.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Navigate to the preview tab for a Live Preview.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Click the <b>Insert Shortcode</b> button to add the shortcode.</span>
									</li>
								</ol>
							</div>
						</div>
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=41"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>

					<div class="ctl_step ctl_col-rev">
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=238"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2>3. Timeline Configuration</h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Configure general settings for your timeline, including title, story content, story year labels, and timeline position.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Customize style settings to adjust timeline story colors and apply custom CSS.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Set typography settings to style your timeline date, story, and story content fonts.</span>
									</li>
								</ol>
							</div>
						</div>
					</div>

				</div>

				<div class="wrapper_second" style="display:none;">

					<div class="ctl_step">
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2><a href="post-new.php?post_type=page">1. Add Timeline Block</a></h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
		
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">
										Search for <b>Cool Timeline Block</b> in the block search box.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Access Additional Settings by navigating to the right side of the Cool Timeline Block.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Utilize General, Styles, and Advanced Settings within the Cool Timeline Block.</span>
									</li>
								</ol>
							</div>
						</div>
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=125" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
		
					<div class="ctl_step ctl_col-rev">
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=165"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write;encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2>2. Timeline Block Style Settings</h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Access the Style Settings for the Cool Timeline Block.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Modify the color of story Title, Description, and Labels.</span>
									</li>
								</ol>
							</div>
						</div>
					</div>

					<div class="ctl_step">
						<div class="ctl_step-content">
							<div class="ctl_steps-title">
								<h2>3. Timeline Block Advanced Settings</h2>
							</div>
							<div class="ctl_steps-list">
								<ol>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">
										Access advanced settings for the Cool Timeline Block.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Customize the Line, Icon, Icon Background, and Story Border color.</span>
									</li>
									<li class="ctl_step-data">
										<!-- <span class="ctl_list-icon"><i class="fa fa-check" aria-hidden="true"></i></span> -->
										<span class="ctl_list-text">Adjust the size of Line, Icon Box, and Item Spacing.</span>
									</li>
								</ol>
							</div>
						</div>
						<div class="ctl_video-section">
							<iframe class="ctl_timeline-video" width="560" height="315" src="https://www.youtube.com/embed/eBoNMy2fjg8?si=VSz5YrO_ZOqqvoJT&amp;start=205" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			const ClassicPostButton = jQuery(".ctl_class_post_button");
			const BlockTimelineButton = jQuery(".ctl_block_timeline_button");
			const ElementAddonsButton = jQuery(".ctl_elementor_addon_button");
			const firstWrapper = jQuery(".wrapper_first");
			const secondWrapper = jQuery(".wrapper_second");
			ClassicPostButton.on("click", (event) => {
				firstWrapper.css("display","block");
				secondWrapper.css("display","none");
				event.preventDefault();
				ClassicPostButton.siblings().removeClass('ctl_tab_active');
				ClassicPostButton.addClass('ctl_tab_active');
			});
			BlockTimelineButton.on("click", (event) => {
				firstWrapper.css("display","none");
				secondWrapper.css("display","block");
				event.preventDefault();
				BlockTimelineButton.siblings().removeClass('ctl_tab_active');
				BlockTimelineButton.addClass('ctl_tab_active');
			});
			ElementAddonsButton.on("click",(event)=>{
				window.open("https://coolplugins.net/product/elementor-timeline-widget-pro-addon/?utm_source=ctl_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=get-started-tabs", "_blank");
				event.preventDefault();
			})
		</script>
			
		<!-- return $data; -->
		<?php
		return ob_get_clean();
	}
	// Create a section
	CSF::createSection(
		$prefix,
		array(
			'title'  => 'Get Started',
			'fields' => array(
				array(
					'id'      => 'timeline_display',
					'type'    => 'content',
					'content' => ctl_demo_page_content(),
				),
			),
		)
	);


}
