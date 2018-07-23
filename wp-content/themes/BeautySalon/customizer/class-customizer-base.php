<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package Megastar
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class beautysalon_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	public function __construct( WP_Customize_Manager $wp_manager ) {
		// set the private propery to instance of wp_manager
		$this->wp_customize = $wp_manager;

		// register the settings/panels/sections/controls, main method
		$this->register();

		/**
		 * Action and filters
		 */

		// render the CSS and cache it to the theme_mod when the setting is saved
		add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );

		// save logo width/height dimensions
		add_action( 'customize_save_beautysalon_logo_upload' , array( $this, 'beautysalon_save_logo_dimensions' ), 10, 1 );

		// flush the rewrite rules after the customizer settings are saved
		add_action( 'customize_save_after', 'flush_rewrite_rules' );


	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	*
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*
	* @see add_action('customize_register',$func)
	*/
	public function register () {
		/**
		 * Settings
		 */


		$this->wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$this->wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';


		if ( isset( $this->wp_customize->selective_refresh ) ) {
			$this->wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.site-title a',
				'container_inclusive' => false,
				'render_callback' => 'beautysalon_customize_partial_blogname',
			));
			$this->wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'container_inclusive' => false,
				'render_callback' => 'beautysalon_customize_partial_blogdescription',
			));
		}

		$this->wp_customize->add_setting( 'beautysalon_logo_upload' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'beautysalon_logo_upload', array(
			'priority' => 101,
		    'label'    => esc_html_x( 'Logo Upload', 'backend', 'beautysalon' ),
			'description' => esc_html_x('Use 210px by 40px default logo dimension for best header look.', 'backend', 'beautysalon'),
		    'section'  => 'title_tagline',
		    'settings' => 'beautysalon_logo_upload'
		)));

		$this->wp_customize->add_setting( 'beautysalon_logo_small_upload' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'beautysalon_logo_small_upload', array(
			'priority' => 103,
		    'label'    => esc_html_x( 'Mobile Logo Upload', 'backend', 'beautysalon' ),
			'description' => esc_html_x('Use 150px by 40px mobile logo dimension for best header look.', 'backend', 'beautysalon'),
		    'section'  => 'title_tagline',
		    'settings' => 'beautysalon_logo_small_upload'
		)));



		//general section
		$this->wp_customize->add_section('general', array(
			'title' => esc_html_x('General', 'backend', 'beautysalon'),
			'priority' => 30
		));	

		$this->wp_customize->add_setting('beautysalon_comment_show', array(
			'default' => 0,
			'sanitize_callback' => 'beautysalon_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'beautysalon_comment_show',
	        array(
				'priority'    => 2,
				'label'       => esc_html_x('Show Global Page Comment', 'backend', 'beautysalon'),
				'description' => esc_html_x('Enable / Disable global page comments (not post comment).', 'backend', 'beautysalon'),
				'section'     => 'general',
				'settings'    => 'beautysalon_comment_show',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'beautysalon'),
					0  => esc_html_x('No', 'backend', 'beautysalon')
				)
	        )
		));



		$this->wp_customize->add_section('breadcrumb', array(
			'title' => esc_attr__('Breadcrumb Settings', 'beautysalon'),
			'priority' => 31
		));

		$this->wp_customize->add_setting('show_breadcrumb', array(
			'default' => 1,
			'sanitize_callback' => 'beautysalon_sanitize_choices'
		) );
		$this->wp_customize->add_control('show_breadcrumb', array(
			'label'    => esc_attr__('Show Breadcrumb', 'beautysalon'),
			'section'  => 'breadcrumb',
			'settings' => 'show_breadcrumb', 
			'type'     => 'select',
			'priority' => 1,
			'choices'  => array(
				1 => esc_attr__('Yes', 'beautysalon'),
				0 => esc_attr__('No', 'beautysalon'),
			)
		));

		$this->wp_customize->add_setting('bdt_home_title', array(
			'sanitize_callback' => 'esc_attr'
		));
		$this->wp_customize->add_control('bdt_home_title', array(
			'priority' => 2,
		    'label'    => esc_attr__('Home Title: ', 'beautysalon'),
		    'section'  => 'breadcrumb',
		    'settings' => 'bdt_home_title'
		));

		$this->wp_customize->add_setting('bdt_blog_title', array(
			'default' => esc_attr__('Blog', 'beautysalon'),
			'sanitize_callback' => 'esc_attr'
		));
		$this->wp_customize->add_control('bdt_blog_title', array(
			'priority' => 2,
		    'label'    => esc_attr__('Blog Title: ', 'beautysalon'),
		    'section'  => 'breadcrumb',
		    'settings' => 'bdt_blog_title'
		));

		$this->wp_customize->add_setting('bdt_woocommerce_title', array(
			'default' => esc_attr__('Shop', 'beautysalon'),
			'sanitize_callback' => 'esc_attr'
		));
		$this->wp_customize->add_control('bdt_woocommerce_title', array(
			'priority' => 3,
		    'label'    => esc_attr__('WooCommerce Title: ', 'beautysalon'),
		    'section'  => 'breadcrumb',
		    'settings' => 'bdt_woocommerce_title'
		));


		//header section
		if (class_exists('Woocommerce')){
			$this->wp_customize->add_section('woocommerce', array(
				'title' => esc_html_x('WooCommerce', 'backend', 'beautysalon'),
				'priority' => 99
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_title', array(
				'default'           => esc_html_x('Shop', 'backend', 'beautysalon'),
				'sanitize_callback' => 'esc_attr'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_title', array(
			    'label'    => esc_html_x('WooCommerce Page Title: ', 'backend', 'beautysalon'),
			    'section'  => 'titlebar',
			    'settings' => 'beautysalon_woocommerce_title',
			    'priority' => 4,
			));


			$this->wp_customize->add_setting('beautysalon_woocommerce_columns', array(
				'default' => 3,
				'sanitize_callback' => 'beautysalon_sanitize_choices'
			) );
			$this->wp_customize->add_control('beautysalon_woocommerce_columns', array(
				'label'    => esc_html_x('WooCommerce Columns:', 'backend', 'beautysalon'),
				'section'  => 'woocommerce',
				'settings' => 'beautysalon_woocommerce_columns', 
				'type'     => 'select',
				'choices'  => array(
					2 => esc_html_x('2 Columns', 'backend', 'beautysalon'),
					3 => esc_html_x('3 Columns', 'backend', 'beautysalon'),
					4 => esc_html_x('4 Columns', 'backend', 'beautysalon')
				)
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_limit', array(
				'default' => 12,
				'sanitize_callback' => 'esc_attr'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_limit', array(
				'label'       => esc_html_x('Items per Shop Page: ', 'backend', 'beautysalon'),
				'description' => esc_html_x('Enter how many items you want to show on Shop pages & Categorie Pages before Pagination shows up (Default: 12)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_limit'
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_sort', array(
				'default' => 1,
				'sanitize_callback' => 'beautysalon_sanitize_checkbox'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_sort', array(
				'label'       => esc_html_x('Shop Sort', 'backend', 'beautysalon'),
				'description' => esc_html_x('(Enable / Disable sort-by function on Shop Pages)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_sort',
				'type'        => 'checkbox'
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_result_count', array(
				'default' => 1,
				'sanitize_callback' => 'beautysalon_sanitize_checkbox'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_result_count', array(
				'label'       => esc_html_x('Shop Result Count', 'backend', 'beautysalon'),
				'description' => esc_html_x('(Enable / Disable Result Count on Shop Pages)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_result_count',
				'type'        => 'checkbox'
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_cart_button', array(
				'default' => 1,
				'sanitize_callback' => 'beautysalon_sanitize_checkbox'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_cart_button', array(
				'label'       => esc_html_x('Add to Cart Button', 'backend', 'beautysalon'),
				'description' => esc_html_x('(Enable / Disable "Add to Cart"-Button on Shop Pages)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_cart_button',
				'type'        => 'checkbox'
			));

			$this->wp_customize->add_setting('beautysalon_woocommerce_upsells', array(
				'default' => 0,
				'sanitize_callback' => 'beautysalon_sanitize_checkbox'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_upsells', array(
				'label'       => esc_html_x('Upsells Products', 'backend', 'beautysalon'),
				'description' => esc_html_x('(Enable / Disable to show upsells Products on Product Item Details)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_upsells',
				'type'        => 'checkbox'
			));
			$this->wp_customize->add_setting('beautysalon_woocommerce_related', array(
				'default' => 1,
				'sanitize_callback' => 'beautysalon_sanitize_checkbox'
			));
			$this->wp_customize->add_control('beautysalon_woocommerce_related', array(
				'label'       => esc_html_x('Related Products', 'backend', 'beautysalon'),
				'description' => esc_html_x('(Enable / Disable to show related Products on Product Item Details)', 'backend', 'beautysalon'),
				'section'     => 'woocommerce',
				'settings'    => 'beautysalon_woocommerce_related',
				'type'        => 'checkbox'
			));
		}



		// footer appearance
		$this->wp_customize->add_section('footer', array(
			'title' => esc_html_x('Footer', 'backend', 'beautysalon'),
			'description' => esc_html_x( 'All Beauty Salon theme specific footer settings.', 'backend', 'beautysalon' ),
			'priority' => 150
		));

		/*
		 * "go to top" link
		 */
		$this->wp_customize->add_setting('beautysalon_top_link', array(
			'default' => 0,
			'sanitize_callback' => 'beautysalon_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control($this->wp_customize, 'beautysalon_top_link',
	        array(
				'priority' => 3,
				'label'    => esc_html_x('Disable "Go to top" link', 'backend', 'beautysalon'),
				'section'  => 'footer',
				'settings' => 'beautysalon_top_link',
				'type'     => 'checkbox'
	        )
		));

		$this->wp_customize->add_setting('beautysalon_show_copyright_text', array(
			'default' => 0,
			'sanitize_callback' => 'beautysalon_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control($this->wp_customize, 'beautysalon_show_copyright_text',
	        array(
				'priority' => 4,
				'label'    => esc_html_x('Show Custom Copyright Text', 'backend', 'beautysalon'),
				'section'  => 'footer',
				'settings' => 'beautysalon_show_copyright_text',
				'type'     => 'checkbox',
	        )
		));
		
		//Footer Content
		$this->wp_customize->add_setting('beautysalon_custom_copyright_text', array(
			'default'           => 'Theme Designed by <a href="'.esc_url( esc_html_x( 'https://www.bdthemes.com', 'backend', 'beautysalon')).' ">BdThemes Ltd</a>',
			'sanitize_callback' => 'beautysalon_sanitize_textarea'
		));
		$this->wp_customize->add_control( new beautysalon_Customize_Textarea_Control( $this->wp_customize, 'beautysalon_custom_copyright_text', array(
			'priority' => 5,
			'label'    => esc_html_x('Copyright Text', 'backend', 'beautysalon'),
			'section'  => 'footer',
			'settings' => 'beautysalon_custom_copyright_text',
			'type'     => 'textarea',
		)));

		if ( isset( $this->wp_customize->selective_refresh ) ) {
			$this->wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.tm-headerbar a.tm-logo h1',
				'container_inclusive' => false,
				'render_callback' => 'beautysalon_customize_partial_blogname',
			));
			$this->wp_customize->selective_refresh->add_partial( 'beautysalon_logo_upload', array(
				'selector' => '.tm-headerbar a.tm-logo',
				'container_inclusive' => false,
			));
			
			$this->wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'container_inclusive' => false,
				'render_callback' => 'beautysalon_customize_partial_blogdescription',
			));

			$this->wp_customize->selective_refresh->add_partial( 'beautysalon_show_copyright_text', array(
				'selector' => '.copyright-txt',
				'container_inclusive' => false,
			));
			

			$this->wp_customize->selective_refresh->add_partial( 'nav_menu_locations[main_menu]', array(
				'selector' => '.tm-headerbar .tm-navbar',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'nav_menu_locations[footer]', array(
				'selector' => '.tm-footer .tm-copyright-menu',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'beautysalon_top_link', array(
				'selector' => '.tm-totop-scroller',
				'container_inclusive' => false,
			));

		}


	}


	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @since Megastar 1.0
	 * @see beautysalon_customize_register_colors()
	 *
	 * @return void
	 */
	public function beautysalon_customize_partial_blogname() {
		bloginfo( 'name' );
	}

	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @since Megastar 1.0
	 * @see beautysalon_customize_register_colors()
	 *
	 * @return void
	 */
	public function beautysalon_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}

	/**
	 * Get the dimensions of the logo image when the setting is saved
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_logo_img' , array( $this, 'beautysalon_save_logo_dimensions' ), 10, 1 );
	 *
	 * @return void
	 */
	public static function beautysalon_save_logo_dimensions( $setting ) {
		$logo_width_height = '';
		$img_data          = getimagesize( esc_url( $setting->post_value() ) );

		if ( is_array( $img_data ) ) {
			$logo_width_height = $img_data[3];
		}

		set_theme_mod( 'logo_width_height', $logo_width_height );
	}

}

