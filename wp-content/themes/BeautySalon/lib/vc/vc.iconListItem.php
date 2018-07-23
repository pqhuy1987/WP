<?php
	if (function_exists('bdthemes_icon_list_item_shortcode')) {
		function bdthemes_icon_list_item_vc() {
			vc_map(
				array(
					"name"        => __("Icon List Item", "warp"),
					"base"        => "bdt_icon_list_item",
					"category"    => "Theme Addons",
					"icon"        => "vc-icon-list",
					"description" => __("Adds icon box with custom font icon","warp"),
					"params"      => array(
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Title', 'warp' ),
							'param_name'  => 'title',
							'value'       => __( 'Unique Design', 'warp' ),
							'description' => __( 'Enter text here that you want to show for title', 'warp' ),
						),
						array(
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'warp' ),
							'param_name'   => 'icon',
							'value'        => 'fa fa-eye',
							'settings'     => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 500,
							),
							'group'       => 'Icon',
							'description'  => __( 'Select icon from library.', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Title Color', 'warp' ),
							'param_name'  => 'title_color',
							'value'       => '#666666',
							'group'       => 'Style',
							'description' => __( 'You can select title color from here.', 'warp' )
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Title  Size', 'warp' ),
							'param_name'  => 'title_size',
							'value'       => '18px',
							'group'       => 'Style',
							'description' => __( 'You can change title size from here.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Icon  Size', 'warp' ),
							'param_name'  => 'icon_size',
							'value'       => 32,
							'group'       => 'Icon',
							'description' => __( 'Select your object size. Its will be set in pixel value', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Text Color', 'warp' ),
							'param_name'  => 'color',
							'value'       => '#444444',
							'group'       => 'Style',
							'description' => __( 'Select custom icon color.', 'warp' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Icon color', 'warp' ),
							'param_name'  => 'icon_color',
							'value'       => '#444444',
							'group'       => 'Icon',
							'description' => __( 'Select custom icon color.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Link Text', 'warp' ),
							'param_name'  => 'link_text',
							'description' => __( 'Link Text', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'URL', 'warp' ),
							'param_name'  => 'url',
							'description' => __( 'You must fill this field to show link. Leave empty to disable link.', 'warp' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Link Target', 'warp' ),
							'param_name'  => 'target',
							'description' => __( 'Select where to open  custom links.', 'warp' ),
							'value'       => array(
								__( 'Same window', 'warp' ) => '_self',
								__( 'New window', 'warp' )  => '_blank',
							),		
						),
						// Add some description
						array(
							"type"        => "textarea_html",
							"class"       => "",
							"heading"     => __("Description", "warp"),
							"param_name"  => "content",
							"value"       => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.",
							"description" => __("Provide the description for this icon box.", "warp"),
						),
					)
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_icon_list_item_vc' );
	}