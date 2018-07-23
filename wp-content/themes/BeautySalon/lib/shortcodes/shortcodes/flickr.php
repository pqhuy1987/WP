<?php
// Bdthemes flickr shortcode

if (!function_exists('bdthemes_flickr')) {
	function bdthemes_flickr($atts) {
		$atts = shortcode_atts(array(
			'flickr_id' => '95572727@N00',
			'limit'     => '9',
			'lightbox'  => 'no',
			'radius'    => '0px',
			'class'     => ''
		), $atts, 'bdt_flickr');

		$unique_id = uniqid("flickr_");
		$rounded = ($atts['radius']) ? 'border-radius: ' . $atts['radius'] . ';' : '';

		$style = 'style="' . $rounded . '"';

		$image = ($atts['lightbox'] == 'yes') ? '<a class="bdt-lightbox" data-uk-lightbox="{group: \''.$unique_id.'\'}" href="{{image_b}}" title="{{title}}"' . $style . '> ' : '';
		$image .= '<img ' . $style . ' src="{{image_s}}" alt="{{title}}" />';
		$image .= ($atts['lightbox'] == 'yes') ? '</a> ' : '';


		if ($atts['lightbox'] == 'yes') {
		    $atts['class'] .= ' bdt-flickr-lightbox';
		}

		$output = "<ul id='".$unique_id."' class='flickrfeed".bdt_ecssc($atts)."'></ul> <div class='clear'></div>";

		$output .= "<script type='text/javascript'>
				      jQuery(document).ready(function() {
				              jQuery('#".$unique_id."').jflickrfeed({
				                limit: " . $atts['limit'] . ", qstrings: {
				                  id: '" . $atts['flickr_id'] . "'},
				                  itemTemplate: '<li>" . addslashes($image) . "</li>' });
				            });
				    </script> ";
		return $output;
	}
	add_shortcode('bdt_flickr', 'bdthemes_flickr');
}