<?php
	if (function_exists('bdthemes_team_member_shortcode')) {
		function bdthemes_team_member_vc() {
			vc_map(
				array(
					"name"        => __("Team Member","warp"),
					"base"        => "bdt_team_member",
					"icon"        => "vc-team-member",
					"category"    => "Theme Addons",
					"description" => __("Team Member.","warp"),
					"params"      => array(

						array(
							'type'        => 'attach_image',
							'heading'     => __( 'Photo', 'warp' ),
							'param_name'  => 'photo',
							'value'       => '',
							'description' => __( 'Select image from media library.', 'warp' ),
						),
				   		array(
							"type"       => "dropdown",
							"class"      => "",
							"heading"    => __("Team Member Style", "warp"),
							"param_name" => "style",
		                    'value' 	 => array(
		                        __('Style 1', 'warp') => '1',
		                        __('Style 2', 'warp') => '2',
		                        __('Style 3', 'warp') => '3',
		                        __('Style 4', 'warp') => '4'
		                    ),
							'group'       => __( 'Style', 'warp' ),
							"description" => __("Select style for Team Member.", "warp"),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Background', 'warp' ),
							'param_name'  => 'background',
							'value'       => '#FFFFFF',
							'group'       => __( 'Style', 'warp' ),
							'description' => __( 'Select custom background color.', 'warp' )
						),	
						array(
							'type'        => 'colorpicker',
							'heading'     => __( 'Color', 'warp' ),
							'param_name'  => 'color',
							'value'       => '#222222',
							'group'       => __( 'Style', 'warp' ),
							'description' => __( 'Select custom color.', 'warp' )
						),					
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Border', 'warp' ),
							'param_name'  => 'border',
							'value'       => '',
							'group'       => __( 'Style', 'warp' ),
							'description' => __( 'You can set content border from here.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Border Radius', 'warp' ),
							'param_name'  => 'radius',
							'group'       => __( 'Style', 'warp' ),
							'description' => __( 'You can set member border radius from here. This radius value will be only pixel (px) units.', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Shadow', 'warp' ),
							'param_name'  => 'shadow',
							'group'       => __( 'Style', 'warp' ),
							'description' => __( 'You can set member box-shadow radius from here.', 'warp' ),
						),
				   		array(
							"type"               => "dropdown",
							"class"              => "",
							"heading"            => __("Align", "warp"),
							"param_name"         => "text_align",
							'value'              => array(
								__('Center', 'warp') => 'center',
								__('Left', 'warp')   => 'left',
								__('Right', 'warp')  => 'right',
		                    ),
							'group'              => __( 'Style', 'warp' ),
							"description"        => __("You can set alignment from here.", "warp"),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Name', 'warp' ),
							'value'       => 'John Due',
							'param_name'  => 'name',
							'description' => __( 'Type name here that you want to show for title', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Role', 'warp' ),
							'param_name'  => 'role',
							'description' => __( 'Member role', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Facebook URL', 'warp' ),
							'param_name'  => 'facebook_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Twitter URL', 'warp' ),
							'param_name'  => 'twitter_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Google-Plus URL', 'warp' ),
							'param_name'  => 'googleplus_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Pinterest URL', 'warp' ),
							'param_name'  => 'pinterest_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Github URL', 'warp' ),
							'param_name'  => 'github_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Linkedin URL', 'warp' ),
							'param_name'  => 'linkedin_url',
							'group'       => __( 'Social Share', 'warp' ),
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'URL', 'warp' ),
							'param_name'  => 'url',
							'description' => __( 'URL/Link of the author. Leave empty to disable link', 'warp' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Extra class name', 'warp' ),
							'param_name'  => 'el_class',
							'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'warp' ),
						),
						array(
							'type'        => 'textarea_html',
							'holder'      => 'div',
							'heading'     => __( 'Text', 'warp' ),
							'param_name'  => 'content',
							'value'       => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'warp' ),
						)
					)	
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_team_member_vc' );
	}