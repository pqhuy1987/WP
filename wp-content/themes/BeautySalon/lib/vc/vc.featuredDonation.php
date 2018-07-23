<?php
	if (function_exists('bdthemes_featured_donation_shortcode')) {
		function bdthemes_featured_donation_vc() {
			vc_map(
				array(
					"name"        => __("Featured Donation","warp"),
					"base"        => "bdt_featured_donation",
					"icon"        => "vc-featured-donation",
					"category"    => "Theme Addons",
					"description" => __("You can add featured donation.","warp"),
					"params"      => array(
						array(
							'type' => 'textfield',
							'heading' => __( 'Title', 'warp' ),
							'value' => 'Featured Donation Title',
							'param_name' => 'title',
							'description' => __( 'Type here that you want to show for title', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Title Color', 'warp' ),
							'value'       => '#ffffff',
							'param_name'  => 'title_color',
							'group'  => 'Style',
							'description' => __( 'Choose a color for title', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Title Size', 'warp' ),
							'value'       => '20px',
							'param_name'  => 'title_size',
							'group'  => 'Style',
							'description' => __( 'Enter pixel value for title size', 'warp' ),
						),
						array(
							'type'        => 'attach_image',
							'heading'     => __( 'Photo', 'warp' ),
							'param_name'  => 'image',
							'value'       => '',
							'description' => __( 'Select image from media library.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Goal Value', 'warp' ),
							'param_name'  => 'goal',
							'value'       => '',
							'group'  => 'Progress',
							'description' => __( 'Enter numeric goal value.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Achieve Value', 'warp' ),
							'param_name'  => 'achieve',
							'value'       => '',
							'group'  => 'Progress',
							'description' => __( 'Enter numeric achieve value.', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Bar Color', 'warp' ),
							'param_name'  => 'bar_color',
							'value'       => '#E8E8E8',
							'group'  => 'Progress',
							'description' => __( 'Choose a color for progress bar', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Fill Color', 'warp' ),
							'param_name'  => 'fill_color',
							'value'       => '#F39C12',
							'group'  => 'Progress',
							'description' => __( 'Choose progress bar fill color', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Extra class name', 'warp' ),
							'param_name'  => 'class',
							'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'warp' ),
						),
						array(
							'type'        => 'textarea_html',
							'heading'     => __( 'Content Text', 'warp' ),
							'param_name'  => 'content',
							'value'       => __( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.<br></br>
							<a href="#" class="readon border">Donate Now</a>', 'warp' ),
						)
					)	
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_featured_donation_vc' );
	}
