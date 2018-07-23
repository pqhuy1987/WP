<?php
// Bdthemes Custom Carousel

if (!function_exists('bdthemes_custom_carousel')) {
	function bdthemes_custom_carousel($atts=null, $content=null){
		$atts = shortcode_atts(array(
			'large'          => 4,
			'medium'         => 3,
			'small'          => 1,
			'scroll'         => 1,
			'arrows'         => 'true',
			'arrow_position' => 'default',
			'pagination'     => 'true',
			'autoplay'       => 'true',
			'delay'          => 4000,
			'speed'          => 350,
			'hoverpause'     => 'false',
			'lazyload'       => 'false',
			'loop'           => 'true',
			'gutter'		 => ''
		), $atts, 'bdt_custom_carousel');

		if ($atts['gutter'] == 'large') { $gutter = 50; }
		elseif ($atts['gutter'] == 'medium') { $gutter = 25; }
		elseif ($atts['gutter'] == 'small') { $gutter = 10; }
		elseif ($atts['gutter'] == 'collapse') { $gutter = 0; }
		else { $gutter = 35; }
		
		$output = array();

		$output[] = '<div class="bdt-owl-carousel" data-autoplay="' . $atts['autoplay'] .'" data-delay="' . $atts['delay'] . '" data-speed="' . $atts['speed'] . '" data-arrows="' . $atts['arrows'] .'" data-pagination="' . $atts['pagination'] . '" data-lazyload="' . $atts['lazyload'] . '" data-hoverpause="' . $atts['hoverpause'] . '" data-large="' . $atts['large'] . '" data-medium="' . $atts['medium'] . '" data-small="' . $atts['small'] . '" data-margin="' . $gutter . '" data-scroll="' . $atts['scroll'] . '" data-loop="' . $atts['loop'] . '">';
            $output[] = '<div class="bdt-carousel-slides">';
                $output[] = bdthemes_override_shortcodes();
				$output[] = do_shortcode($content);
				bdthemes_restore_shortcodes();
            $output[] = '</div>';
        $output[]= '</div>';

        wp_enqueue_script( 'owl-carousel' );

		return implode("\n", $output);
	}
	add_shortcode('bdt_custom_carousel', 'bdthemes_custom_carousel');


	function bdthemes_override_shortcodes() {
	    global $shortcode_tags, $_shortcode_tags;
	    // Let's make a back-up of the shortcodes
	    $_shortcode_tags = $shortcode_tags;
	    // Add any shortcode tags that we shouldn't touch here
	    $disabled_tags = array( '' );
	    foreach ( $shortcode_tags as $tag => $cb ) {
	        if ( in_array( $tag, $disabled_tags ) ) {
	            continue;
	        }
	        // Overwrite the callback function
	        $shortcode_tags[ $tag ] = 'bdthemes_wrap_shortcode_in_div';
	    }
	}


	function bdthemes_restore_shortcodes() {
	    global $shortcode_tags, $_shortcode_tags;
	    // Restore the original callbacks
	    if ( isset( $_shortcode_tags ) ) {
	        $shortcode_tags = $_shortcode_tags;
	    }
	}

	function bdthemes_wrap_shortcode_in_div( $attr, $content = null, $tag ) {
	    global $_shortcode_tags;
	    return '<div class="bdt-carousel-slide">' . call_user_func( $_shortcode_tags[ $tag ], $attr, $content, $tag ) . '</div>';
	}
}