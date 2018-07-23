<?php
	if (function_exists('bdthemes_testimonial_shortcode')) {
		function bdthemes_testimonial_vc() {
			vc_map(
				array(
					"name"        => __("Testimonial","warp"),
					"base"        => "bdt_testimonial",
					"icon"        => "vc-testimonial",
					"category"    => "Theme Addons",
					"description" => __("Testimonial.","warp"),
					"params"      => array(
						array(
							'type' => 'textfield',
							'heading' => __( 'Name', 'warp' ),
							'value' => 'John Doe',
							'param_name' => 'name',
							'description' => __( 'Type name here that you want to show for title', 'warp' ),
						),
				   		array(
							"type"       => "dropdown",
							"heading"    => __("Testimonial Style", "warp"),
							"param_name" => "style",
		                    'value' => array(
		                        'Style1' => __('1', "warp"),
		                        'Style2' => __('2', "warp"),
		                        'Style3' => __('3', "warp")
		                    ),
							"group" => 'Style',
							"description" => __("Select style for Testimonial.", "warp"),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Title', 'warp' ),
							'param_name' => 'title',
							'description' => __( '', 'warp' ),
						),
						array(
							'type' => 'attach_image',
							'heading' => __( 'Photo', 'warp' ),
							'param_name' => 'photo',
							'value' => '',
							'description' => __( 'Select image from media library.', 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Company', 'warp' ),
							'param_name' => 'company',
							'description' => __( 'Type here a company name. Leave this field empty to hide company name', 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Website URL', 'warp' ),
							'param_name' => 'url',
							'description' => __( 'Enter the client company website url. Leave empty to disable link', 'warp' ),
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Target', 'warp' ),
							'param_name' => 'target',
							'value' => array(
								__( 'Same window', 'warp' ) => '_self',
								__( 'New window', 'warp' ) => '_blank',
							),
							'description' => __( 'Set link target self or blank', 'warp' )
						),
						array(
							"type"       => "checkbox",
							"class"      => "",
							"heading"    => __("Italic", "warp"),
							"param_name" => "italic",
							"value"      => array( __("Yes","warp") => "yes" ),
							"group" => 'Style',
							"description" => __("If you want show content italic, so tick", "warp"),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Radius', 'warp' ),
							'param_name' => 'radius',
							"group" => 'Style',
							'description' => __( 'You can set border radius from here, for example: 3px, 10px, 25px also you can set value as em, % etc if you need', 'warp' ),
						),
						array(
							'type' => 'textarea_html',
							'holder' => 'div',
							'heading' => __( 'Text', 'warp' ),
							'param_name' => 'content',
							'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'warp' ),
						)
					)	
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_testimonial_vc' );
	}