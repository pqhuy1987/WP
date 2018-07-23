<?php
	if (function_exists('bdthemes_post_carousel')) {
		function bdthemes_post_carousel_vc() {
			vc_map( array(
				"name"					=> __( "Posts Carousel", 'warp' ),
				"description"			=> __( "Add recent post in your carousel", 'warp' ),
				"base"					=> "bdt_post_carousel",
				"icon"					=> "vc-post-carousel",
				'category'				=> "Theme Addons",
				"params"				=> array(
					array(
						"type"			=> "textfield",
						"admin_label"	=> false,
						"class"			=> "",
						"heading"		=> __( "Number of Posts", 'warp' ),
						"param_name"	=> "posts",
						"value"			=> "6",
						"description"	=> __( "Number of Posts.", 'warp' )
					),
					array(
						"type"			=> "textfield",
						"admin_label"	=> true,
						"class"			=> "",
						"heading"		=> __( "Categories", 'warp' ),
						"param_name"	=> "categories",
						"value"			=> "all",
						"description"	=> __( "Category Slugs - For example: sports, business, all", 'warp' )
					),
					array(
						"type"			=> "dropdown",
						"admin_label"	=> false,
						"class"			=> "",
						"heading"		=> __( "Style", 'warp' ),
						"param_name"	=> "style",
						"value"			=> array(
							'White' 	=> 'white',
							'Lightgrey' => 'grey',
						)
					),
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
						"std"         => 3,
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
						"std"         => 2,
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
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_post_carousel_vc' );
	}