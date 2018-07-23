<?php
	if (function_exists('bdt_gallery_function')) {
		function bdthemes_gallery_vc() {
			vc_map( array(
				"name"					=> __( "Gallery", 'warp' ),
				"description"			=> __( "Show Photo Gallery", 'warp' ),
				"base"					=> "gallery",
				'category'				=> "Theme Addons",
				"icon"					=> "vc-gallery",
				"params"				=> array(
					array(
						"type"			=> "attach_images",
						"admin_label"	=> true,
						"class"			=> "",
						"heading"		=> __( "Gallery Images", 'warp' ),
						"param_name"	=> "ids",
						"value"			=> "",
						"description"	=> __( "Upload your Images here.", 'warp' ),
					),

					array(
						"type"			=> "dropdown",
						"admin_label"	=> false,
						"class"			=> "",
						"heading"		=> __( "Thumbnail Size", 'warp' ),
						"param_name"	=> "size",
						"value"			=> array(
							'Thumbnail' => 'thumbnail',
							'Medium'    => 'medium',
							'Large'     => 'large',
							'Full'      => 'full'
						),
					),
					array(
						"type"			=> "dropdown",
						"admin_label"	=> false,
						"class"			=> "",
						"heading"		=> __( "Link To", 'warp' ),
						"param_name"	=> "link",
						"value"			=> array(
							'Lightbox Image' => 'file',
							'None' => 'none',
						),
					),
					array(
						"type"			=> "dropdown",
						"admin_label"	=> false,
						"class"			=> "",
						"heading"		=> __( "Columns", 'warp' ),
						"param_name"	=> "columns",
						"value"			=> array(
							"1" => "1",
						    "2" => "2",
					  		"3" => "3",
					  		"4" => "4",
					  		"5" => "5",
					  		"6" => "6"
						),
						"std"         => "3",
					),
					array(
						"type"			=> "dropdown",
						"heading"		=> __( "Gutter", 'warp' ),
						"param_name"	=> "gutter",
						"value"			=> array(
							"No Gutter" => "0",
							"5"         => "5",
							"10"        => "10",
							"15"        => "15",
							"20"        => "20",
							"25"        => "25",
							"35"        => "35",
							"45"        => "45",
							"50"        => "50"
						),
						"std"         => "10",
						"description"	=> __( "Select gutter of the gallery item.", 'warp' )
					),
				)
			) );
		}
		add_action( 'vc_before_init', 'bdthemes_gallery_vc' );
	}