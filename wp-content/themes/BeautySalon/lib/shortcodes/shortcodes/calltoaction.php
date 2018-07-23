<?php
// Bdthemes calltoaction shortcode

if (!function_exists('bdthemes_calltoaction')) {
	function bdthemes_calltoaction($atts = null, $content = null) {
		$atts = shortcode_atts(array(
		    'title'                   => __('Call to action title', 'warp'),
		    'button_text'             => __('Button Text', 'warp'),
		    'align'                   => 'left',
		    'button_link'             => '#',
		    'target'                  => 'self',
		    'background'              => '',
		    'border_color'            => '',
		    'button_radius'           => '',
		    'class'                   => ''
		), $atts, 'bdt_calltoaction');

		$id            = uniqid('suca_');
		$return        = array();
		$title         = ($atts['title']) ? "<h3>" . $atts['title'] . "</h3>" : '';
		$target        = ($atts['target'] === 'yes' || $atts['target'] === 'blank') ? ' target="_blank"' : 'target="_self"';
		$background    = ($atts['background']) ? 'background-color:' . $atts['background'].';' : '';
		$border_color    = ($atts['border_color']) ? 'border-color:' . $atts['border_color'].';' : '';
		$button_radius = ($atts['button_radius']) ? 'border-radius:' . $atts['button_radius'].';' : '';


		$return[] = '<div id="'.$id.'" class="bdt-call-to-action'.bdt_ecssc($atts).' cta-align-'.$atts['align'].'" style="'.$background.$border_color.'">';
		    $return[] = '<a class="cta-dbtn bdt-ca-hp" '.$target . ' href="'. $atts['button_link'].'" style="'.$button_radius.'">'. $atts['button_text'] . '</a>';
		    $return[] = '<div class="cta-content">'.$title.'<div class="bdt-ca-dtxt">'.do_shortcode($content).'</div></div>';
		        $return[] = "<a class='cta-dbtn bdt-ca-vp' " . $target . " href='" . $atts['button_link'] . "'>" . $atts['button_text'] . "</a>";
		    $return[] = '<div class="clear">';
		    $return[] = '</div>';
		$return[] = '</div>';

		return implode("\n", $return);
	}
	add_shortcode('bdt_calltoaction', 'bdthemes_calltoaction');
}