<?php

if (function_exists('bdthemes_counter_shortcode')) {		
	function bdthemes_counter_vc() {
		vc_map(
			array(
				"name"        => __("Counter","warp"),
				"base"        => "bdt_counter",
				"class"       => "vc_counter",
				"icon"        => "vc-counter",
				"category"    => "Theme Addons",
				"description" => __("Add animated counting number","warp"),
				"params"      => array(
					array(
				   		"type" => "textfield",
						"heading" => __("Count Start", "warp"),
						"param_name" => "count_start",
						"value" => "0", 
						"description" => __("Enter the number that is minimum number of the counter where from counter will start counting", "warp"),
					),
					array(
				   		"type" => "textfield",
						"heading" => __("Count End", "warp"),
						"param_name" => "count_end",
						"value" => "5000", 
						"description" => __("Enter the number that is maximum number of the counter where to counter will finish counting", "warp"),
					),
					array(
				   		"type" => "textfield",
						"heading" => __("Count Speed", "warp"),
						"param_name" => "counter_speed",
						"value" => "5", 
						"description" => __("Counting will finish in specified time (in second)", "warp"),
					),
					array(
				   		"type" => "textfield",
						"heading" => __("Prefix Text", "warp"),
						"param_name" => "prefix",
						"value" => "", 
						"description" => __("You can add text before the count number. For example: $ sign", "warp"),
					),
					array(
				   		"type" => "textfield",
						"heading" => __("Suffix Text", "warp"),
						"param_name" => "suffix",
						"value" => "", 
						"description" => __("You can add text after the count number. For example: /-", "warp"),
					),
					array(
						"type"       => "checkbox",
						"class"      => "",
						"heading"    => __("Separator", "warp"),
						"param_name" => "separator",
						"value"      => array( __("Yes","warp") => "yes" ),
						"description" => __("You can separate count text by comma(,) if you select yes.For example: 1,500", "warp"),
					),
					array(
						"type"       => "checkbox",
						"class"      => "",
						"heading"    => __("Add Icon", "warp"),
						"param_name" => "add_icon",
						"value"      => array( __("Yes","warp") => "yes" ),
						"description" => __("If you want to add icon with counter, just check it.", "warp"),
					),
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Icon', 'warp' ),
						'param_name' => 'icon',
						'value'      => '', // default value to backend editor admin_label
						'settings' 	 => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 500,
						),
						"group" => "Icon",
						'description'      => __( 'Select icon from library.', 'warp' ),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
				   		"type" => "dropdown",
						"heading" => __("Icon Align", "warp"),
						"param_name" => "align",
						"value" => array(
							"Top"=>'top',
							"Left"=>"left",
							"Right"=>"right",
						),
						"group" => "Icon",
						"description" => __("You can set alignment from here.", "warp"),
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
				   		"type" => "colorpicker",
						"heading" => __("Icon Color", "warp"),
						"param_name" => "icon_color",
						"value" => "",
						"description" => __("Text color for time ticks Period.", "warp"),
						"group" => "Icon",
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon  Size', 'warp' ),
						'param_name' => 'icon_size',
						'value' => "24px",
						'description' => __( 'Select your object size. Its will be set in pixel value', 'warp' ),
						"group" => "Icon",
						'dependency'       => array(
							'element' => 'add_icon',
							'value'   => 'yes',
						),
					),
					array(
				   		"type" => "colorpicker",
						"heading" => __("Count Color", "warp"),
						"param_name" => "count_color",
						"value" => "#444444",
						"group" => "Style",
						"description" => __("Text color for time ticks Period.", "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Count  Size', 'warp' ),
						'param_name' => 'count_size',
						'value' => "32px",
						"group" => "Style",
						'description' => __( 'Select your object size. Its will be set in pixel value', 'warp' ),
					),
					array(
				   		"type" => "colorpicker",
						"heading" => __("Text Color", "warp"),
						"param_name" => "text_color",
						"value" => "#666666",
						"group" => "Style",
						"description" => __("Text color for time ticks Period.", "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text  Size', 'warp' ),
						'param_name' => 'text_size',
						'value' => "14px",
						"group" => "Style",
						'description' => __( 'Select your object size. Its will be set in pixel value', 'warp' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'warp' ),
						'param_name' => 'background',
						'value' => 'transparent',
						"group" => "Style",
						'description' => __( 'Select background color.', 'warp' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'warp' ),
						'param_name' => 'padding',
						'value' => '15px',
						"group" => "Style",
						'description' => __( 'Select counter padding from here. support <b>px, em</b> value.', 'warp' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'warp' ),
						'param_name' => 'border',
						'value' => '0px solid #cccccc',
						"group" => "Style",
						'description' => __( 'You can set content border from here.', 'warp' ),
					),
					// Add some description
					array(
						"type" => "textarea_html",
						"heading" => __("Description", "warp"),
						"param_name" => "content",
						"value" => "",
						"description" => __("Provide the description for this icon box.", "warp"),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'warp' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'warp' ),
					)
				)	
			)
		);
	}
	add_action( 'vc_before_init', 'bdthemes_counter_vc' );
}	