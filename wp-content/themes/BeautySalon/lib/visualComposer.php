<?php

/**
* visual composer integration
*/

class BDT_VisualComposer {
    
    function __construct()
    {
        add_action('after_setup_theme',array($this,'vc_elements_init'));
    }

    function vc_elements_init() {
        // VC Params
        require_once(dirname(__FILE__).'/vc/params/number.php');

        // VC Elements 
        require_once(dirname(__FILE__).'/vc/vc.calltoaction.php');
        require_once(dirname(__FILE__).'/vc/vc.counter.php');
        require_once(dirname(__FILE__).'/vc/vc.customCarousel.php');
        require_once(dirname(__FILE__).'/vc/vc.divider.php');
        require_once(dirname(__FILE__).'/vc/vc.eventCarousel.php');
        require_once(dirname(__FILE__).'/vc/vc.flickr.php');
        require_once(dirname(__FILE__).'/vc/vc.gallery.php');
        require_once(dirname(__FILE__).'/vc/vc.heading.php');
        require_once(dirname(__FILE__).'/vc/vc.iconListItem.php');
        require_once(dirname(__FILE__).'/vc/vc.inlineIcon.php');
        require_once(dirname(__FILE__).'/vc/vc.note.php');
        require_once(dirname(__FILE__).'/vc/vc.postCarousel.php');
        require_once(dirname(__FILE__).'/vc/vc.progressBar.php');
        require_once(dirname(__FILE__).'/vc/vc.progressPie.php');
        require_once(dirname(__FILE__).'/vc/vc.testimonial.php');
        require_once(dirname(__FILE__).'/vc/vc.spacer.php');
        require_once(dirname(__FILE__).'/vc/vc.serviceCarousel.php');
        require_once(dirname(__FILE__).'/vc/vc.teamMember.php');
        require_once(dirname(__FILE__).'/vc/vc.teamCarousel.php');

    } // end vc_elements_init
}

new BDT_VisualComposer;


// Visual composer theme integration
add_action( 'vc_before_init', 'bdthemes_vcSetAsTheme' );
function bdthemes_vcSetAsTheme() {
    if(function_exists('vc_set_as_theme')) vc_set_as_theme(true);
}

function bdthemes_vc_hide_updates( $value ) {
    if (isset($value->response['js_composer/js_composer.php'])) {
        unset( $value->response['js_composer/js_composer.php'] );
        return $value;
    }
    return null;
}
add_filter( 'site_transient_update_plugins', 'bdthemes_vc_hide_updates' );


// Remove Default Templates
add_filter( 'vc_load_default_templates', 'bdthemes_template_modify_array' );
function bdthemes_template_modify_array($data) {
    return array(); // This will remove all default templates
}

// Disable Instructional/Help Pointers
add_action( 'vc_before_init', 'vc_remove_all_pointers' );
function vc_remove_all_pointers() {
   remove_action( 'admin_enqueue_scripts', 'vc_pointer_load' );
}

// Deregister Composer Custom CSS
//wp_deregister_style( 'js_composer_custom_css' );

// // Load Visual Composer at Top of the Site
// if ( function_exists( 'vc_map' ) && !is_admin() ) {
//     wp_enqueue_style( 'js_composer_front' );
// }




/* 
 * Remove Visual Composer Elements
 */

//vc_remove_element("vc_separator");
//vc_remove_element("vc_text_separator");
//vc_remove_element("vc_message");
//vc_remove_element("vc_empty_space");
//vc_remove_element("vc_gallery");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_posts_slider");
//vc_remove_element("vc_flickr");
vc_remove_element("vc_cta");
//vc_remove_element("vc_pie");
vc_remove_element("vc_progress_bar");
vc_remove_element("vc_custom_heading");
vc_remove_element("rev_slider_vc");
vc_remove_element("vc_media_grid");
vc_remove_element("vc_masonry_grid");
vc_remove_element("vc_masonry_media_grid");
vc_remove_element("vc_icon");
vc_remove_element("vc_basic_grid");
vc_remove_element("vc_widget_sidebar");
vc_remove_element("vc_line_chart");
vc_remove_element("vc_round_chart");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_text");

// Remove from VC 4.7
vc_remove_element("vc_tta_tour");
vc_remove_element("vc_tta_pageable");

// Deprecated version removed
vc_remove_element("vc_accordion");
//vc_remove_element("vc_tour");
vc_remove_element("vc_tabs");
vc_remove_element("vc_button");
vc_remove_element("vc_button2");
//vc_remove_element("vc_cta_button");
vc_remove_element("vc_cta_button2");



if (is_admin()) {
    function bdthemes_vc_admin_styles() {   
        // Register Styles
        wp_register_style( 'bdt-vc-admin-style', get_template_directory_uri() . '/lib/vc/assets/css/admin.css' );
        wp_enqueue_style( 'bdt-vc-admin-style' );

    }  
    add_action( 'admin_enqueue_scripts', 'bdthemes_vc_admin_styles' );
}


