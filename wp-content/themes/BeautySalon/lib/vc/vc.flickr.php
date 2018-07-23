<?php
	if (function_exists('bdthemes_flickr')) {
		function bdthemes_flickr_vc() {
			vc_map(
				array(
					"name"        => __("Flickr","warp"),
					"base"        => "bdt_flickr",
					"icon"        => "vc-flickr",
					"category"    => "Theme Addons",
					"description" => __("Flickr is for make flickr feed .","warp"),
					"params"      => array(
						array(
					   		"type" => "textfield",
							"heading" => __("Flickr ID", "warp"),
		                    'param_name' => 'id',
							"value" => "95572727@N00", 
							"description" => __("Enter your flickr ID, To find your flickID visit <a href='http://idgettr.com/' target='_blank'>idGettr</a>", "warp")
						),
		                array(
		                    'type' => 'dropdown',
		                    'heading' => __( 'Limit', 'warp' ),
		                    'param_name' => 'limit',
		                    'value' => array( 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1 ),
		                    'description' => __( 'Select number of photos to display.', 'warp' )
		                ), 
						array(
							'type' => 'checkbox',
							'heading' => __( 'Lightbox', 'warp' ),
							'param_name' => 'lightbox',
							'description' => __( 'If checked row will be set to full height.', 'warp' ),
							'value' => array( __( 'Yes', 'warp' ) => 'yes' )
						),
						array(
					   		"type" => "textfield",
							"heading" => __("Radius", "warp"),
							'param_name' => 'radius',
							"value" => "0px", 
							"description" => __("You can set border radius from here.", "warp")
						),
					)	
				)
			);
		}
		add_action( 'vc_before_init', 'bdthemes_flickr_vc' );
	}