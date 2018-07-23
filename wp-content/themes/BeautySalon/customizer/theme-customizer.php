<?php
/**
 * Load the Customizer with some custom extended addons
 *
 * @package Megastar
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

load_template( get_template_directory() . '/customizer/class-customizer-control.php' );

/**
 * This funtion is only called when the user is actually on the customizer page
 * @param  WP_Customize_Manager $wp_customize
 */
if ( ! function_exists( 'beautysalon_customizer' ) ) {
	function beautysalon_customizer( $wp_customize ) {
		
		// add required files
		load_template( get_template_directory() . '/customizer/class-customizer-base.php' );
		//load_template( get_template_directory() . '/customizer/class-customizer-dynamic-css.php' );

		new beautysalon_Customizer_Base( $wp_customize );
	}
	add_action( 'customize_register', 'beautysalon_customizer' );
}


/**
 * Takes care for the frontend output from the customizer and nothing else
 */
if ( ! function_exists( 'beautysalon_customizer_frontend' ) && ! class_exists( 'beautysalon_Customize_Frontent' ) ) {
	function beautysalon_customizer_frontend() {
		load_template( get_template_directory() . '/customizer/class-customizer-frontend.php' );
		new beautysalon_Customize_Frontent();
	}
	add_action( 'init', 'beautysalon_customizer_frontend' );
}