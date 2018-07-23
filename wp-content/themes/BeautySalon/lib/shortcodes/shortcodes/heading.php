<?php
// Bdthemes heading Shortcode

if (!function_exists('bdthemes_heading')) {
	function bdthemes_heading($atts = null, $content = null) {
		$atts = shortcode_atts(array(
		    'style'         => 'default',
		    'size'          => '24',
		    'align'         => 'center',
		    'margin'        => '',
		    'width'         => '',
		    'heading'       => 'h3',
		    'color'         => ''
		), $atts, 'bdt_heading');

		$id     = uniqid('suhead');
		
		$width  = ($atts['width']) ? 'width: ' . intVal($atts['width']) . '%;' : '';
		$margin = ($atts['margin']) ? 'margin-bottom: ' . intVal($atts['margin']) . 'px;' : '';
		$size   = ($atts['size']) ? 'font-size: ' . intVal($atts['size']) . 'px;line-height: '.$atts['size'].'px;' : '';
		$color  = ($atts['color']) ? ' color: ' . $atts['color'] . ';' : '';


		return '<div id="'.$id.'" class="bdt-heading bdt-heading-style-' . $atts['style'] . ' bdt-heading-align-'. $atts['align'] .'" style="'.$width.$margin.'"><'.$atts['heading'].' class="bdt-heading-inner" style="'.$size.$color.'">' . do_shortcode($content) . '</'.$atts['heading'].'></div>';
	}
	add_shortcode('bdt_heading', 'bdthemes_heading');
}