<?php

defined('ABSPATH') or die;

/*
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 *
 */
require_once( dirname( __FILE__ ) . '/options/options.php' );

/*
 * 
 * Add support tab
 *
 */
if ( ! defined('MTS_THEME_WHITE_LABEL') || ! MTS_THEME_WHITE_LABEL ) {
	require_once( dirname( __FILE__ ) . '/options/support.php' );
	$mts_options_tab_support = MTS_Options_Tab_Support::get_instance();
}

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constants for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
		'title' => __('A Section added by hook', 'splash' ),
		'desc' => '<p class="description">' . __('This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.', 'splash' ) . '</p>',
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You don't have to though, leave it blank for default.
		'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array()
	);
	
	return $sections;
	
}//function
//add_filter('nhp-opts-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function
//add_filter('nhp-opts-args-twenty_eleven', 'change_framework_args');

/*
 * This is the meat of creating the options page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
	$args = array();

	//Set it to dev mode to view the class settings/info in the form - default is false
	$args['dev_mode'] = false;
	//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
	//$args['stylesheet_override'] = true;

	//Add HTML before the form
	//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'splash' );

	if ( ! MTS_THEME_WHITE_LABEL ) {
		//Setup custom links in the footer for share icons
		$args['share_icons']['twitter'] = array(
			'link' => 'http://twitter.com/mythemeshopteam',
			'title' => __( 'Follow Us on Twitter', 'splash' ),
			'img' => 'fa fa-twitter-square'
		);
		$args['share_icons']['facebook'] = array(
			'link' => 'http://www.facebook.com/mythemeshop',
			'title' => __( 'Like us on Facebook', 'splash' ),
			'img' => 'fa fa-facebook-square'
		);
	}

	//Choose to disable the import/export feature
	//$args['show_import_export'] = false;

	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
	$args['opt_name'] = MTS_THEME_NAME;

	//Custom menu icon
	//$args['menu_icon'] = '';

	//Custom menu title for options page - default is "Options"
	$args['menu_title'] = __('Theme Options', 'splash' );

	//Custom Page Title for options page - default is "Options"
	$args['page_title'] = __('Theme Options', 'splash' );

	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
	$args['page_slug'] = 'theme_options';

	//Custom page capability - default is set to "manage_options"
	//$args['page_cap'] = 'manage_options';

	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	//$args['page_type'] = 'submenu';

	//parent menu - default is set to "themes.php" (Appearance)
	//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$args['page_parent'] = 'themes.php';

	//custom page location - default 100 - must be unique or will override other items
	$args['page_position'] = 62;

	//Custom page icon class (used to override the page icon next to heading)
	//$args['page_icon'] = 'icon-themes';

	if ( ! MTS_THEME_WHITE_LABEL ) {
		//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition
		$args['help_tabs'][] = array(
			'id' => 'nhp-opts-1',
			'title' => __('Support', 'splash' ),
			'content' => '<p>' . sprintf( __('If you are facing any problem with our theme or theme option panel, head over to our %s.', 'splash' ), '<a href="http://community.mythemeshop.com/">'. __( 'Support Forums', 'splash' ) . '</a>' ) . '</p>'
		);
		$args['help_tabs'][] = array(
			'id' => 'nhp-opts-2',
			'title' => __('Earn Money', 'splash' ),
			'content' => '<p>' . sprintf( __('Earn 70%% commision on every sale by refering your friends and readers. Join our %s.', 'splash' ), '<a href="http://mythemeshop.com/affiliate-program/">' . __( 'Affiliate Program', 'splash' ) . '</a>' ) . '</p>'
		);
	}

	//Set the Help Sidebar for the options page - no sidebar by default										
	//$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'splash' );

	$mts_patterns = array(
		'nobg' => array('img' => NHP_OPTIONS_URL.'img/patterns/nobg.png'),
		'pattern0' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern0.png'),
		'pattern1' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern1.png'),
		'pattern2' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern2.png'),
		'pattern3' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern3.png'),
		'pattern4' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern4.png'),
		'pattern5' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern5.png'),
		'pattern6' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern6.png'),
		'pattern7' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern7.png'),
		'pattern8' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern8.png'),
		'pattern9' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern9.png'),
		'pattern10' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern10.png'),
		'pattern11' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern11.png'),
		'pattern12' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern12.png'),
		'pattern13' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern13.png'),
		'pattern14' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern14.png'),
		'pattern15' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern15.png'),
		'pattern16' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern16.png'),
		'pattern17' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern17.png'),
		'pattern18' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern18.png'),
		'pattern19' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern19.png'),
		'pattern20' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern20.png'),
		'pattern21' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern21.png'),
		'pattern22' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern22.png'),
		'pattern23' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern23.png'),
		'pattern24' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern24.png'),
		'pattern25' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern25.png'),
		'pattern26' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern26.png'),
		'pattern27' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern27.png'),
		'pattern28' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern28.png'),
		'pattern29' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern29.png'),
		'pattern30' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern30.png'),
		'pattern31' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern31.png'),
		'pattern32' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern32.png'),
		'pattern33' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern33.png'),
		'pattern34' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern34.png'),
		'pattern35' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern35.png'),
		'pattern36' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern36.png'),
		'pattern37' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern37.png'),
		'hbg' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg.png'),
		'hbg2' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg2.png'),
		'hbg3' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg3.png'),
		'hbg4' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg4.png'),
		'hbg5' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg5.png'),
		'hbg6' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg6.png'),
		'hbg7' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg7.png'),
		'hbg8' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg8.png'),
		'hbg9' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg9.png'),
		'hbg10' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg10.png'),
		'hbg11' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg11.png'),
		'hbg12' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg12.png'),
		'hbg13' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg13.png'),
		'hbg14' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg14.png'),
		'hbg15' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg15.png'),
		'hbg16' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg16.png'),
		'hbg17' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg17.png'),
		'hbg18' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg18.png'),
		'hbg19' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg19.png'),
		'hbg20' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg20.png'),
		'hbg21' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg21.png'),
		'hbg22' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg22.png'),
		'hbg23' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg23.png'),
		'hbg24' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg24.png'),
		'hbg25' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg25.png')
	);

	$sections = array();

	$sections[] = array(
		'icon' => 'fa fa-cogs',
		'title' => __('General Settings', 'splash' ),
		'desc' => '<p class="description">' . __('This tab contains common setting options which will be applied to the whole theme.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_logo',
				'type' => 'upload',
				'title' => __('Logo Image', 'splash' ),
				'sub_desc' => __('Upload your logo using the Upload Button or insert image URL.', 'splash' )
			),
			array(
				'id' => 'mts_favicon',
				'type' => 'upload',
				'title' => __('Favicon', 'splash' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s favicon.', 'splash' ), '<strong>32 x 32 px</strong>' )
			),
			array(
				'id' => 'mts_touch_icon',
				'type' => 'upload',
				'title' => __('Touch icon', 'splash' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s touch icon for iOS 2.0+ and Android 2.1+ devices.', 'splash' ), '<strong>152 x 152 px</strong>' )
			),
			array(
				'id' => 'mts_metro_icon',
				'type' => 'upload',
				'title' => __('Metro icon', 'splash' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s IE 10 Metro tile icon.', 'splash' ), '<strong>144 x 144 px</strong>' )
			),
			array(
				'id' => 'mts_twitter_username',
				'type' => 'text',
				'title' => __('Twitter Username', 'splash' ),
				'sub_desc' => __('Enter your Username here.', 'splash' ),
			),
			array(
				'id' => 'mts_feedburner',
				'type' => 'text',
				'title' => __('FeedBurner URL', 'splash' ),
				'sub_desc' => sprintf( __('Enter your FeedBurner\'s URL here, ex: %s and your main feed (http://example.com/feed) will get redirected to the FeedBurner ID entered here.)', 'splash' ), '<strong>http://feeds.feedburner.com/mythemeshop</strong>' ),
				'validate' => 'url'
			),
			array(
				'id' => 'mts_header_code',
				'type' => 'textarea',
				'title' => __('Header Code', 'splash' ),
				'sub_desc' => wp_kses( __('Enter the code which you need to place <strong>before closing &lt;/head&gt; tag</strong>. (ex: Google Webmaster Tools verification, Bing Webmaster Center, BuySellAds Script, Alexa verification etc.)', 'splash' ), array( 'strong' => array() ) )
			),
			array(
				'id' => 'mts_analytics_code',
				'type' => 'textarea',
				'title' => __('Footer Code', 'splash' ),
				'sub_desc' => wp_kses( __('Enter the codes which you need to place in your footer. <strong>(ex: Google Analytics, Clicky, STATCOUNTER, Woopra, Histats, etc.)</strong>.', 'splash' ), array( 'strong' => array() ) )
			),
			array(
				'id' => 'mts_pagenavigation',
				'type' => 'radio',
				'title' => __('Pagination Type', 'splash' ),
				'sub_desc' => __('Select pagination type.', 'splash' ),
				'options' => array(
					'0'=> __('Default (Next / Previous)', 'splash' ),
					'1' => __('Numbered (1 2 3 4...)', 'splash' ),
					'2' => __( 'AJAX (Load More Button)', 'splash' ),
					'3' => __( 'AJAX (Auto Infinite Scroll)', 'splash' ) ),
				'std' => '0'
			),
			array(
				'id' => 'mts_ajax_search',
				'type' => 'button_set',
				'title' => __('AJAX Quick search', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable or disable search results appearing instantly below the search form', 'splash' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_full_posts',
				'type' => 'button_set',
				'title' => __('Posts on blog pages', 'splash' ),
				'options' => array('0' => 'Excerpts','1' => 'Full posts'),
				'sub_desc' => __('Show post excerpts or full posts on the homepage and other archive pages.', 'splash' ),
				'std' => '0',
				'class' => 'green'
			),
			array(
				'id' => 'mts_responsive',
				'type' => 'button_set',
				'title' => __('Responsiveness', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('MyThemeShop themes are responsive, which means they adapt to tablet and mobile devices, ensuring that your content is always displayed beautifully no matter what device visitors are using. Enable or disable responsiveness using this option.', 'splash' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_rtl',
				'type' => 'button_set',
				'title' => __('Right To Left Language Support', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable this option for right-to-left sites.', 'splash' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_shop_products',
				'type' => 'text',
				'title' => __('No. of Products', 'splash' ),
				'sub_desc' => __('Enter the total number of products which you want to show on shop page (WooCommerce plugin must be enabled).', 'splash' ),
				'validate' => 'numeric',
				'std' => '9',
				'class' => 'small-text'
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-bolt',
		'title' => __('Performance', 'splash' ),
		'desc' => '<p class="description">' . __('This tab contains performance-related options which can help speed up your website.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_prefetching',
				'type' => 'button_set',
				'title' => __('Prefetching', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable or disable prefetching. If user is on homepage, then single page will load faster and if user is on single page, homepage will load faster in modern browsers.', 'splash' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_lazy_load',
				'type' => 'button_set_hide_below',
				'title' => __('Lazy Load', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Delay loading of images outside of viewport, until user scrolls to them.', 'splash' ),
				'std' => '0',
				'args' => array('hide' => 2)
			),
			array(
				'id' => 'mts_lazy_load_thumbs',
				'type' => 'button_set',
				'title' => __('Lazy load featured images', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable or disable Lazy load of featured images across site.', 'splash' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_lazy_load_content',
				'type' => 'button_set',
				'title' => __('Lazy load post content images', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable or disable Lazy load of images inside post/page content.', 'splash' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_async_js',
				'type' => 'button_set',
				'title' => __('Async JavaScript', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Add %s attribute to script tags to improve page download speed.', 'splash' ), '<code>async</code>' ),
				'std' => '1',
			),
			array(
				'id' => 'mts_remove_ver_params',
				'type' => 'button_set',
				'title' => __('Remove ver parameters', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Remove %s parameter from CSS and JS file calls. It may improve speed in some browsers which do not cache files having the parameter.', 'splash' ), '<code>ver</code>' ),
				'std' => '1',
			),
			array(
				'id' => 'mts_optimize_wc',
				'type' => 'button_set',
				'title' => __('Optimize WooCommerce scripts', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Load WooCommerce scripts and styles only on WooCommerce pages (WooCommerce plugin must be enabled).', 'splash' ),
				'std' => '1'
				),
			'cache_message' => array(
				'id' => 'mts_cache_message',
				'type' => 'info',
				'title' => __('Use Cache', 'splash' ),
				// Translators: %1$s = popup link to W3 Total Cache, %2$s = popup link to WP Super Cache
				'desc' => sprintf(
					__('A cache plugin can increase page download speed dramatically. We recommend using %1$s or %2$s.', 'splash' ),
					'<a href="https://community.mythemeshop.com/tutorials/article/8-make-your-website-load-faster-using-w3-total-cache-plugin/" target="_blank" title="W3 Total Cache">W3 Total Cache</a>',
					'<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-super-cache&TB_iframe=true&width=772&height=574' ).'" class="thickbox" title="WP Super Cache">WP Super Cache</a>'
				),
			),
		)
	);

	// Hide cache message on multisite or if a chache plugin is active already
	if ( is_multisite() || strstr( join( ';', get_option( 'active_plugins' ) ), 'cache' ) ) {
		unset( $sections[1]['fields']['cache_message'] );
	}

	$sections[] = array(
		'icon' => 'fa fa-adjust',
		'title' => __('Styling Options', 'splash' ),
		'desc' => '<p class="description">' . __('Control the visual appearance of your theme, such as colors, layout and patterns, from here.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_color_scheme',
				'type' => 'color',
				'title' => __('Color Scheme', 'splash' ),
				'sub_desc' => __('The theme comes with unlimited color schemes for your theme\'s styling.', 'splash' ),
				'std' => '#2db2eb'
			),
			array(
				'id' => 'mts_layout',
				'type' => 'radio_img',
				'title' => __('Layout Style', 'splash' ),
				'sub_desc' => wp_kses( __('Choose the <strong>default sidebar position</strong> for your site. The position of the sidebar for individual posts can be set in the post editor.', 'splash' ), array( 'strong' => array() ) ),
				'options' => array(
					'cslayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/cs.png'),
					'sclayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/sc.png')
				),
				'std' => 'cslayout'
			),
			array(
				'id' => 'mts_background',
				'type' => 'background',
				'title' => __('Site Background', 'splash' ),
				'sub_desc' => __('Set background color, pattern and image from here.', 'splash' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	  => array(),
					'size'		  => array(),
					'gradient'	  => '',
					'parallax'	  => array(),
				),
				'std' => array(
					'color'		 => '#ffffff',
					'use'		   => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	  => 'left top',
					'size'		  => 'cover',
					'gradient'	  => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	  => '0',
				)
			),
			array(
				'id' => 'mts_custom_css',
				'type' => 'textarea',
				'title' => __('Custom CSS', 'splash' ),
				'sub_desc' => __('You can enter custom CSS code here to further customize your theme. This will override the default CSS used on your site.', 'splash' )
			),
			array(
				'id' => 'mts_lightbox',
				'type' => 'button_set',
				'title' => __('Lightbox', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('A lightbox is a stylized pop-up that allows your visitors to view larger versions of images without leaving the current page. You can enable or disable the lightbox here.', 'splash' ),
				'std' => '0'
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-credit-card',
		'title' => __('Header', 'splash' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of header section.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_sticky_nav',
				'type' => 'button_set',
				'title' => __('Floating Navigation Menu', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Use this button to enable %s.', 'splash' ), '<strong>' . __('Floating Navigation Menu', 'splash' ) . '</strong>' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_show_primary_nav',
				'type' => 'button_set_hide_below',
				'title' => __('Show Primary Menu', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Use this button to enable %s.', 'splash' ), '<strong>' . __( 'Primary Navigation Menu', 'splash' ) . '</strong>' ),
				'std' => '1',
				'reset_at_version' => '3.0'
				),
				array(
				'id' => 'mts_header_search',
				'type' => 'button_set',
				'title' => __('Show Header Search Form', 'splash'), 
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Use this button to Show or Hide <strong>Header Search Form</strong>.', 'splash') ),
				'std' => '1'
			),
			array(
				'id' => 'mts_show_secondary_nav',
				'type' => 'button_set_hide_below',
				'title' => __('Show secondary menu', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => sprintf( __('Use this button to enable %s.', 'splash' ), '<strong>' . __( 'Secondary Navigation Menu', 'splash' ) . '</strong>' ),
				'std' => '1',
				'reset_at_version' => '3.0'
			),
			array(
				'id' => 'mts_nav_color',
				'type' => 'color',
				'title' => __('Secondary Navigation Background', 'splash' ),
				'sub_desc' => __('The theme comes with unlimited color schemes for your theme\'s styling.', 'splash' ),
				'std' => '#222222',
				'reset_at_version' => '3.0'
			),
			array(
				'id' => 'mts_header_section2',
				'type' => 'button_set',
				'title' => __('Show Logo', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => wp_kses( __('Use this button to Show or Hide the <strong>Logo</strong> completely.', 'splash' ), array( 'strong' => array() ) ),
				'std' => '1'
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-table',
		'title' => __('Footer', 'splash' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of Footer section.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_footer_background',
				'type' => 'background',
				'title' => __('Footer Background', 'splash' ),
				'sub_desc' => __('Set Footer background color, pattern and image from here.', 'splash' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	  => array(),
					'size'		  => array(),
					'gradient'	  => '',
					'parallax'	  => array(),
				),
				'std' => array(
					'color'		 => '#fafafa',
					'use'		   => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	  => 'left top',
					'size'		  => 'cover',
					'gradient'	  => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	  => '0',
				)
			),
			array(
				'id' => 'mts_first_footer',
				'type' => 'button_set',
				'title' => __('Footer Widgets', 'splash' ),
				'sub_desc' => __('Enable or disable footer widgets with this option.', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'std' => '1',
				'reset_at_version' => '3.0'
			),
			array(
				'id' => 'mts_copyrights',
				'type' => 'textarea',
				'title' => __('Copyrights Text', 'splash' ),
				'sub_desc' => __( 'You can change or remove our link from footer and use your own custom text.', 'splash' ) . ( MTS_THEME_WHITE_LABEL ? '' : wp_kses( __('(You can also use your affiliate link to <strong>earn 70% of sales</strong>. Ex: <a href="https://mythemeshop.com/go/aff/aff" target="_blank">https://mythemeshop.com/?ref=username</a>)', 'splash' ), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ) ),
				'std' => MTS_THEME_WHITE_LABEL ? null : sprintf( __( 'Theme by %s', 'splash' ), '<a href="http://mythemeshop.com/" rel="nofollow">MyThemeShop</a>' )
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-home',
		'title' => __('Homepage', 'splash' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of the homepage.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_featured_slider',
				'type' => 'button_set_hide_below',
				'title' => __('Homepage Slider', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => wp_kses( __('<strong>Enable or Disable</strong> homepage slider with this button. The slider will show recent articles from the selected categories.', 'splash' ), array( 'strong' => array() ) ),
				'std' => '0',
				'args' => array('hide' => 3)
				),
				array(
					'id' => 'mts_featured_slider_cat',
					'type' => 'cats_multi_select',
					'title' => __('Slider Category(s)', 'splash' ),
					'sub_desc' => wp_kses( __('Select a category from the drop-down menu, latest articles from this category will be shown <strong>in the slider</strong>.', 'splash' ), array( 'strong' => array() ) ),
				),
				array(
					'id' => 'mts_featured_slider_num',
					'type' => 'text',
					'class' => 'small-text',
					'title' => __('Number of posts', 'splash' ),
					'sub_desc' => __('Enter the number of posts to show in the slider', 'splash' ),
					'std' => '3',
					'args' => array('type' => 'number')
				),	
				array(
					'id'		=> 'mts_custom_slider',
					'type'	  => 'group',
					'title'	 => __('Custom Slider', 'splash' ),
					'sub_desc'  => __('With this option you can set up a slider with custom image and text instead of the default slider automatically generated from your posts.', 'splash' ),
					'groupname' => __('Slider', 'splash' ), // Group name
					'subfields' => 
						array(
							array(
								'id' => 'mts_custom_slider_title',
								'type' => 'text',
								'title' => __('Title', 'splash' ),
								'sub_desc' => __('Title of the slide', 'splash' ),
							),
							array(
								'id' => 'mts_custom_slider_image',
								'type' => 'upload',
								'title' => __('Image', 'splash' ),
								'sub_desc' => __('Upload or select an image for this slide', 'splash' ),
								'return' => 'id'
							),	
							array('id' => 'mts_custom_slider_text',
								'type' => 'textarea',
								'title' => __('Text', 'splash' ),
								'sub_desc' => __('Description of the slide', 'splash' ),
							), 
							array('id' => 'mts_custom_slider_link',
								'type' => 'text',
								'title' => __('Link', 'splash' ),
								'sub_desc' => __('Insert a link URL for the slide', 'splash' ),
								'std' => '#'
							),
					),
				),
			array(
				'id'		=> 'mts_featured_categories',
				'type'	  => 'group',
				'title'	 => __('Featured Categories', 'splash' ),
				'sub_desc'  => __('Select categories appearing on the homepage.', 'splash' ),
				'groupname' => __('Section', 'splash' ), // Group name
				'subfields' => 
					array(
						array(
							'id' => 'mts_featured_category',
							'type' => 'cats_select',
							'title' => __('Category', 'splash' ),
							'sub_desc' => __('Select a category or the latest posts for this section', 'splash' ),
							'std' => 'latest',
							'args' => array('include_latest' => 1, 'hide_empty' => 0),
						),
						array(
							'id' => 'mts_featured_category_postsnum',
							'type' => 'text',
							'class' => 'small-text',
							'title' => __('Number of posts', 'splash' ),
							'sub_desc' => __('Enter the number of posts to show in this section.', 'splash' ),
							'std' => '3',
							'args' => array('type' => 'number')
						),
					),
					'std' => array(
						'1' => array(
							'group_title' => '',
							'group_sort' => '1',
							'mts_featured_category' => 'latest',
							'mts_featured_category_postsnum' => get_option('posts_per_page')
						)
					),
					'reset_at_version' => '3.0'
			),
			array(
				'id' => 'mts_home_layout',
				'type' => 'button_set',
				'title' => __('Default layout', 'splash'), 
				'options' => array( 'list' => __( 'List', 'splash' ), 'grid' => __( 'Grid', 'splash' ) ),
				'sub_desc' => __('Select default posts layout.', 'splash'),
				'std' => 'list',
				'class' => 'green',
				'reset_at_version' => '3.0'
			),
			array(
				'id' => 'mts_sorting',
				'type' => 'button_set',
				'title' => __('Show List/Grid layout selection', 'splash'), 
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Enable or disable <strong>Show Posts in</strong> layout option on Homepage.', 'splash'),
				'std' => '1'
			),
			array(
				'id'	   => 'mts_home_headline_meta_info',
				'type'	 => 'layout',
				'title'	=> __('HomePage Post Meta Info', 'splash' ),
				'sub_desc' => __('Organize how you want the post meta info to appear on the homepage', 'splash' ),
				'options'  => array(
					'enabled'  => array(
						'author'   => __('Author Name', 'splash' ),
						'date'	 => __('Date', 'splash' ),
						'category' => __('Categories', 'splash' )
					),
					'disabled' => array(
						'comment'  => __('Comment Count', 'splash' )
					)
				),
				'std'  => array(
					'enabled'  => array(
						'author'   => __('Author Name', 'splash' ),
						'date'	 => __('Date', 'splash' ),
						'category' => __('Categories', 'splash' )
					),
					'disabled' => array(
						'comment'  => __('Comment Count', 'splash' )
					)
				)
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-file-text',
		'title' => __('Single Posts', 'splash' ),
		'desc' => '<p class="description">' . __('From here, you can control the appearance and functionality of your single posts page.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id'	   => 'mts_single_post_layout',
				'type'	 => 'layout2',
				'title'	=> __('Single Post Layout', 'splash' ),
				'sub_desc' => __('Customize the look of single posts', 'splash' ),
				'options'  => array(
					'enabled'  => array(
						'content'   => array(
							'label' 	=> __('Post Content', 'splash' ),
							'subfields'	=> array(
								
							)
						),
						'author'   => array(
							'label' 	=> __('Author Box', 'splash' ),
							'subfields'	=> array(

							)
						),
						'related'   => array(
							'label' 	=> __('Related Posts', 'splash' ),
							'subfields'	=> array(
								array(
									'id' => 'mts_related_posts_taxonomy',
									'type' => 'button_set',
									'title' => __('Related Posts Taxonomy', 'splash' ) ,
									'options' => array(
										'tags' => __( 'Tags', 'splash' ),
										'categories' => __( 'Categories', 'splash' )
									) ,
									'class' => 'green',
									'sub_desc' => __('Related Posts based on tags or categories.', 'splash' ) ,
									'std' => 'categories'
								),
								array(
									'id' => 'mts_related_postsnum',
									'type' => 'text',
									'class' => 'small-text',
									'title' => __('Number of related posts', 'splash' ) ,
									'sub_desc' => __('Enter the number of posts to show in the related posts section.', 'splash' ) ,
									'std' => '4',
									'args' => array(
										'type' => 'number'
									)
								),

							)
						),
					),
					'disabled' => array(
						'tags'   => array(
							'label' 	=> __('Tags', 'splash' ),
							'subfields'	=> array()
						),
					)
				)
			),
			array(
				'id'	   => 'mts_single_headline_meta_info',
				'type'	 => 'layout',
				'title'	=> __('Meta Info to Show', 'splash' ),
				'sub_desc' => __('Organize how you want the post meta info to appear', 'splash' ),
				'options'  => array(
					'enabled'  => array(
						'author'   => __('Author Name', 'splash' ),
						'date'	 => __('Date', 'splash' ),
						'category' => __('Categories', 'splash' ),
						'comment'  => __('Comment Count', 'splash' )
					),
					'disabled' => array()
				),
				'std'  => array(
					'enabled'  => array(
						'author'   => __('Author Name', 'splash' ),
						'date'	 => __('Date', 'splash' ),
						'category' => __('Categories', 'splash' ),
						'comment'  => __('Comment Count', 'splash' )
					),
					'disabled' => array()
				)
			),
			array(
				'id' => 'mts_breadcrumb',
				'type' => 'button_set',
				'title' => __('Breadcrumbs', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Breadcrumbs are a great way to make your site more user-friendly. You can enable them by checking this box.', 'splash' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_author_comment',
				'type' => 'button_set',
				'title' => __('Highlight Author Comment', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Use this button to highlight author comments.', 'splash' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_comment_date',
				'type' => 'button_set',
				'title' => __('Date in Comments', 'splash' ),
				'options' => array( '0' => __( 'Off', 'splash' ), '1' => __( 'On', 'splash' ) ),
				'sub_desc' => __('Use this button to show the date for comments.', 'splash' ),
				'std' => '1'
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-group',
		'title' => __('Social Buttons', 'splash' ),
		'desc' => '<p class="description">' . __('Enable or disable social sharing buttons on single posts using these buttons.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_social_button_position',
				'type' => 'button_set',
				'title' => __('Social Sharing Buttons Position', 'splash' ),
				'options' => array('top' => __('Above Content', 'splash' ), 'bottom' => __('Below Content', 'splash' ), 'floating' => __('Floating', 'splash' )),
				'sub_desc' => __('Choose position for Social Sharing Buttons.', 'splash' ),
				'std' => 'floating',
				'class' => 'green'
			),
			array(
				'id' => 'mts_social_buttons_on_pages',
				'type' => 'button_set',
				'title' => __('Social Sharing Buttons on Pages', 'splash' ),
				'options' => array('0' => __('Off', 'splash' ), '1' => __('On', 'splash' )),
				'sub_desc' => __('Enable the sharing buttons for pages too, not just posts.', 'splash' ),
				'std' => '0',
			),
			array(
				'id'	   => 'mts_social_buttons',
				'type'	 => 'layout',
				'title'	=> __('Social Media Buttons', 'splash' ),
				'sub_desc' => __('Organize how you want the social sharing buttons to appear on single posts', 'splash' ),
				'options'  => array(
					'enabled'  => array(
						'facebookshare'   => __('Facebook Share', 'splash' ),
						'facebook'  => __('Facebook Like', 'splash' ),
						'twitter'   => __('Twitter', 'splash' ),
						'gplus'	 => __('Google Plus', 'splash' ),
						'pinterest' => __('Pinterest', 'splash' ),
					),
					'disabled' => array(
						'linkedin'  => __('LinkedIn', 'splash' ),
						'stumble'   => __('StumbleUpon', 'splash' ),
					)
				),
				'std'  => array(
					'enabled'  => array(
						'facebookshare'   => __('Facebook Share', 'splash' ),
						'facebook'  => __('Facebook Like', 'splash' ),
						'twitter'   => __('Twitter', 'splash' ),
						'gplus'	 => __('Google Plus', 'splash' ),
						'pinterest' => __('Pinterest', 'splash' ),
					),
					'disabled' => array(
						'linkedin'  => __('LinkedIn', 'splash' ),
						'stumble'   => __('StumbleUpon', 'splash' ),
					)
				)
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-bar-chart-o',
		'title' => __('Ad Management', 'splash' ),
		'desc' => '<p class="description">' . __('Now, ad management is easy with our options panel. You can control everything from here, without using separate plugins.', 'splash' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_posttop_adcode',
				'type' => 'textarea',
				'title' => __('Below Post Title', 'splash' ),
				'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below your article title on single posts.', 'splash' )
			),
			array(
				'id' => 'mts_posttop_adcode_time',
				'type' => 'text',
				'title' => __('Show After X Days', 'splash' ),
				'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'splash' ),
				'validate' => 'numeric',
				'std' => '0',
				'class' => 'small-text',
				'args' => array('type' => 'number')
			),
			array(
				'id' => 'mts_postend_adcode',
				'type' => 'textarea',
				'title' => __('Below Post Content', 'splash' ),
				'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below the post content on single posts.', 'splash' )
			),
			array(
				'id' => 'mts_postend_adcode_time',
				'type' => 'text',
				'title' => __('Show After X Days', 'splash' ),
				'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'splash' ),
				'validate' => 'numeric',
				'std' => '0',
				'class' => 'small-text',
				'args' => array('type' => 'number')
			),
		)
	);

	$sections[] = array(
		'icon' => 'fa fa-columns',
		'title' => __('Sidebars', 'splash' ),
		'desc' => '<p class="description">' . __('Now you have full control over the sidebars. Here you can manage sidebars and select one for each section of your site, or select a custom sidebar on a per-post basis in the post editor.', 'splash' ) . '<br></p>',
		'fields' => array(
			array(
				'id'		=> 'mts_custom_sidebars',
				'type'	  => 'group', //doesn't need to be called for callback fields
				'title'	 => __('Custom Sidebars', 'splash' ),
				'sub_desc'  => wp_kses( __('Add custom sidebars. <strong style="font-weight: 800;">You need to save the changes to use the sidebars in the dropdowns below.</strong><br />You can add content to the sidebars in Appearance &gt; Widgets.', 'splash' ), array( 'strong' => array(), 'br' => array() ) ),
				'groupname' => __('Sidebar', 'splash' ), // Group name
				'subfields' => 
					array(
						array(
							'id' => 'mts_custom_sidebar_name',
							'type' => 'text',
							'title' => __('Name', 'splash' ),
							'sub_desc' => __('Example: Homepage Sidebar', 'splash' )
						),	
						array(
							'id' => 'mts_custom_sidebar_id',
							'type' => 'text',
							'title' => __('ID', 'splash' ),
							'sub_desc' => __('Enter a unique ID for the sidebar. Use only alphanumeric characters, underscores (_) and dashes (-), eg. "sidebar-home"', 'splash' ),
							'std' => 'sidebar-'
						),
					),
			),
			array(
				'id' => 'mts_sidebar_for_home',
				'type' => 'sidebars_select',
				'title' => __('Homepage', 'splash' ),
				'sub_desc' => __('Select a sidebar for the homepage.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_post',
				'type' => 'sidebars_select',
				'title' => __('Single Post', 'splash' ),
				'sub_desc' => __('Select a sidebar for the single posts. If a post has a custom sidebar set, it will override this.', 'splash' ),
				'args' => array('exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_page',
				'type' => 'sidebars_select',
				'title' => __('Single Page', 'splash' ),
				'sub_desc' => __('Select a sidebar for the single pages. If a page has a custom sidebar set, it will override this.', 'splash' ),
				'args' => array('exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_archive',
				'type' => 'sidebars_select',
				'title' => __('Archive', 'splash' ),
				'sub_desc' => __('Select a sidebar for the archives. Specific archive sidebars will override this setting (see below).', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_category',
				'type' => 'sidebars_select',
				'title' => __('Category Archive', 'splash' ),
				'sub_desc' => __('Select a sidebar for the category archives.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_tag',
				'type' => 'sidebars_select',
				'title' => __('Tag Archive', 'splash' ),
				'sub_desc' => __('Select a sidebar for the tag archives.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_date',
				'type' => 'sidebars_select',
				'title' => __('Date Archive', 'splash' ),
				'sub_desc' => __('Select a sidebar for the date archives.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_author',
				'type' => 'sidebars_select',
				'title' => __('Author Archive', 'splash' ),
				'sub_desc' => __('Select a sidebar for the author archives.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_search',
				'type' => 'sidebars_select',
				'title' => __('Search', 'splash' ),
				'sub_desc' => __('Select a sidebar for the search results.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_notfound',
				'type' => 'sidebars_select',
				'title' => __('404 Error', 'splash' ),
				'sub_desc' => __('Select a sidebar for the 404 Not found pages.', 'splash' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_shop',
				'type' => 'sidebars_select',
				'title' => __('Shop Pages', 'splash' ),
				'sub_desc' => wp_kses( __('Select a sidebar for Shop main page and product archive pages (WooCommerce plugin must be enabled). Default is <strong>Shop Page Sidebar</strong>.', 'splash' ), array( 'strong' => array() ) ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => 'shop-sidebar'
			),
			array(
				'id' => 'mts_sidebar_for_product',
				'type' => 'sidebars_select',
				'title' => __('Single Product', 'splash' ),
				'sub_desc' => wp_kses( __('Select a sidebar for single products (WooCommerce plugin must be enabled). Default is <strong>Single Product Sidebar</strong>.', 'splash' ), array( 'strong' => array() ) ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-1', 'footer-2', 'footer-3', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => 'product-sidebar'
			),
		),
	);

	$sections[] = array(
		'icon' => 'fa fa-list-alt',
		'title' => __('Navigation', 'splash' ),
		'desc' => '<p class="description"><div class="controls">' . sprintf( __('Navigation settings can now be modified from the %s.', 'splash' ), '<a href="nav-menus.php"><b>' . __( 'Menus Section', 'splash' ) . '</b></a>' ) . '<br></div></p>'
	);

				
	$tabs = array();
	
	$args['presets'] = array();
	$args['show_translate'] = false;
	include('theme-presets.php');
	
	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function

/*--------------------------------------------------------------------
 * 
 * Default Font Settings
 *
 --------------------------------------------------------------------*/
if(function_exists('mts_register_typography')) { 
	mts_register_typography(array(
		'logo_font' => array(
			'preview_text' => __( 'Logo', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '38px',
			'font_color' => '#2DB2EB',
			'additional_css' => 'text-transform: uppercase;',
			'css_selectors' => '#logo a'
		),
		'top_navigation_font' => array(
			'preview_text' => __( 'Primary Navigation Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '14px',
			'font_color' => '#989898',
			'additional_css' => 'text-transform: uppercase;',
			'css_selectors' => '#navigation.primary-navigation a'
		),
		'navigation_font' => array(
			'preview_text' => __( 'Secondary Navigation Font', 'splash' ),
			'preview_color' => 'dark',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '14px',
			'font_color' => '#FFFFFF',
			'additional_css' => 'text-transform: uppercase;',
			'css_selectors' => '#navigation.secondary-navigation a'
		),
		'home_title_font' => array(
			'preview_text' => __( 'Home Article Title', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_size' => '24px',
			'font_variant' => '400',
			'font_color' => '#2B2B2B',
			'css_selectors' => '.latestPost .title a'
		),
		'single_title_font' => array(
			'preview_text' => __( 'Single Article Title', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_size' => '24px',
			'font_variant' => '400',
			'font_color' => '#333333',
			'css_selectors' => '.single-title'
		),
		'content_font' => array(
			'preview_text' => __( 'Content Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Open Sans',
			'font_size' => '14px',
			'font_variant' => 'normal',
			'font_color' => '#666666',
			'css_selectors' => 'body'
		),
		'sidebar_title_font' => array(
			'preview_text' => __( 'Sidebar Title Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '18px',
			'font_color' => '#333333',
			'css_selectors' => '.sidebar .widget h3'
		),
		'sidebar_widget_font' => array(
			'preview_text' => __( 'Sidebar Widget Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Open Sans',
			'font_variant' => '400',
			'font_size' => '14px',
			'font_color' => '#666666',
			'css_selectors' => '.sidebar .widget, .sidebar .widget li'
		),
		'footer_font' => array(
			'preview_text' => __( 'Footer Widget Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Open Sans',
			'font_variant' => 'normal',
			'font_size' => '14px',
			'font_color' => '#999999',
			'css_selectors' => '#site-footer, .footer-widgets'
		),
		'footer_title_font' => array(
			'preview_text' => __( 'Footer Title Font', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => 'normal',
			'font_size' => '18px',
			'font_color' => '#222222',
			'css_selectors' => '.footer-widgets h3'
		),
		'h1_headline' => array(
			'preview_text' => __( 'Content H1', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '28px',
			'font_color' => '#222222',
			'css_selectors' => 'h1'
		),
		'h2_headline' => array(
			'preview_text' => __( 'Content H2', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '24px',
			'font_color' => '#222222',
			'css_selectors' => 'h2'
		),
		'h3_headline' => array(
			'preview_text' => __( 'Content H3', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '22px',
			'font_color' => '#222222',
			'css_selectors' => 'h3'
		),
		'h4_headline' => array(
			'preview_text' => __( 'Content H4', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '20px',
			'font_color' => '#222222',
			'css_selectors' => 'h4'
		),
		'h5_headline' => array(
			'preview_text' => __( 'Content H5', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '18px',
			'font_color' => '#222222',
			'css_selectors' => 'h5'
		),
		'h6_headline' => array(
			'preview_text' => __( 'Content H6', 'splash' ),
			'preview_color' => 'light',
			'font_family' => 'Bree Serif',
			'font_variant' => '400',
			'font_size' => '16px',
			'font_color' => '#222222',
			'css_selectors' => 'h6'
		)
	));
}