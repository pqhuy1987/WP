<?php

if ( function_exists( 'vc_map' ) ) {
	vc_add_shortcode_param( 'number', 'bdt_number_settings_field' );
	function bdt_number_settings_field( $settings, $value ) {
	   return '<div class="bdt_number_block">'
	             .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
	             esc_attr( $settings['param_name'] ) . ' ' .
	             esc_attr( $settings['type'] ) . '_field" type="number" value="' . esc_attr( $value ) . '" />' .
	             '</div>'; // This is html markup that will be outputted in content elements edit form
	}
}