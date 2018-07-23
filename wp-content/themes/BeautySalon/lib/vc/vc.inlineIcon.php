<?php
	if (function_exists('bdthemes_inline_icon_shortcode')) {
		function bdthemes_inline_icon_vc() {
			vc_map(
				array(
					"name"                    => __("Inline Icon", "warp"),
					"base"                    => "bdt_inline_icon",
					"icon"                    => "vc-icon",
					"category"                => "Theme Addons",
					"description"             => __("Adds icon box with custom font icon","warp"),
					"params" => array(
						array(
							'type' => 'iconpicker',
							'heading' => __( 'Icon', 'warp' ),
							'param_name' => 'icon',
							'value' => 'fa fa-heart',
							'settings' => array(
								'emptyIcon' => false,
								'iconsPerPage' => 500,
							),
							'description' => __( 'Select icon from library.', 'warp' ),
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Icon color', 'warp' ),
							'param_name' => 'color',
							'value' => '#333333',
							'group' => __( 'Style', 'warp' ),
							'description' => __( 'This color will be applied to the selected icon.', 'warp' ),
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Icon Background', 'warp' ),
							'param_name' => 'background',
							'value' => 'transparent',
							'group' => __( 'Style', 'warp' ),
							'description' => __( 'Select icon background color.', 'warp' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Icon Size', 'warp' ),
							'param_name' => 'size',
							'value' => '16',
							'group' => __( 'Style', 'warp' ),
							'description' => __( 'You can set icon size from here. Icon size set only pixel value.', 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Icon Border', 'warp' ),
							'param_name' => 'border',
							'value' => '0px solid #cccccc',
							'group' => __( 'Style', 'warp' ),
							'description' => __( 'You can set content border from here.', 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Icon  Radius', 'warp' ),
							'param_name' => 'radius',
							'value' => '0px',
							'group' => __( 'Style', 'warp' ),
							'description' => __("You can set border radius from here, for example: <b class='su-generator-set-value' title='Click to set this value'>3px</b> <b class='su-generator-set-value' title='Click to set this value'>10px</b> <b class='su-generator-set-value' title='Click to set this value'>25px</b> also you can set value as em, % etc if you need", 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Icon Margin', 'warp' ),
							'param_name' => 'margin',
							'value' => '0px',
							'group' => __( 'Style', 'warp' ),
							'description' => __( 'You can set margin from here.', 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Icon Padding', 'warp' ),
							'param_name' => 'padding',
							'value' => '15px',
							'group' => __( 'Style', 'warp' ),
							'description' => __( "You can set padding from here, for example: <b class='su-generator-set-value' title='Click to set this value'>5px</b> <b class='su-generator-set-value' title='Click to set this value'>10px</b> <b class='su-generator-set-value' title='Click to set this value'>25px</b> also you can set value as em, % etc if you need", 'warp' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'URL', 'warp' ),
							'param_name' => 'url',
							'description' => __( 'URL/Link of the author. Leave empty to disable link.', 'warp' ),
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Link Target', 'warp' ),
							'param_name' => 'target',
							'description' => __( 'Select where to open  custom links.', 'warp' ),
							'value' => array(
								__( 'Same window', 'warp' ) => '_self',
								__( 'New window', 'warp' ) => '_blank',
							),		
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Inline Text', 'warp' ),
							'param_name' => 'inline_text',
							'description' => __( '(optional) if you want show text with icon', 'warp' ),
						)
					) 
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_inline_icon_vc' );
	}