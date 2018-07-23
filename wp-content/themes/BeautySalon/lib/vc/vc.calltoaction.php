<?php

if (function_exists('bdthemes_calltoaction')) {
	function bdthemes_calltoaction_vc() {
		vc_map( array(
			"name"        => __( "Call to Action", 'warp' ),
			"description" => __( "Make your call to action easily.", 'warp' ),
			"base"        => "bdt_calltoaction",
			"icon"        => "vc-call-to-action",
			'category'    => "Theme Addons",
			"params"      => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'warp' ),
					'param_name' => 'title',
					'description' => __( 'Title of the call to aciton.', 'warp' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Button Text', 'warp' ),
					'param_name' => 'button_text',
					'description' => __( 'Button text of the call to aciton.', 'warp' )
				),
				array(
					'type' => 'textarea_html',
					'heading' => __( 'Calltoaction Content', 'warp' ),
					'value' => "And it has huge awesome features, unlimited colors, advanced template admin options and so much more!",
					'param_name' => 'content'
				),
				array(
					"type"       => "dropdown",
					"heading"    => __("Align", "warp"),
					"param_name" => "align",
					'value'      => array(
						'Left'           => 'left',
						'Right'           => 'right',
						'Center'           => 'center'
	                ),
					"description" => __("Select alignment of the calltoaction.", "warp")
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Button Link', 'warp' ),
					'value' => '#',
					'param_name' => 'button_link',
					'description' => __( 'You can type here any hyperlink to make link in button.', 'warp' )
				),
				array(
					"type"       => "dropdown",
					"heading"    => __("Target", "warp"),
					"param_name" => "target",
					'value'      => array(
						'Self'   => 'self',
						'Blank'  => 'blank'
	                ),
					"description" => __("Set link target self or blank.", "warp")
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Background', 'warp' ),
					'param_name' => 'background',
					'description' => __( 'Select your call to action background color.', 'warp' ),
					"group"	=> __( 'Styles', "warp")
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Border Color', 'warp' ),
					'param_name' => 'border_color',
					'description' => __( 'Select your call to action border color.', 'warp' ),
					"group"	=> __( 'Styles', "warp")
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Button Radius', 'warp' ),
					'param_name' => 'button_radius',
					'description' => __( 'You can set button border radius from here.', 'warp' ),
					"group"	=> __( 'Styles', "warp")
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'bdthemes_calltoaction_vc' );
}