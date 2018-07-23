<?php
	if (function_exists('bdthemes_progress_pie')) {
		function bdthemes_progress_pie_vc() {
			vc_map( array(
				"name"        => __( "Progress Pie", 'warp' ),
				"description" => __( "Customizable progress pie", 'warp' ),
				"base"        => "bdt_progress_pie",
				"icon"        => "vc-progress-pie",
				'category'    => "Theme Addons",
				"params"      => array(
					array(
						'type'        => 'number',
						'heading'     => __( 'Percent', 'warp' ),
						'param_name'  => 'percent',
						'value'       => 75,
						'description' => __( 'Specify percentage value.', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Before Text', 'warp' ),
						'param_name'  => 'before',
						'description' => __( 'This content will be shown before the percent.', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Middle Text', 'warp' ),
						'param_name'  => 'text',
						'description' => __( 'You can show custom text. Leave this field empty to show the percentage value.', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'After Text', 'warp' ),
						'param_name'  => 'after',
						'description' => __( 'This content will be shown after the percent.', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Progress Pie Title', 'warp' ),
						'param_name'  => 'after_title',
						'value'       => 'Pie Title',
						'description' => __( 'This content will be shown as progress pie title.', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Text Size', 'warp' ),
						'param_name'  => 'text_size',
						'description' => __( 'Select your text size (pixel)', 'warp' )
					),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Text Color', 'warp' ),
							'param_name'  => 'text_color',
							'value'       => '#444444',
							'group'       => 'Style',
							'description' => __( 'You can select text color from here.', 'warp' )
						),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Border', 'warp' ),
						'param_name'  => 'border',
						'value'       => '1px solid rgba(0,0,0,.05)',
						'group'       => 'Styles',
						'description' => __( 'Enter border value for progress pie', 'warp' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Padding', 'warp' ),
						'param_name'  => 'padding',
						'value'       => '47px',
						'group'       => 'Styles',
						'description' => __( 'Enter padding value for progress pie', 'warp' )
					),
					array(
						'type'        => 'number',
						'heading'     => __( 'Duration', 'warp' ),
						'param_name'  => 'duration',
						'value'       => 1,
						'description' => __( 'You can set animation duration as (seconds) units from here.', 'warp' )
					),
					array(
						'type'        => 'number',
						'heading'     => __( 'Delay', 'warp' ),
						'param_name'  => 'delay',
						'value'       => 1,
						'description' => __( 'After mentioned time (in second) animation will start.', 'warp' )
					),
					array(
						'type'        => 'number',
						'heading'     => __( 'Line Width', 'warp' ),
						'param_name'  => 'line_width',
						'value'       => 8,
						'description' => __( 'Set your pie width from here.', 'warp' )
					),
					array(
						"type"        => "dropdown",
						"heading"     => __("Line Cap", "warp"),
						"param_name"  => "line_cap",
						'value'       => array(
							'Round'       => 'round',
							'Square'      => 'square',
							'Butt'        => 'butt'
		                ),
						"description" => __("Set your line edge cap style from here.", "warp"),
						"group"	      => __( 'Styles', "warp"),
					)
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_progress_pie_vc' );
	}