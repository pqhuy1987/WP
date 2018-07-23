<?php
function bdthemes_shortcode_styles() {
    // Register Styles
    wp_register_style( 'bdt-shortcodes-css', BDTHM_SC_URI.'css/shortcodes.css');

    wp_enqueue_style( 'bdt-shortcodes-css' );

}
add_action( 'wp_enqueue_scripts', 'bdthemes_shortcode_styles' );


function bdthemes_shortcode_scripts() {

    wp_register_script('bdt-shortcodes-js', BDTHM_SC_URI.'js/shortcodes.js', array( 'jquery' ), NULL, true);
    // Register Styles
    wp_register_script('owl-carousel', BDTHM_SC_URI.'js/owl.carousel.min.js', array( 'jquery' ), NULL, true);
    wp_register_script('jquery-appear', BDTHM_SC_URI.'js/jquery.appear.js', array( 'jquery' ), NULL, true);
    wp_register_script('jquery-countup', BDTHM_SC_URI.'js/jquery.countup.js', array( 'jquery' ), NULL, true);
    wp_register_script('team-member', BDTHM_SC_URI.'js/team-member.js', array( 'jquery' ), NULL, true);
    wp_register_script('jquery-easing', BDTHM_SC_URI.'js/jquery.easing.js', array( 'jquery' ), NULL, true);
    wp_register_script('flickr-lightbox', BDTHM_SC_URI.'js/flickr-lightbox.js', array( 'jquery' ), NULL, true);
    wp_register_script('icon-list-item', BDTHM_SC_URI.'js/icon-list.js', array( 'jquery' ), NULL, true);

    wp_enqueue_script('bdt-shortcodes-js');
}
add_action( 'wp_enqueue_scripts', 'bdthemes_shortcode_scripts' );