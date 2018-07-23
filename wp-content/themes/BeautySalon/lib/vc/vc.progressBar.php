<?php
	if (function_exists('bdthemes_progress_bar')) {
		function bdthemes_progress_bar_vc() {
			vc_map( array(
				"name"        => __( "Progress Bar", 'warp' ),
				"description" => __( "Setting of Yes can cause for show divider", 'warp' ),
				"base"        => "bdt_progress_bar",
				"icon"        => "vc-progress-bar",
				'category'    => "Theme Addons",
				"params"      => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Progress Bar Text', 'warp' ),
						'value' => "HTML",
						'param_name' => 'text',
						'description' => __( 'Type your progress bar text to this input box.', 'warp' )
					),
					array(
						'type' => 'number',
						'heading' => __( 'Percent', 'warp' ),
						'param_name' => 'percent',
						'value' => 75,
						'description' => __( 'Percentage of the progress bar.', 'warp' )
					),
					array(
						"type"			=> "checkbox",
						"heading"		=> __( "Show Percent", 'warp' ),
						"param_name"	=> "show_percent",
						"value" => array( __( "Yes", 'warp' ) => "yes" ),
						"description"	=> __( "You can show or hide percent from here.", 'warp' )
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Animation", "warp"),
						"param_name" => "animation",
						'value'      => array(
							'linear'           => 'linear',
							'swing'            => 'swing',
							'jswing'           => 'jswing',
							'easeInQuad'       => 'easeInQuad',
							'easeInCubic'      => 'easeInCubic',
							'easeInQuart'      => 'easeInQuart',
							'easeInQuint'      => 'easeInQuint',
							'easeInSine'       => 'easeInSine',
							'easeInExpo'       => 'easeInExpo',
							'easeInCirc'       => 'easeInCirc',
							'easeInElastic'    => 'easeInElastic',
							'easeInBack'       => 'easeInBack',
							'easeInBounce'     => 'easeInBounce',
							'easeOutQuad'      => 'easeOutQuad',
							'easeOutCubic'     => 'easeOutCubic',
							'easeOutQuart'     => 'easeOutQuart',
							'easeOutQuint'     => 'easeOutQuint',
							'easeOutSine'      => 'easeOutSine',
							'easeOutExpo'      => 'easeOutExpo',
							'easeOutCirc'      => 'easeOutCirc',
							'easeOutElastic'   => 'easeOutElastic',
							'easeOutBack'      => 'easeOutBack',
							'easeOutBounce'    => 'easeOutBounce',
							'easeInOutQuad'    => 'easeInOutQuad',
							'easeInOutCubic'   => 'easeInOutCubic',
							'easeInOutQuart'   => 'easeInOutQuart',
							'easeInOutQuint'   => 'easeInOutQuint',
							'easeInOutSine'    => 'easeInOutSine',
							'easeInOutExpo'    => 'easeInOutExpo',
							'easeInOutCirc'    => 'easeInOutCirc',
							'easeInOutElastic' => 'easeInOutElastic',
							'easeInOutBack'    => 'easeInOutBack',
							'easeInOutBounce'  => 'easeInOutBounce'
		                ),
						"description" => __("Select animation of the progress bar.", "warp"),
					),
					array(
						'type' => 'number',
						'heading' => __( 'Duration', 'warp' ),
						'param_name' => 'duration',
						'value' => 1.5,
						'description' => __( 'You can set animation duration as (seconds) units from here.', 'warp' )
					),
					array(
						'type' => 'number',
						'heading' => __( 'Delay', 'warp' ),
						'param_name' => 'delay',
						'value' => 0.3,
						'description' => __( 'After mentioned time (in second) animation will start.', 'warp' )
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Styles", "warp"),
						"param_name" => "style",
						'value'      => array(
							'Default'   => '1',
							'Fancy'     => '2',
							'Thin'      => '3',
							'Striped'   => '4',
							'Animation' => '5'
		                ),
						"description" => __("Select style of the progress bar.", "warp"),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Text Color', 'warp' ),
						'param_name' => 'text_color',
						'description' => __( 'This color will be applied to the text.', 'warp' ),
						"group"	=> __( 'Styles', "warp")
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Bar Color', 'warp' ),
						'param_name' => 'bar_color',
						'description' => __( 'You can set progress bar background color from here.', 'warp' ),
						"group"	=> __( 'Styles', "warp")
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Fill Color', 'warp' ),
						'param_name' => 'fill_color',
						'description' => __( 'Select progress bar fill color, if you need it transparent color.', 'warp' ),
						"group"	=> __( 'Styles', "warp")
					)
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_progress_bar_vc' );
	}