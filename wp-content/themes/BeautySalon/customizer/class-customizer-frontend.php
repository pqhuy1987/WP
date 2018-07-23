<?php
/**
 * Class which handles the output of the WP customizer on the frontend.
 * Meaning that this stuff loads always, no matter if the global $wp_cutomize
 * variable is present or not.
 */
class beautysalon_Customize_Frontent {

	/**
	 * Add actions to load the right staff at the right places (header, footer).
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts' , array( $this, 'customizer_css' ), 20 );
	}

	/**
	* This will output the custom WordPress settings to the live theme's WP head.
	* Used by hook: 'wp_head'
	* @see add_action( 'wp_head' , array( $this, 'head_output' ) );
	*/
	public static function customizer_css() {
		// customizer settings
		$cached_css = get_theme_mod( 'cached_css', '' );

		ob_start();

		echo '/* WP Customizer start */' . PHP_EOL;
		echo apply_filters( 'beautysalon/cached_css', $cached_css );
		echo '/* WP Customizer end */';

		wp_add_inline_style( 'beautysalon-style', ob_get_clean() );
	}

}