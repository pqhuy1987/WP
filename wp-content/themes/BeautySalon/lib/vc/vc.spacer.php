<?php
	if (function_exists('bdthemes_spacer_shortcode')) {
		function bdthemes_spacer_vc() {
			vc_map( 
				array(
					"name"					=> __( "Spacer", 'warp' ),
					"description"			=> __( "Empty space with adjust table height", 'warp' ),
					"base"					=> "bdt_spacer",
					"icon"					=> "vc-spacer",
					'category'				=> "Theme Addons",
					"params"				=> array(
						array(
							"type"			=> "textfield",
							"heading"		=> __( "Spacer Size", 'warp' ),
							"param_name"	=> "size",
							"value"			=> "20",
							"description"	=> __( "Set your spacer height. it's set px value", 'warp' )
						)
					)
				)
		 	);
		}
		add_action( 'vc_before_init', 'bdthemes_spacer_vc' );
	}