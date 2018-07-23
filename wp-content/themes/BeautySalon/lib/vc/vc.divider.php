<?php
	if (function_exists('bdthemes_divider')) {
		function bdthemes_divider_vc() {
			vc_map( array(
				"name"        => __( "Divider", 'warp' ),
				"description" => __( "Setting of Yes can cause for show divider", 'warp' ),
				"base"        => "bdt_divider",
				"icon"        => "vc-divider",
				'category'    => "Theme Addons",
				"params"      => array(
			   		array(
						"type"       => "dropdown",
						"heading"    => __("Divider Align", "warp"),
						"param_name" => "align",
		                'value'  => array(
		                    'center'  => 'center',
		                    'left'    => 'left',
		                    'right'   => 'right',
		                ),
						"description" => __("Select divider alignment here.", "warp"),
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Icon Align", "warp"),
						"param_name" => "icon_align",
		                'value'  => array(
		                    'center'  => 'center',
		                    'left'    => 'left',
		                    'right'   => 'right',
		                ),
						"description" => __("You can set icon alignment from here.", "warp"),
					),
					array(
						"type"			=> "checkbox",
						"heading"		=> __( "Show Top Link", 'warp' ),
						"param_name"	=> "top",
						"description"	=> __( "Set show or hide top link here.", 'warp' )
					),
					array(
						"type"			=> "checkbox",
						"heading"		=> __( "Force Fullwidth", 'warp' ),
						"param_name"	=> "force_fullwidth",
						"description"	=> __( "Force fullwidht of the divider.", 'warp' )
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Divider Style", "warp"),
						"param_name" => "style",
						'value'      => array(
							'Single Line'   => '1',
							'Double Line'   => '2',
							'Single Dashed' => '3',
							'Double Dashed' => '4',
							'Single Dotted' => '5',
							'Double Dotted' => '6',
							'Striped'       => '7',
		                ),
						"description" => __("Select divider style from here.", "warp"),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Style Color', 'warp' ),
						'param_name' => 'color',
						'value' => '#c5c5c5',
						'description' => __( 'Set divider style color.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						"type"       => "checkbox",
						"class"      => "",
						"heading"    => __("Add Divider Icon", "warp"),
						"param_name" => "add_icon",
						"value"      => array( __("Yes","warp") => "yes" ),
						"group"	=> __( 'Styles', "warp"),
						"description" => __("Add icon of the divider, just check it.", "warp"),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Divider Icon', 'warp' ),
						'param_name' => 'icon',
						'settings' => array(
							'iconsPerPage' => 100, // default 100, how many icons per/page to display
						),
						'description' => __( 'Click on the icon picker to pick an icons for this shortcode.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
						"type"       => "dropdown",
						"heading"    => __("Icon Style", "warp"),
						"param_name" => "icon_style",
		                'value'  => array(
							'Default'    => '1',
							'Background' => '2',
							'Border'     => '3'
		                ),
						"description" => __("Set icon style from here.", "warp"),
						"group"	=> __( 'Styles', "warp"),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Color', 'warp' ),
						'param_name' => 'icon_color',
						'value' => '#c5c5c5',
						'description' => __( 'Set icon color from here.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Size', 'warp' ),
						'param_name' => 'icon_size',
						'value' => 16,
						'description' => __( 'Select your icon size. It will be set in pixel value', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
						'type' => 'number',
						'heading' => __( 'Divider Width', 'warp' ),
						'param_name' => 'width',
						'value' => 100,
						'description' => __( 'Set divider width from here.', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Top', 'warp' ),
						'param_name' => 'margin_top',
						'value' => '10px',
						'description' => __( 'Top margin of the divider', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Bottom', 'warp' ),
						'param_name' => 'margin_bottom',
						'value' => '10px',
						'description' => __( 'Bottom margin of the divider', 'warp' ),
						"group"	=> __( 'Styles', "warp"),
					),
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_divider_vc' );
	}