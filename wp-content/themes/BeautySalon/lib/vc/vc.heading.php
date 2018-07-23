<?php
	if (function_exists('bdthemes_heading')) {
		function bdthemes_heading_vc() {
			vc_map( array(
				"name"        => __( "Heading", 'warp' ),
				"description" => __( "Huge heading collection.", 'warp' ),
				"base"        => "bdt_heading",
				"icon"        => "vc-heading",
				'category'    => "Theme Addons",
				"params"      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Heading Text', 'warp' ),
						"admin_label" => true,
						'value'       => "This is a heading",
						'param_name'  => 'content',
						'description' => __( 'Type your heading text here.', 'warp' )
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Heading Tag", "warp"),
						"param_name" => "heading",
						'value'      => array(
							'H1'   => 'h1',
							'H2'   => 'h2',
							'H3'   => 'h3',
							'H4'   => 'h4',
							'H5'   => 'h5',
							'H6'   => 'h6'
		                ),
						"description" => __("Select heading tag from here.", "warp"),
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Heading Style", "warp"),
						"param_name" => "style",
						'value'      => array(
							'Default'   => 'default',
							'Style 1'   => '1',
							'Style 2'   => '2',
							'Style 3'   => '3',
							'Style 4'   => '4',
							'Style 5'   => '5',
							'Style 6'   => '6',
							'Style 7'   => '7',
							'Style 8'   => '8',
							'Style 9'   => '9',
							'Style 10'   => '10'
		                ),
						"description" => __("Select heading style from here.", "warp"),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Heading Align", "warp"),
						"param_name" => 'align',
						'value'      => array(
							'Center'   => 'center',
							'Left'   => 'left',
							'Right'   => 'right'
		                ),
						"description" => __("Select heading align from here.", "warp"),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Heading Color', 'warp' ),
						'param_name' => 'color',
						'description' => __( 'Color of the heading text.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'number',
						'heading' => __( 'Heading Width', 'warp' ),
						'param_name' => 'width',
						'description' => __( 'Set heading width from here.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'number',
						'heading' => __( 'Heading Size', 'warp' ),
						'param_name' => 'size',
						'value' => 24,
						'description' => __( 'Set heading size from here.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Bottom', 'warp' ),
						'param_name' => 'margin',
						'description' => __( 'Margin bottom of the hading.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_heading_vc' );
	}