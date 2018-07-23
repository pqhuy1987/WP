<?php
/*
Plugin Name: BdThemes Shortcodes
Plugin URI: http://bdthemes.com/
Description: Shortcodes for BdThemes WordPress theme.
Version: 1.0.0
Author: Bdthemes
Author URI: http://bdthemes.com/
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2

BdThemes Shortcodes is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

BdThemes Shortcodes is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with BdThemes Shortcodes. If not, see {License URI}.
*/

function bdthemes_shortcode_empty_paragraph_fix($content){
    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );

    $content = strtr($content, $array);
    return $content;
}

add_filter('the_content', 'bdthemes_shortcode_empty_paragraph_fix');

//Remove e.g. from values
function bdthemes_arrangement_shortcode_value($value) {
	return preg_replace('/^e.g.\s*/', '', $value);
}

function bdthemes_arrangement_shortcode_arr_value(&$value) {
	$value = preg_replace('/^e.g.\s*/', '', $value);
}

// Helper function here
require_once(dirname(__FILE__).'/helper.php');
require_once(dirname(__FILE__).'/register-assets.php');
require_once(dirname(__FILE__).'/shortcodes/post_carousel.php');
require_once(dirname(__FILE__).'/shortcodes/service_carousel.php');
require_once(dirname(__FILE__).'/shortcodes/team_carousel.php');
require_once(dirname(__FILE__).'/shortcodes/counter.php');
require_once(dirname(__FILE__).'/shortcodes/gallery.php');
require_once(dirname(__FILE__).'/shortcodes/heading.php');
require_once(dirname(__FILE__).'/shortcodes/team_member.php');
require_once(dirname(__FILE__).'/shortcodes/inline_icon.php');
require_once(dirname(__FILE__).'/shortcodes/flickr.php');
require_once(dirname(__FILE__).'/shortcodes/icon_list_item.php');
require_once(dirname(__FILE__).'/shortcodes/testimonial.php');
require_once(dirname(__FILE__).'/shortcodes/custom_carousel.php');
require_once(dirname(__FILE__).'/shortcodes/calltoaction.php');