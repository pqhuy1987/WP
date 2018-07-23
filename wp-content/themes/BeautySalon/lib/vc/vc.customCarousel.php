<?php
	if (function_exists('bdthemes_custom_carousel')) {
		function bdthemes_custom_carousel_vc() {
			vc_map(
				array(
					"name"                    => __("Custom Carousel","warp"),
					"base"                    => "bdt_custom_carousel",
					"icon"                    => "vc-custom-carousel",
					"category"                => "Theme Addons",
					"description"             => __("Describe your content as a carousel.","warp"),
					'is_container'            => true,
					'show_settings_on_create' => true,
					"as_parent"               => array('only' => 'vc_column_text, bdt_team_member, vc_single_image, bdt_testimonial, bdt_progress_pie'),
					"params" => array(			
						array(
					   		'type' => 'dropdown',
							'heading' => __('Gutter', 'warp'),
							'param_name' => 'gutter',
							'value' => array(
								__('Collapse', 'warp') => 'collapse',
								__('Large', 'warp') => 'large',
								__('Medium', 'warp') => 'medium',
								__('Small', 'warp') => 'small'
							),
							'std'         => 'medium',
							'description' => __('Gutter of the event carousel.', 'warp')
						),
						array(
							"type"			=> "dropdown",
							"heading"		=> __( "Navigation", 'warp' ),
							"param_name"	=> "arrows",
							"value"			=> array(
								'Show' 	=> 'true',
								'Hide' => 'false',
							),
							'description' => __( 'Show or hide navigation from here.', 'warp' ),
							"std"         => "true",
							"group"	=> __( 'Carousel Settings', "warp")
						),
						array(
							"type"			=> "dropdown",
							"heading"		=> __( "Pagination", 'warp' ),
							"param_name"	=> "pagination",
							"value"			=> array(
								'Show' 	=> 'true',
								'Hide' => 'false',
							),
							'description' => __( 'Show or hide pagination from here.', 'warp' ),
							"std"         => "true",
							"group"	=> __( 'Carousel Settings', "warp")
						),
						array(
							"type"        => "number",
							"heading"     => __("Delay", "warp"),
							"param_name"  => "delay",
							"value"       => 4000,
							"group"	=> __( 'Carousel Settings', "warp"),
							"description" => __("Delay of the event carousel. It's set <b>ms</b> value.", "warp")
						),
						array(
							"type"        => "number",
							"heading"     => __("Speed", "warp"),
							"param_name"  => "speed",
							"value"       => 350,
							"group"	=> __( 'Carousel Settings', "warp"),
							"description" => __("Speed of the event carousel. It's set <b>ms</b> value.", "warp")
						),
						array(
							"type"			=> "dropdown",
							"heading"		=> __( "Auto Play", 'warp' ),
							"param_name"	=> "autoplay",
							"value"			=> array(
								'Yes' 	=> 'true',
								'No' => 'false',
							),
							'description' => __( 'Show or hide auto play from here.', 'warp' ),
							"std"         => "true",
							"group"	=> __( 'Carousel Settings', "warp")
						),
						array(
							"type"			=> "dropdown",
							"heading"		=> __( "Hover Pause", 'warp' ),
							"param_name"	=> "hoverpause",
							"value"			=> array(
								'Yes' 	=> 'true',
								'No' => 'false',
							),
							'description' => __( 'Set yes or no hover pause from here.', 'warp' ),
							"std"         => "false",
							"group"	=> __( 'Carousel Settings', "warp")
						),
						array(
							"type"			=> "dropdown",
							"heading"		=> __( "Loop", 'warp' ),
							"param_name"	=> "loop",
							"value"			=> array(
								'Yes' 	=> 'true',
								'No' => 'false',
							),
							'description' => __('Set yes or no loop from here.', 'warp' ),
							"std"         => "true",
							"group"	=> __( 'Carousel Settings', "warp")
						),
						array(
					   		"type" => "dropdown",
							"heading" => __("Large View", "warp"),
							"param_name" => "large",
							"value" => array(
								__('1', 'warp') => 1,
								__('2', 'warp') => 2,
								__('3', 'warp') => 3,
								__('4', 'warp') => 4,
								__('5', 'warp') => 5,
								__('6', 'warp') => 6
							),
							"std"         => 5,
							"group"	=> __( 'Responsive', "warp"),
							"description" => __("Large view item of the event carousel.", "warp")
						),
						array(
					   		"type" => "dropdown",
							"heading" => __("Medium View", "warp"),
							"param_name" => "medium",
							"value" => array(
								__('1', 'warp') => 1,
								__('2', 'warp') => 2,
								__('3', 'warp') => 3,
								__('4', 'warp') => 4
							),
							"std"         => 3,
							"group"	=> __( 'Responsive', "warp"),
							"description" => __("Medium view item of the event carousel.", "warp")
						),
						array(
					   		"type" => "dropdown",
							"heading" => __("Small View", "warp"),
							"param_name" => "small",
							"value" => array(
								__('1', 'warp') => 1,
								__('2', 'warp') => 2,
								__('3', 'warp') => 3
							),
							"std"         => 1,
							"group"	=> __( 'Responsive', "warp"),
							"description" => __("Small view item of the event carousel.", "warp")
						),
					),	
					'js_view' => 'VcColumnView',
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_custom_carousel_vc' );


		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		    class WPBakeryShortCode_bdt_custom_carousel extends WPBakeryShortCodesContainer {
		    }
		}
	}