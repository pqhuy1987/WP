<?php
	if (function_exists('bdthemes_note_shortcode')) {
		function bdthemes_note_vc() {
			vc_map( array(
				"name"					=> __( "Note", 'warp' ),
				"description"			=> __( "Superb! various note style", 'warp' ),
				"base"					=> "bdt_note",
				"icon"					=> "vc-note",
				'category'				=> "Theme Addons",
				"params"				=> array(
					array(
						"type"			=> "dropdown",
						"heading"		=> __( "Style", 'warp' ),
						"param_name"	=> "style",
						"value"			=> array(
							__( "Style 1", 'warp' )	 => "1",
							__( "Style 2", 'warp' )  => "2",
							__( "Style 3", 'warp' )  => "3",
							__( "Style 4", 'warp' )  => "4",
							__( "Style 5", 'warp' )  => "5",
							__( "Style 6", 'warp' )  => "6",
						),
						"description"	=> __( "You can set four attractive note style. You can set any style with any type", 'warp' )
					),
					array(
						"type"			=> "dropdown",
						"heading"		=> __( "Type", 'warp' ),
						"param_name"	=> "type",
						"value"			=> array(
							__( "Info", "warp" ) 	=> "info",
							__( "Success", "warp" ) => "success",
							__( "Warning", "warp" ) => "warning",
							__( "Danger", "warp" )  => "danger",
						),
						"description"	=> __( "You can set any note type into four note type. Please! select as you need.", 'warp' )
					),
					array(
						"type"			=> "checkbox",
						"heading"		=> __( "Icon", 'warp' ),
						"param_name"	=> "icon",
						"value" => array( __( "Yes", 'warp' ) => "yes" ),
						"description"	=> __( "If you want to show note icon then please select yes. default value is no", 'warp' )
					),
					array(
						"type"			=> "textfield",
						"heading"		=> __( "Radius", 'warp' ),
						"param_name"	=> "radius",
						"value"			=> "3px",
						"description"	=> __( "You can set border radius from here, for example: 3px 10px 25px also you can set value as em, % etc if you need", 'warp' )
					),
					array(
						'type'       => 'textarea_html',
						'holder'     => 'div',
						'heading'    => __( 'Text', 'warp' ),
						'param_name' => 'content',
						'value'      => __( "<p>Heads up! This alert needs your attention, but it's not super important.</p>", 'warp' ),
					)
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_note_vc' );
	}