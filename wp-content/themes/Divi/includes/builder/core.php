<?php

define( 'ET_BUILDER_ENABLE_BFB_OPTIN_MODAL', false );


if ( ! function_exists( 'et_builder_add_filters' ) ):
/**
 * Add common filters depending on what builder is being used.
 * These hooks are not used in DBP as it has its own implementations for them.
 *
 * @return void
 */
function et_builder_add_filters() {
	if ( et_is_builder_plugin_active() ) {
		return;
	}

	add_filter( 'et_builder_bfb_enabled', 'et_builder_filter_bfb_enabled' );
	add_filter( 'et_builder_is_fresh_install', 'et_builder_filter_is_fresh_install' );
	add_action( 'et_builder_toggle_bfb', 'et_builder_action_toggle_bfb' );

	add_filter( 'et_builder_show_bfb_optin_modal', 'et_builder_filter_show_bfb_optin_modal' );
	add_action( 'et_builder_bfb_optin_modal_shown', 'et_builder_action_bfb_optin_modal_shown' );
	add_action( 'et_builder_queue_bfb_optin_modal', 'et_builder_action_queue_bfb_optin_modal' );
}
endif;
add_action( 'init', 'et_builder_add_filters' );

if ( ! function_exists( 'et_builder_should_load_framework' ) ):
function et_builder_should_load_framework() {
	global $pagenow;
	// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

	static $should_load = null;

	if ( null !== $should_load ) {
		return $should_load;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return $should_load = true;
	}

	$is_admin = is_admin();
	$required_admin_pages = array( 'edit.php', 'post.php', 'post-new.php', 'admin.php', 'customize.php', 'edit-tags.php', 'admin-ajax.php', 'export.php', 'options-permalink.php', 'themes.php', 'revision.php' ); // list of admin pages where we need to load builder files
	$specific_filter_pages = array( 'edit.php', 'admin.php', 'edit-tags.php' ); // list of admin pages where we need more specific filtering

	$is_edit_library_page = 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'];
	$is_role_editor_page = 'admin.php' === $pagenow && isset( $_GET['page'] ) && apply_filters( 'et_divi_role_editor_page', 'et_divi_role_editor' ) === $_GET['page'];
	$is_import_page = 'admin.php' === $pagenow && isset( $_GET['import'] ) && 'wordpress' === $_GET['import']; // Page Builder files should be loaded on import page as well to register the et_pb_layout post type properly
	$is_wpml_page = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'sitepress-multilingual-cms/menu/languages.php' === $_GET['page']; // Page Builder files should be loaded on WPML clone page as well to register the custom taxonomies properly
	$is_edit_layout_category_page = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && ( 'layout_category' === $_GET['taxonomy'] || 'layout_pack' === $_GET['taxonomy'] );

	if ( ! $is_admin || ( $is_admin && in_array( $pagenow, $required_admin_pages ) && ( ! in_array( $pagenow, $specific_filter_pages ) || $is_edit_library_page || $is_role_editor_page || $is_edit_layout_category_page || $is_import_page || $is_wpml_page ) ) ) {
		$should_load = true;
	} else {
		$should_load = false;
	}

	/**
	 * Filters whether or not the Divi Builder codebase should be loaded for the current request.
	 *
	 * @since 3.0.99
	 *
	 * @param bool $should_load
	 */
	$should_load = apply_filters( 'et_builder_should_load_framework', $should_load );

	return $should_load;
	// phpcs:enable
}
endif;

if ( et_builder_should_load_framework() ) {
	// Initialize the Divi Library
	require_once ET_BUILDER_DIR . 'feature/Library.php';
}

if ( ! function_exists( 'et_builder_maybe_enable_inline_styles' ) ):
function et_builder_maybe_enable_inline_styles() {
	et_update_option( 'static_css_custom_css_safety_check_done', true );

	if ( ! wp_get_custom_css() ) {
		return;
	}

	// This site has Custom CSS that existed prior to v3.0.54 which could contain syntax
	// errors that the user is unaware of. Such errors would cause problems in a unified
	// static CSS file so let's enable inline styles for the builder's design styles.
	et_update_option( 'et_pb_css_in_footer', 'on' );
}
endif;

if ( defined( 'ET_CORE_UPDATED' ) && ! et_get_option( 'static_css_custom_css_safety_check_done', false ) ) {
	et_builder_maybe_enable_inline_styles();
}

function et_pb_video_get_oembed_thumbnail() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$video_url = esc_url( $_POST['et_video_url'] );
	if ( false !== wp_oembed_get( $video_url ) ) {
		// Get image thumbnail
		add_filter( 'oembed_dataparse', 'et_pb_video_oembed_data_parse', 10, 3 );
		// Save thumbnail
		$image_src = wp_oembed_get( $video_url );
		// Set back to normal
		remove_filter( 'oembed_dataparse', 'et_pb_video_oembed_data_parse', 10, 3 );
		if ( '' === $image_src ) {
			die( -1 );
		}
		echo esc_url( $image_src );
	} else {
		die( -1 );
	}
	die();
}
add_action( 'wp_ajax_et_pb_video_get_oembed_thumbnail', 'et_pb_video_get_oembed_thumbnail' );

if ( ! function_exists( 'et_pb_video_oembed_data_parse' ) ) :
function et_pb_video_oembed_data_parse( $return, $data, $url ) {
	if ( isset( $data->thumbnail_url ) ) {
		return esc_url( str_replace( array('https://', 'http://'), '//', $data->thumbnail_url ), array('http') );
	} else {
		return false;
	}
}
endif;

function et_pb_add_widget_area(){
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	$et_pb_widgets = get_theme_mod( 'et_pb_widgets' );

	$number = $et_pb_widgets ? intval( $et_pb_widgets['number'] ) + 1 : 1;

	$et_widget_area_name = sanitize_text_field( $_POST['et_widget_area_name'] );
	$et_pb_widgets['areas']['et_pb_widget_area_' . $number] = $et_widget_area_name;
	$et_pb_widgets['number'] = $number;

	set_theme_mod( 'et_pb_widgets', $et_pb_widgets );

	et_pb_force_regenerate_templates();

	printf( et_get_safe_localization( __( '<strong>%1$s</strong> widget area has been created. You can create more areas, once you finish update the page to see all the areas.', 'et_builder' ) ),
		esc_html( $et_widget_area_name )
	);

	die();
}
add_action( 'wp_ajax_et_pb_add_widget_area', 'et_pb_add_widget_area' );

function et_pb_remove_widget_area(){
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	$et_pb_widgets = get_theme_mod( 'et_pb_widgets' );

	$et_widget_area_name = sanitize_text_field( $_POST['et_widget_area_name'] );
	unset( $et_pb_widgets['areas'][ $et_widget_area_name ] );

	set_theme_mod( 'et_pb_widgets', $et_pb_widgets );

	et_pb_force_regenerate_templates();

	die( esc_html( $et_widget_area_name ) );
}
add_action( 'wp_ajax_et_pb_remove_widget_area', 'et_pb_remove_widget_area' );

function et_pb_current_user_can_lock() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$permission = et_pb_is_allowed( 'lock_module' );
	$permission = wp_json_encode( (bool) $permission );

	die( et_core_esc_previously( $permission ) );
}
add_action( 'wp_ajax_et_pb_current_user_can_lock', 'et_pb_current_user_can_lock' );

/**
 * Get the supported post types by default.
 *
 * @since 3.10
 *
 * @return array<string>
 */
function et_builder_get_default_post_types() {
	return array(
		// WordPress:
		'page',
		'post',

		// Divi/Extra/DBP:
		'project',
		'et_pb_layout',
	);
}

/**
 * Get the supported third party post types.
 *
 * @since 3.10
 *
 * @return array<string>
 */
function et_builder_get_third_party_post_types() {
	/**
	 * Array of third-party registered post types that should have support enabled by default.
	 *
	 * @since 3.10
	 *
	 * @param array<string>
	 */
	return apply_filters( 'et_builder_third_party_post_types', array(
		// WooCommerce (https://wordpress.org/plugins/woocommerce/):
		'product',

		// The Events Calendar (https://wordpress.org/plugins/the-events-calendar/):
		'tribe_events',

		// Popup Maker (https://wordpress.org/plugins/popup-maker/):
		'popup',

		// All-in-One Event Calendar (https://wordpress.org/plugins/all-in-one-event-calendar/):
		'ai1ec_event',

		// Events Manager (https://wordpress.org/plugins/events-manager/):
		'event',
		'location',

		// Portfolio Post Type (https://wordpress.org/plugins/portfolio-post-type/):
		'portfolio',

		// LifterLMS (https://wordpress.org/plugins/lifterlms/):
		'course',

		// LearnDash (https://www.learndash.com/wordpress-course-plugin-features/):
		'sfwd-courses',
		'sfwd-lessons',
	) );
}

/**
 * Get the list of unsupported Post Types.
 *
 * @since 3.10
 *
 * @return array
 */
function et_builder_get_blacklisted_post_types() {
	return apply_filters( 'et_builder_post_type_blacklist', array(
		// LearnDash
		'sfwd-essays',

		// bbPress:
		'forum',
		'topic',
		'reply',
	) );
}

/**
 * Check whether the supplied post type is a custom post type as far as the builder is concerned.
 *
 * @since 3.10
 *
 * @param string $post_type
 *
 * @return boolean
 */
function et_builder_is_post_type_custom( $post_type ) {
	return $post_type && ! in_array( $post_type, et_builder_get_default_post_types() );
}

/**
 * Check whether the supplied post is of a custom post type as far as the builder is concerned.
 * If no post id is supplied, checks whether the current page is the singular view of a custom post type.
 *
 * @since 3.10
 *
 * @param integer $post_id
 *
 * @return boolean
 */
function et_builder_post_is_of_custom_post_type( $post_id = 0 ) {
	$post_types = et_builder_get_default_post_types();

	if ( $post_id === 0 ) {
		return is_singular() && ! in_array( get_post_type( get_the_ID() ), $post_types );
	}

	return et_builder_is_post_type_custom( get_post_type( $post_id ) );
}

/**
 * Get an array of post types the Divi Builder is enabled on.
 *
 * @since 3.10
 *
 * @return string[]
 */
function et_builder_get_enabled_builder_post_types() {
	$default = array_merge(
		et_builder_get_default_post_types(),
		et_builder_get_third_party_post_types()
	);

	/**
	 * Filter the array of enabled post type options.
	 * Allows Divi/Extra/DBP to only supply their option value in order to reduce code duplication.
	 *
	 * Schema:
	 *     array(
	 *         'post_type_name' => <'on' or 'off'>,
	 *         // ...
	 *     )
	 *
	 * @since 3.10
	 *
	 * @param array<string, string> $options
	 *
	 * @return array<string, string>
	 */
	$options = apply_filters( 'et_builder_enabled_builder_post_type_options', array() );

	foreach ( $default as $post_type ) {
		if ( ! isset( $options[ $post_type ] ) ) {
			$options[ $post_type ] = 'on';
		}
	}

	$filtered = array();

	foreach ( $options as $post_type => $state ) {
		if ( 'on' === $state && ! in_array( $post_type, et_builder_get_blacklisted_post_types() ) ) {
			$filtered[] = $post_type;
		}
	}

	return $filtered;
}

function et_builder_get_builder_post_types() {
	/**
	 * Array of post types which have the builder enabled.
	 *
	 * @since 3.10
	 *
	 * @param array<string>
	 */
	return apply_filters( 'et_builder_post_types', et_builder_get_enabled_builder_post_types() );
}

function et_builder_get_fb_post_types() {
	/**
	 * Array of post types which have the frontend builder enabled.
	 *
	 * @since 3.10
	 *
	 * @param array<string>
	 */
	return apply_filters( 'et_fb_post_types', et_builder_get_enabled_builder_post_types() );
}

/**
 * Check whether the specified post can have the builder enabled.
 *
 * @since 3.10
 *
 * @param integer $post_id
 *
 * @return boolean
 */
function et_builder_enabled_for_post( $post_id ) {
	if ( et_pb_is_pagebuilder_used( $post_id ) ) {
		return true;
	}

	return et_builder_enabled_for_post_type( get_post_type( $post_id ) );
}

/**
 * Check whether the specified post type can have the builder enabled.
 *
 * @since 3.10
 *
 * @param string $post_type
 *
 * @return boolean
 */
function et_builder_enabled_for_post_type( $post_type ) {
	return in_array( $post_type, et_builder_get_builder_post_types() );
}

/**
 * Check whether the specified post can have the FB enabled.
 *
 * @since 3.10
 *
 * @param string $post_type
 *
 * @return boolean
 */
function et_builder_fb_enabled_for_post( $post_id ) {
	$post_type            = get_post_type( $post_id );
	$enabled              = false;
	$pto                  = get_post_type_object( $post_type );
	$is_default_post_type = in_array( $post_type, et_builder_get_default_post_types() );
	$is_public_post_type  = et_builder_is_post_type_public( $post_type );

	if ( $pto && ( $is_default_post_type || $is_public_post_type ) ) {
		$enabled = et_builder_enabled_for_post( $post_id );
	}

	/**
	 * Filter whether the FB is enabled for a given post.
	 *
	 * @since 3.10
	 *
	 * @param boolean $enabled
	 * @param integer $post_id
	 */
	$enabled = apply_filters( 'et_builder_fb_enabled_for_post', $enabled, $post_id );

	return $enabled;
}

/**
 * Check whether the specified post type is public.
 *
 * @since 3.10
 *
 * @param string $post_type
 *
 * @return boolean
 */
function et_builder_is_post_type_public( $post_type ) {
	$pto = get_post_type_object( $post_type );

	// Note: the page post type is not publicly_queryable but we should treat it as such.
	return ( $pto && ( $pto->publicly_queryable || $pto->name === 'page' ) );
}

function et_is_extra_library_layout( $post_id ) {
	return 'layout' === get_post_type( $post_id );
}

/**
 * Check whether the specified capability allowed for current user
 * @return bool
 */
function et_pb_is_allowed( $capabilities, $role = '' ) {
	$saved_capabilities = et_pb_get_role_settings();
	$test_current_user  = '' === $role;
	$role               = $test_current_user ? et_pb_get_current_user_role() : $role;

	if ( ! $role ) {
		return false;
	}

	// Disable certain capabilities for non-administrators by default.
	$dangerous          = array( 'read_dynamic_content_custom_fields' );

	foreach ( (array) $capabilities as $capability ) {
		$is_dangerous = in_array( $capability, $dangerous, true );

		if ( $test_current_user && $is_dangerous && is_multisite() && is_super_admin() ) {
			// Super admins always have access to dangerous capabilities and that cannot be
			// changed in the role editor.
			return true;
		}

		if ( ! empty( $saved_capabilities[ $role ][ $capability ] ) ) {
			return 'on' === $saved_capabilities[ $role ][ $capability ];
		}

		if ( $is_dangerous && 'administrator' !== $role ) {
			// Admins have access to dangerous capabilities by default, but that can be
			// changed in the role editor.
			return false;
		}
	}

	return true;
}

/**
 * Gets the array of role settings
 * @return array
 */
function et_pb_get_role_settings() {
	global $et_pb_role_settings;

	// if we don't have saved global variable, then get the value from WPDB
	$et_pb_role_settings = isset( $et_pb_role_settings ) ? $et_pb_role_settings : get_option( 'et_pb_role_settings', array() );

	return $et_pb_role_settings;
}

/**
 * Determines the current user role
 * @return string
 */
function et_pb_get_current_user_role() {
	$current_user = wp_get_current_user();
	$user_roles = $current_user->roles;

	// retrieve the role from array if exists or determine it using custom mechanism
	// $user_roles array may start not from 0 index. Use reset() to retrieve the first value from array regardless its index
	$role = ! empty( $user_roles ) ? reset( $user_roles ) : et_pb_determine_current_user_role();

	return $role;
}

/**
 * Generate the list of all roles ( with editing permissions ) registered in current WP
 * @return string
 */
function et_pb_get_all_roles_list() {
	// get all roles registered in current WP
	if ( ! function_exists( 'get_editable_roles' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/user.php' );
	}

	$all_roles = get_editable_roles();
	$builder_roles_array = array();

	if ( ! empty( $all_roles ) ) {
		foreach( $all_roles as $role => $role_data ) {
			// add roles with edit_posts capability into $builder_roles_array
			if ( ! empty( $role_data['capabilities']['edit_posts'] ) && 1 === (int) $role_data['capabilities']['edit_posts'] ) {
				$builder_roles_array[ $role ] = $role_data['name'];
			}
		}
	}

	// fill the builder roles array with default roles if it's empty
	if ( empty( $builder_roles_array ) ) {
		$builder_roles_array = array(
			'administrator' => esc_html__( 'Administrator', 'et_builder' ),
			'editor'        => esc_html__( 'Editor', 'et_builder' ),
			'author'        => esc_html__( 'Author', 'et_builder' ),
			'contributor'   => esc_html__( 'Contributor', 'et_builder' ),
		);
	}

	return $builder_roles_array;
}

/**
 * Determine the current user role by checking every single registered role via current_user_can()
 * @return string
 */
function et_pb_determine_current_user_role() {
	$all_roles = et_pb_get_all_roles_list();

	// go through all the registered roles and return the one current user have
	foreach( $all_roles as $role => $role_data ) {
		if ( current_user_can( $role ) ) {
			return $role;
		}
	}
}

function et_pb_show_all_layouts_built_for_post_type( $post_type ) {
	$similar_post_types = array(
		'post',
		'page',
		'project',
	);

	if ( in_array( $post_type, $similar_post_types ) ) {
		return $similar_post_types;
	}

	return $post_type;
}
add_filter( 'et_pb_show_all_layouts_built_for_post_type', 'et_pb_show_all_layouts_built_for_post_type' );

function et_pb_show_all_layouts() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	printf( '
		<label for="et_pb_load_layout_replace">
			<input name="et_pb_load_layout_replace" type="checkbox" id="et_pb_load_layout_replace" %2$s/>
			<span>%1$s</span>
		</label>',
		esc_html__( 'Replace the existing content with loaded layout', 'et_builder' ),
		checked( get_theme_mod( 'et_pb_replace_content', 'on' ), 'on', false )
	);

	$post_type = ! empty( $_POST['et_layouts_built_for_post_type'] ) ? sanitize_text_field( $_POST['et_layouts_built_for_post_type'] ) : 'post';
	$layouts_type = ! empty( $_POST['et_load_layouts_type'] ) ? sanitize_text_field( $_POST['et_load_layouts_type'] ) : 'predefined';

	$predefined_operator = 'predefined' === $layouts_type ? 'EXISTS' : 'NOT EXISTS';

	$post_type = apply_filters( 'et_pb_show_all_layouts_built_for_post_type', $post_type, $layouts_type );

	$query_args = array(
		'meta_query'      => array(
			'relation' => 'AND',
			array(
				'key'     => '_et_pb_predefined_layout',
				'value'   => 'on',
				'compare' => $predefined_operator,
			),
			array(
				'key'     => '_et_pb_built_for_post_type',
				'value'   => $post_type,
				'compare' => 'IN',
			),
			array(
				'key'     => '_et_pb_layout_applicability',
				'value'   => 'product_tour',
				'compare' => 'NOT EXISTS',
			),
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'layout_type',
				'field'    => 'slug',
				'terms'    => array( 'section', 'row', 'module', 'fullwidth_section', 'specialty_section', 'fullwidth_module' ),
				'operator' => 'NOT IN',
			),
		),
		'post_type'       => ET_BUILDER_LAYOUT_POST_TYPE,
		'posts_per_page'  => '-1',
		'suppress_filters' => 'predefined' === $layouts_type,
	);

	$query = new WP_Query( $query_args );

	if ( $query->have_posts() ) :

		echo '<ul class="et-pb-all-modules et-pb-load-layouts">';

		while ( $query->have_posts() ) : $query->the_post();

			$button_html = 'predefined' !== $layouts_type ?
				sprintf( '<a href="#" class="button et_pb_layout_button_delete">%1$s</a>',
					esc_html__( 'Delete', 'et_builder' )
				)
				: '';

			printf( '<li class="et_pb_text" data-layout_id="%2$s">%1$s<span class="et_pb_layout_buttons"><a href="#" class="button-primary et_pb_layout_button_load">%3$s</a>%4$s</span></li>',
				esc_html( get_the_title() ),
				esc_attr( get_the_ID() ),
				esc_html__( 'Load', 'et_builder' ),
				et_core_esc_previously( $button_html )
			);

		endwhile;

		echo '</ul>';
	endif;

	wp_reset_postdata();

	die();
}
add_action( 'wp_ajax_et_pb_show_all_layouts', 'et_pb_show_all_layouts' );

function et_pb_get_saved_templates() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$layout_type = ! empty( $_POST['et_layout_type'] ) ? sanitize_text_field( $_POST['et_layout_type'] ) : 'layout';
	$module_width = ! empty( $_POST['et_module_width'] ) && 'module' === $layout_type ? sanitize_text_field( $_POST['et_module_width'] ) : '';
	$is_global = ! empty( $_POST['et_is_global'] ) ? sanitize_text_field( $_POST['et_is_global'] ) : 'false';
	$specialty_query = ! empty( $_POST['et_specialty_columns'] ) && 'row' === $layout_type ? sanitize_text_field( $_POST['et_specialty_columns'] ) : '0';
	$post_type = ! empty( $_POST['et_post_type'] ) ? sanitize_text_field( $_POST['et_post_type'] ) : 'post';

	$templates_data = et_pb_retrieve_templates( $layout_type, $module_width, $is_global, $specialty_query, $post_type );

	if ( empty( $templates_data ) ) {
		$templates_data = array( 'error' => esc_html__( 'You have not saved any items to your Divi Library yet. Once an item has been saved to your library, it will appear here for easy use.', 'et_builder' ) );
	}

	$json_templates = wp_json_encode( $templates_data );

	die( et_core_esc_previously( $json_templates ) );
}
add_action( 'wp_ajax_et_pb_get_saved_templates', 'et_pb_get_saved_templates' );

/**
 * Retrieves saved builder layouts.
 *
 * @since 2.0
 *
 * @param string $layout_type     Accepts 'section', 'row', 'module', 'fullwidth_section',
 *                                'specialty_section', 'fullwidth_module'.
 * @param string $module_width    Accepts 'regular', 'fullwidth'.
 * @param string $is_global       Filter layouts based on their scope. Accepts 'global' to include
 *                                only global layouts, 'false' to include only non-global layouts,
 *                                or 'all' to include both global and non-global layouts.
 * @param string $specialty_query Limit results to layouts of type 'row' that can be put inside
 *                                specialty sections. Accepts '3' to include only 3-column rows,
 *                                '2' for 2-column rows, or '0' to disable the specialty query. Default '0'.
 * @param string $post_type       Limit results to layouts built for this post type.
 * @param string $deprecated      Deprecated.
 * @param array  $boundaries      {
 *
 *     Return a subset of the total results.
 *
 *     @type int $offset Start from this point in the results. Default `0`.
 *     @type int $limit  Maximum number of results to return. Default `-1`.
 * }
 *
 * @return array[] $layouts {
 *
 *     @type mixed[] {
 *
 *         Layout Data
 *
 *         @type int      $ID               The layout's post id.
 *         @type string   $title            The layout's title/name.
 *         @type string   $shortcode        The layout's shortcode content.
 *         @type string   $is_global        The layout's scope. Accepts 'global', 'non_global'.
 *         @type string   $layout_type      The layout's type. See {@see self::$layout_type} param for accepted values.
 *         @type string   $applicability    The layout's applicability.
 *         @type string   $layouts_type     Deprecated. Will always be 'library'.
 *         @type string   $module_type      For layouts of type 'module', the module type/slug (eg. et_pb_blog).
 *         @type string[] $categories       This layout's assigned categories (slugs).
 *         @type string   $row_layout       For layout's of type 'row', the row layout type (eg. 4_4).
 *         @type mixed[]  $unsynced_options For global layouts, the layout's unsynced settings.
 *     }
 *     ...
 * }
 */
function et_pb_retrieve_templates( $layout_type = 'layout', $module_width = '', $is_global = 'false', $specialty_query = '0', $post_type = 'post', $deprecated = '', $boundaries = array() ) {
	$templates_data         = array();
	$suppress_filters       = false;
	$extra_layout_post_type = 'layout';
	$module_icons           = ET_Builder_Element::get_module_icons();
	$utils                  = ET_Core_Data_Utils::instance();
	$similar_post_types     = array_keys(ET_Builder_Settings::get_registered_post_type_options());

	// All default and 3rd party post types considered similar and share the same library items, so retrieve all items for any post type from the list
	$post_type = in_array($post_type, $similar_post_types) ? $similar_post_types : $post_type;

	// need specific query for the layouts
	if ( 'layout' === $layout_type ) {

		if ( 'all' === $post_type ) {
			$meta_query = array(
				'relation' => 'AND',
				array(
					'key'     => '_et_pb_built_for_post_type',
					'value'   => $extra_layout_post_type,
					'compare' => 'NOT IN',
				),
			);
		} else {
			$meta_query = array(
				'relation' => 'AND',
				array(
					'key'     => '_et_pb_built_for_post_type',
					'value'   => $post_type,
					'compare' => 'IN',
				),
			);
		}

		$tax_query = array(
			array(
				'taxonomy' => 'layout_type',
				'field'    => 'slug',
				'terms'    => array( 'section', 'row', 'module', 'fullwidth_section', 'specialty_section', 'fullwidth_module' ),
				'operator' => 'NOT IN',
			),
		);
		$suppress_filters = 'predefined' === $layout_type;
	} else {
		$additional_condition = '' !== $module_width ?
			array(
					'taxonomy' => 'module_width',
					'field'    => 'slug',
					'terms'    =>  $module_width,
				) : '';

		$meta_query = array();

		if ( '0' !== $specialty_query ) {
			$columns_val = '3' === $specialty_query ? array( '4_4', '1_2,1_2', '1_3,1_3,1_3' ) : array( '4_4', '1_2,1_2' );
			$meta_query[] = array(
				'key'     => '_et_pb_row_layout',
				'value'   => $columns_val,
				'compare' => 'IN',
			);
		}

		$post_type = apply_filters( 'et_pb_show_all_layouts_built_for_post_type', $post_type, $layout_type );
		$meta_query[] = array(
			'key'     => '_et_pb_built_for_post_type',
			'value'   => $post_type,
			'compare' => 'IN',
		);

		$tax_query = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'layout_type',
				'field'    => 'slug',
				'terms'    =>  $layout_type,
			),
			$additional_condition,
		);

		if ( 'all' !== $is_global ) {
			$global_operator = 'global' === $is_global ? 'IN' : 'NOT IN';
			$tax_query[] = array(
				'taxonomy' => 'scope',
				'field'    => 'slug',
				'terms'    => array( 'global' ),
				'operator' => $global_operator,
			);
		}
	}

	$start_from = 0;
	$limit_to = '-1';

	if ( ! empty( $boundaries ) ) {
		$start_from = $boundaries[0];
		$limit_to = $boundaries[1];
	}

	$query = new WP_Query( array(
		'tax_query'        => $tax_query,
		'post_type'        => ET_BUILDER_LAYOUT_POST_TYPE,
		'posts_per_page'   => $limit_to,
		'meta_query'       => $meta_query,
		'offset'           => $start_from,
		'suppress_filters' => $suppress_filters,
	) );

	wp_reset_postdata();

	if ( ! empty ( $query->posts ) ) {
		foreach( $query->posts as $single_post ) {

			if ( 'module' === $layout_type ) {
				$module_type = get_post_meta( $single_post->ID, '_et_pb_module_type', true );
			} else {
				$module_type = '';
			}

			// add only modules allowed for current user
			if ( '' === $module_type || et_pb_is_allowed( $module_type ) ) {
				$categories = wp_get_post_terms( $single_post->ID, 'layout_category' );
				$scope = wp_get_post_terms( $single_post->ID, 'scope' );
				$global_scope = isset( $scope[0] ) ? $scope[0]->slug : 'non_global';
				$categories_processed = array();
				$row_layout = '';
				$this_layout_type = '';
				$this_layout_applicability = '';

				if ( ! empty( $categories ) ) {
					foreach( $categories as $category_data ) {
						$categories_processed[] = esc_html( $category_data->slug );
					}
				}

				if ( 'row' === $layout_type ) {
					$row_layout = get_post_meta( $single_post->ID, '_et_pb_row_layout', true );
				}

				if ( 'layout' === $layout_type ) {
					$this_layout_type = 'on' === get_post_meta( $single_post->ID, '_et_pb_predefined_layout', true ) ? 'predefined' : 'library';
					$this_layout_applicability = get_post_meta( $single_post->ID, '_et_pb_layout_applicability', true );
				}

				// get unsynced global options for module
				if ( 'module' === $layout_type && 'false' !== $is_global ) {
					$unsynced_options = get_post_meta( $single_post->ID, '_et_pb_excluded_global_options' );
				}

				$templates_datum = array(
					'ID'               => (int) $single_post->ID,
					'title'            => esc_html( $single_post->post_title ),
					'shortcode'        => et_core_intentionally_unescaped( $single_post->post_content, 'html' ),
					'is_global'        => esc_html( $global_scope ),
					'layout_type'      => esc_html( $layout_type ),
					'applicability'    => esc_html( $this_layout_applicability ),
					'layouts_type'     => esc_html( $this_layout_type ),
					'module_type'      => esc_html( $module_type ),
					'categories'       => et_core_esc_previously( $categories_processed ),
					'row_layout'       => esc_html( $row_layout ),
					'unsynced_options' => ! empty( $unsynced_options ) ? $utils->esc_array( json_decode( $unsynced_options[0], true ), 'sanitize_text_field' ) : array(),
				);

				// Append icon if there's any
				if ( $module_type && $template_icon = $utils->array_get( $module_icons, "{$module_type}.icon", false ) ) {
					$templates_datum['icon'] = $template_icon;
				}

				// Append svg icon if there's any
				if ( $module_type && $template_icon_svg = $utils->array_get( $module_icons, "{$module_type}.icon_svg", false ) ) {
					$templates_datum['icon_svg'] = $template_icon_svg;
				}

				$templates_data[] = $templates_datum;
			}
		}
	}

	return $templates_data;
}


function et_pb_add_template_meta() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	$post_id = ! empty( $_POST['et_post_id'] ) ? sanitize_text_field( $_POST['et_post_id'] ) : '';

	if ( empty( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		die( -1 );
	}

	$value = ! empty( $_POST['et_meta_value'] ) ? sanitize_text_field( $_POST['et_meta_value'] ) : '';
	$custom_field = ! empty( $_POST['et_custom_field'] ) ? sanitize_text_field( $_POST['et_custom_field'] ) : '';

	$whitelisted_meta_keys = array(
		'_et_pb_row_layout',
		'_et_pb_module_type',
	);

	if ( in_array( $custom_field, $whitelisted_meta_keys ) ) {
		update_post_meta( $post_id, $custom_field, $value );
	}
}
add_action( 'wp_ajax_et_pb_add_template_meta', 'et_pb_add_template_meta' );

if ( ! function_exists( 'et_pb_add_new_layout' ) ) {
	function et_pb_add_new_layout() {
		if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
			die( -1 );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			die( -1 );
		}

		$fields_data = isset( $_POST['et_layout_options'] ) ? trim( $_POST['et_layout_options'] ) : '';

		if ( empty( $fields_data ) ) {
			die();
		}

		$fields_data_json = str_replace( '\\', '',  $fields_data );
		$fields_data_array = json_decode( $fields_data_json, true );
		$processed_data_array = array();

		// prepare array with fields data in convenient format
		if ( ! empty( $fields_data_array ) ) {
			foreach ( $fields_data_array as $index => $field_data ) {
				$processed_data_array[ $field_data['field_id'] ] = $field_data['field_val'];
			}
		}

		$processed_data_array = apply_filters( 'et_pb_new_layout_data_from_form', $processed_data_array, $fields_data_array );

		if ( empty( $processed_data_array ) ) {
			die();
		}

		$args = array(
			'layout_type'          => ! empty( $processed_data_array['new_template_type'] ) ? sanitize_text_field( $processed_data_array['new_template_type'] ) : 'layout',
			'layout_selected_cats' => ! empty( $processed_data_array['selected_cats'] ) ? sanitize_text_field( $processed_data_array['selected_cats'] ) : '',
			'built_for_post_type'  => ! empty( $processed_data_array['et_builder_layout_built_for_post_type'] ) ? sanitize_text_field( $processed_data_array['et_builder_layout_built_for_post_type'] ) : 'page',
			'layout_new_cat'       => ! empty( $processed_data_array['et_pb_new_cat_name'] ) ? sanitize_text_field( $processed_data_array['et_pb_new_cat_name'] ) : '',
			'columns_layout'       => ! empty( $processed_data_array['et_columns_layout'] ) ? sanitize_text_field( $processed_data_array['et_columns_layout'] ) : '0',
			'module_type'          => ! empty( $processed_data_array['et_module_type'] ) ? sanitize_text_field( $processed_data_array['et_module_type'] ) : 'et_pb_unknown',
			'layout_scope'         => ! empty( $processed_data_array['et_pb_template_global'] ) ? sanitize_text_field( $processed_data_array['et_pb_template_global'] ) : 'not_global',
			'module_width'         => 'regular',
			'layout_content'       => ! empty( $processed_data_array['template_shortcode'] ) ? $processed_data_array['template_shortcode'] : '',
			'layout_name'          => ! empty( $processed_data_array['et_pb_new_template_name'] ) ? sanitize_text_field( $processed_data_array['et_pb_new_template_name'] ) : '',
		);

		// construct the initial shortcode for new layout
		switch ( $args['layout_type'] ) {
			case 'row' :
				$args['layout_content'] = '[et_pb_row template_type="row"][/et_pb_row]';
				break;
			case 'section' :
				$args['layout_content'] = '[et_pb_section template_type="section"][et_pb_row][/et_pb_row][/et_pb_section]';
				break;
			case 'module' :
				$args['layout_content'] = '[et_pb_module_placeholder selected_tabs="all"]';
				break;
			case 'fullwidth_module' :
				$args['layout_content'] = '[et_pb_fullwidth_module_placeholder selected_tabs="all"]';
				$args['module_width'] = 'fullwidth';
				$args['layout_type'] = 'module';
				break;
			case 'fullwidth_section' :
				$args['layout_content'] = '[et_pb_section template_type="section" fullwidth="on"][/et_pb_section]';
				$args['layout_type'] = 'section';
				break;
			case 'specialty_section' :
				$args['layout_content'] = '[et_pb_section template_type="section" specialty="on" skip_module="true" specialty_placeholder="true"][/et_pb_section]';
				$args['layout_type'] = 'section';
				break;
		}

		$new_layout_meta = et_pb_submit_layout( apply_filters( 'et_pb_new_layout_args', $args ) );
		die( et_core_esc_previously( $new_layout_meta ) );
	}
}
add_action( 'wp_ajax_et_pb_add_new_layout', 'et_pb_add_new_layout' );

if ( ! function_exists( 'et_pb_submit_layout' ) ):
/**
 * Handles saving layouts to the database for the builder. Essentially just a wrapper for
 * {@see et_pb_create_layout()} that processes the data from the builder before passing it on.
 *
 * @since 1.0
 *
 * @param string[] $args {
 *     Layout Data
 *
 *     @type string $layout_type          Accepts 'layout', 'section', 'row', 'module'.
 *     @type string $layout_selected_cats Categories to which the layout should be added. This should
 *                                        be one or more IDs separated by pipe symbols. Example: '1|2|3'.
 *     @type string $built_for_post_type  The post type for which the layout was built.
 *     @type string $layout_new_cat       Name of a new category to which the layout should be added.
 *     @type string $columns_layout       When 'layout_type' is 'row', the row's columns structure. Example: '1_4'.
 *     @type string $module_type          When 'layout_type' is 'module', the module type. Example: 'et_pb_blurb'.
 *     @type string $layout_scope         Optional. The layout's scope. Accepts: 'global', 'not_global'.
 *     @type string $module_width         When 'layout_type' is 'module', the module's width. Accepts: 'regular', 'fullwidth'.
 *     @type string $layout_content       The layout's content (unprocessed shortcodes).
 *     @type string $layout_name          The layout's name.
 * }
 *
 * @return string $layout_data The 'post_id' and 'edit_link' for the saved layout (JSON encoded).
 */
function et_pb_submit_layout( $args ) {
	/**
	 * Filters the layout data passed to {@see et_pb_submit_layout()}.
	 *
	 * @since 3.0.99
	 *
	 * @param string[] $args See {@see et_pb_submit_layout()} for array structure definition.
	 */
	$args = apply_filters( 'et_pb_submit_layout_args', $args );

	if ( empty( $args ) ) {
		return '';
	}

	$layout_cats_processed = array();

	if ( '' !== $args['layout_selected_cats'] ) {
		$layout_cats_array = explode( ',', $args['layout_selected_cats'] );
		$layout_cats_processed = array_map( 'intval', $layout_cats_array );
	}

	$meta = array();

	if ( 'row' === $args['layout_type'] && '0' !== $args['columns_layout'] ) {
		$meta = array_merge( $meta, array( '_et_pb_row_layout' => $args['columns_layout'] ) );
	}

	if ( 'module' === $args['layout_type'] ) {
		$meta = array_merge( $meta, array( '_et_pb_module_type' => $args['module_type'] ) );

		// save unsynced options for global modules. Always empty for new modules.
		if ( 'global' === $args['layout_scope'] ) {
			$meta = array_merge( $meta, array( '_et_pb_excluded_global_options' => wp_json_encode( array() ) ) );
		}
	}

	//et_layouts_built_for_post_type
	$meta = array_merge( $meta, array( '_et_pb_built_for_post_type' => $args['built_for_post_type'] ) );

	$tax_input = array(
		'scope'           => $args['layout_scope'],
		'layout_type'     => $args['layout_type'],
		'module_width'    => $args['module_width'],
		'layout_category' => $layout_cats_processed,
	);

	$new_layout_id = et_pb_create_layout( $args['layout_name'], $args['layout_content'], $meta, $tax_input, $args['layout_new_cat'] );
	$new_post_data['post_id'] = (int) $new_layout_id;

	$new_post_data['edit_link'] = htmlspecialchars_decode( get_edit_post_link( $new_layout_id ) );
	$json_post_data = wp_json_encode( $new_post_data );

	return $json_post_data;
}
endif;

if ( ! function_exists( 'et_pb_create_layout' ) ) :
function et_pb_create_layout( $name, $content, $meta = array(), $tax_input = array(), $new_category = '' ) {
	$layout = array(
		'post_title'   => sanitize_text_field( $name ),
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => ET_BUILDER_LAYOUT_POST_TYPE,
	);

	$layout_id = wp_insert_post( $layout );

	if ( !empty( $meta ) ) {
		foreach ( $meta as $meta_key => $meta_value ) {
			add_post_meta( $layout_id, $meta_key, sanitize_text_field( $meta_value ) );
		}
	}
	if ( '' !== $new_category ) {
		$new_term_id = wp_insert_term( $new_category, 'layout_category' );
		$tax_input['layout_category'][] = (int) $new_term_id['term_id'];
	}

	if ( ! empty( $tax_input ) ) {
		foreach( $tax_input as $taxonomy => $terms ) {
			wp_set_post_terms( $layout_id, $terms, $taxonomy );
		}
	}

	return $layout_id;
}
endif;

function et_pb_save_layout() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	if ( empty( $_POST['et_layout_name'] ) ) {
		die();
	}

	$args = array(
		'layout_type'          => isset( $_POST['et_layout_type'] ) ? sanitize_text_field( $_POST['et_layout_type'] ) : 'layout',
		'layout_selected_cats' => isset( $_POST['et_layout_cats'] ) ? sanitize_text_field( $_POST['et_layout_cats'] ) : '',
		'built_for_post_type'  => isset( $_POST['et_post_type'] ) ? sanitize_text_field( $_POST['et_post_type'] ) : 'page',
		'layout_new_cat'       => isset( $_POST['et_layout_new_cat'] ) ? sanitize_text_field( $_POST['et_layout_new_cat'] ) : '',
		'columns_layout'       => isset( $_POST['et_columns_layout'] ) ? sanitize_text_field( $_POST['et_columns_layout'] ) : '0',
		'module_type'          => isset( $_POST['et_module_type'] ) ? sanitize_text_field( $_POST['et_module_type'] ) : 'et_pb_unknown',
		'layout_scope'         => isset( $_POST['et_layout_scope'] ) ? sanitize_text_field( $_POST['et_layout_scope'] ) : 'not_global',
		'module_width'         => isset( $_POST['et_module_width'] ) ? sanitize_text_field( $_POST['et_module_width'] ) : 'regular',
		'layout_content'       => isset( $_POST['et_layout_content'] ) ? $_POST['et_layout_content'] : '',
		'layout_name'          => isset( $_POST['et_layout_name'] ) ? sanitize_text_field( $_POST['et_layout_name'] ) : '',
	);

	$new_layout_meta = et_pb_submit_layout( $args );
	die( et_core_esc_previously( $new_layout_meta ) );
}
add_action( 'wp_ajax_et_pb_save_layout', 'et_pb_save_layout' );

function et_pb_get_global_module() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$global_shortcode = array();

	$utils = ET_Core_Data_Utils::instance();

	$post_id = isset( $_POST['et_global_id'] ) ? (int) $_POST['et_global_id'] : '';

	if ( empty( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		die( -1 );
	}

	$global_autop = isset( $_POST['et_global_autop'] ) ? sanitize_text_field( $_POST['et_global_autop'] ) : 'apply';

	if ( ! empty( $post_id ) ) {
		$query = new WP_Query( array(
			'p'         => $post_id,
			'post_type' => ET_BUILDER_LAYOUT_POST_TYPE,
		) );

		wp_reset_postdata();

		if ( !empty( $query->post ) ) {
			if ( 'skip' === $global_autop ) {
				$post_content = $query->post->post_content;
			} else {
				$post_content = $query->post->post_content;
				// do prep
				$post_content = et_pb_prep_code_module_for_wpautop( $post_content );

				// wpautop does its "thing"
				$post_content = wpautop( $post_content );

				// undo prep
				$post_content = et_pb_unprep_code_module_for_wpautop( $post_content );
			}

			$global_shortcode['shortcode'] = et_core_intentionally_unescaped( $post_content, 'html' );
			$excluded_global_options = get_post_meta( $post_id, '_et_pb_excluded_global_options' );
			$selective_sync_status = empty( $excluded_global_options ) ? '' : 'updated';

			$global_shortcode['sync_status'] = et_core_intentionally_unescaped( $selective_sync_status, 'fixed_string' );
			// excluded_global_options is an array with single value which is json string, so just `sanitize_text_field`, because `esc_html` converts quotes and breaks the json string.
			$global_shortcode['excluded_options'] = $utils->esc_array( $excluded_global_options, 'sanitize_text_field' );
		}
	}

	if ( empty( $global_shortcode ) ) {
		$global_shortcode['error'] = 'nothing';
	}

	$json_post_data = wp_json_encode( $global_shortcode );

	die( et_core_esc_previously( $json_post_data ) );
}
add_action( 'wp_ajax_et_pb_get_global_module', 'et_pb_get_global_module' );

function et_pb_update_layout() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$post_id = isset( $_POST['et_template_post_id'] ) ? absint( $_POST['et_template_post_id'] ) : '';
	$new_content = isset( $_POST['et_layout_content'] ) ? $_POST['et_layout_content'] : '';
	$layout_type = isset( $_POST['et_layout_type'] ) ? sanitize_text_field( $_POST['et_layout_type'] ) : '';

	if ( empty( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		die( -1 );
	}

	$update = array(
		'ID'           => $post_id,
		'post_content' => $new_content,
	);

	$result = wp_update_post( $update );

	if ( ! $result || is_wp_error( $result ) ) {
		wp_send_json_error();
	}

	ET_Core_PageResource::remove_static_resources( 'all', 'all' );

	if ( 'module' === $layout_type && isset( $_POST['et_unsynced_options'] ) ) {
		$unsynced_options = sanitize_text_field( stripslashes( $_POST['et_unsynced_options'] ) );

		update_post_meta( $post_id, '_et_pb_excluded_global_options', $unsynced_options );
	}

	die();
}
add_action( 'wp_ajax_et_pb_update_layout', 'et_pb_update_layout' );


function et_pb_load_layout() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$layout_id = ! empty( $_POST['et_layout_id'] ) ? (int) $_POST['et_layout_id'] : 0;

	if ( empty( $layout_id ) || ! current_user_can( 'edit_post', $layout_id ) ) {
		die( -1 );
	}

	// sanitize via whitelisting
	$replace_content = isset( $_POST['et_replace_content'] ) && 'on' === $_POST['et_replace_content'] ? 'on' : 'off';

	set_theme_mod( 'et_pb_replace_content', $replace_content );

	$layout = get_post( $layout_id );

	if ( $layout ) {
		echo et_core_esc_previously( $layout->post_content );
	}

	die();
}
add_action( 'wp_ajax_et_pb_load_layout', 'et_pb_load_layout' );

function et_pb_delete_layout() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	$layout_id = ! empty( $_POST['et_layout_id'] ) ? (int) $_POST['et_layout_id'] : '';

	if ( empty( $layout_id ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'delete_post', $layout_id ) ) {
		die( -1 );
	}

	wp_delete_post( $layout_id );

	die();
}
add_action( 'wp_ajax_et_pb_delete_layout', 'et_pb_delete_layout' );

/**
 * Enables zlib compression if needed/supported.
 */
function et_builder_enable_zlib_compression() {
	// If compression is already enabled, do nothing
	if ( 1 === intval( @ini_get( 'zlib.output_compression' ) ) ) {
		return;
	}

	// We need to be sure no content has been pushed yet before enabling compression
	// to avoid decoding errors. To do so, we flush buffer and then check header_sent
	while ( ob_get_level() ) {
		ob_end_flush();
	}

	if ( headers_sent() ) {
		// Something has been sent already, could be PHP notices or other plugin output
		return;
	}

	// We use ob_gzhandler because less prone to errors with WP
	if ( function_exists( 'ob_gzhandler' ) ) {
		// Faster compression, requires less cpu/memory
		@ini_set( 'zlib.output_compression_level', 1 );

		ob_start( 'ob_gzhandler' );
	}
}

function et_pb_get_backbone_templates() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$post_type = isset( $_POST['et_post_type'] ) ? sanitize_text_field( $_POST['et_post_type'] ) : '';
	$start_from = isset( $_POST['et_templates_start_from'] ) ? sanitize_text_field( $_POST['et_templates_start_from'] ) : 0;
	$amount = ET_BUILDER_AJAX_TEMPLATES_AMOUNT;

	// Enable zlib compression
	et_builder_enable_zlib_compression();
	// get the portion of templates
	$result = wp_json_encode( ET_Builder_Element::output_templates( $post_type, $start_from, $amount ) );

	die( et_core_intentionally_unescaped( $result, 'html' ) );
}
add_action( 'wp_ajax_et_pb_get_backbone_templates', 'et_pb_get_backbone_templates' );

/**
 * Determine if a post is built by a certain builder.
 *
 * @param int    $post_id          The post_id to check.
 * @param string $built_by_builder The builder to check if the post is built by. Allowed values: fb, bb.
 *
 * @return bool
 */
function et_builder_is_builder_built( $post_id, $built_by_builder ) {
	$_post = get_post( $post_id );

	// a autosave could be passed as $post_id, and an autosave will not have post_meta and then et_pb_is_pagebuilder_used() will always return false.
	$parent_post = wp_is_post_autosave( $post_id ) ? get_post( $_post->post_parent ) : $_post;

	if ( ! $post_id || ! $_post || ! is_object( $_post ) || ! et_pb_is_pagebuilder_used( $parent_post->ID ) ) {
		return false;
	}

	// ensure this is an allowed builder post_type
	if ( ! in_array( $parent_post->post_type, et_builder_get_builder_post_types() ) ) {
		return false;
	}

	// whitelist the builder slug
	$built_by_builder = in_array( $built_by_builder, array( 'fb', 'bb' ) ) ? $built_by_builder : '';

	// the built by slug prepended to the first section automatically, in this format: fb_built="1"
	$pattern = '/^\[et_pb_section ' . $built_by_builder . '_built="1"/s';

	return preg_match( $pattern, $_post->post_content );
}

/**
 * @return bool
 */
function et_is_builder_available_cookie_set() {
	static $builder_available = null;

	if ( null !== $builder_available ) {
		return $builder_available;
	}

	foreach( (array) $_COOKIE as $cookie => $value ) {
		if ( 0 === strpos( $cookie, 'et-editor-available-post-' ) ) {
			$builder_available = true;

			return $builder_available;
		}
	}

	$builder_available = false;

	return $builder_available;
}

function et_builder_heartbeat_interval() {
	return apply_filters( 'et_builder_heartbeat_interval', 30 );
}

function et_builder_ensure_heartbeat_interval( $response, $screen_id ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( ! isset( $response['heartbeat_interval'] ) ) {
		return $response;
	}

	if ( et_builder_heartbeat_interval() === $response['heartbeat_interval'] ) {
		return $response;
	}

	if ( ! et_is_builder_available_cookie_set() ) {
		return $response;
	}

	$response['heartbeat_interval'] = et_builder_heartbeat_interval();

	return $response;
}
add_filter( 'heartbeat_send', 'et_builder_ensure_heartbeat_interval', 100, 2 );

function et_pb_heartbeat_post_modified( $response ) {
	et_core_nonce_verified_previously();

	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( empty( $_POST['data'] ) ) {
		return $response;
	}

	$heartbeat_data = $_POST['data'];
	$has_focus = isset( $_POST['has_focus'] ) && 'true' === $_POST['has_focus'] ? true : false;
	$heartbeat_data_et = !empty( $heartbeat_data['et'] ) ? $heartbeat_data['et'] : false;

	if ( ! empty( $heartbeat_data_et ) ) {
		$post_id = ! empty( $heartbeat_data_et['post_id'] ) ? absint( $heartbeat_data_et['post_id'] ) : '';

		if ( empty( $heartbeat_data_et['post_id'] ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return $response;
		}

		$last_post_modified = sanitize_text_field( $heartbeat_data_et['last_post_modified'] );
		$built_by = sanitize_text_field( $heartbeat_data_et['built_by'] );
		$force_check = isset( $heartbeat_data_et['force_check'] ) && 'true' === $heartbeat_data_et['force_check'] ? true : false;
		$force_autosave = isset( $heartbeat_data_et['force_autosave'] ) && 'true' === $heartbeat_data_et['force_autosave'] ? true : false;
		$current_user_id = get_current_user_id();

		$_post = get_post( $post_id );

		if ( ! $post_id || ! $_post || ! is_object( $_post ) ) {
			return $response;
		}

		// minimum sucessful response
		$response['et'] = array(
			'received'       => true,
			'force_check'    => $force_check,
			'force_autosave' => $force_autosave,
		);

		// the editor in focus is not going to be receiving an update from the other editor
		// so we can return early
		if ( $has_focus && !$force_check ) {
			$response['et']['action'] = 'No actions since this editor has focus'; // dev use
			return $response;
		}

		if ( $force_autosave ) {
			$response['et']['action'] = 'No actions since this is a force autosave request'; // dev use
			return $response;
		}

		// from here down we know that the following logic applies to the editor
		// currently *not* in focus, i.e. the one eligable for a potential sync update

		// sync builder settings
		$builder_settings_autosave = get_post_meta( $post_id, "_et_builder_settings_autosave_{$current_user_id}", true );
		if ( ! empty( $builder_settings_autosave ) ) {
			$response['et']['builder_settings_autosave'] = $builder_settings_autosave;
		}

		$post_content = $_post->post_content;
		$post_modified = $_post->post_modified;

		$autosave = wp_get_post_autosave( $post_id, $current_user_id );

		$post_post_modified = date( 'U', strtotime( $post_modified ) );
		$response['et']['post_post_modified'] = $_post->post_modified;

		if ( !empty( $autosave ) ) {
			$response['et']['autosave_exists'] = true;
			$autosave_post_modified = date( 'U', strtotime( $autosave->post_modified ) );
			$response['et']['autosave_post_modified'] = $autosave->post_modified;
		} else {
			$response['et']['autosave_exists'] = false;
		}

		if ( !empty( $autosave ) && $autosave_post_modified > $post_post_modified ) {
			$response['et']['used_autosave'] = true;
			$post_id = $autosave->ID;
			$post_content = $autosave->post_content;
			$post_modified = $autosave->post_modified;
		} else {
			$response['et']['used_autosave'] = false;
		}

		$response['et']['post_id'] = $post_id;
		$response['et']['last_post_modified'] = $last_post_modified;
		$response['et']['post_modified'] = $post_modified;

		// security short circuit
		$_post = get_post( $post_id );

		// $post_id could be an autosave
		$parent_post = wp_is_post_autosave( $post_id ) ? get_post( $_post->post_parent ) : $_post;

		if ( ! et_pb_is_pagebuilder_used( $parent_post->ID ) || ! in_array( $parent_post->post_type, et_builder_get_builder_post_types() ) ) {
			return $response;
		}
		// end security short circuit

		if ( $last_post_modified !== $post_modified ) {

			// check if the newly modified was made by opposite builder,
			// and if so, send it back in the response
			if ( 'bb' === $built_by ) {
				// backend builder in use and in focus

				$response['et']['is_built_by_fb'] = et_builder_is_builder_built( $post_id, 'fb' );
				// check if latest post_content is built by fb
				if ( et_builder_is_builder_built( $post_id, 'fb' ) ) {
					if ( et_builder_bfb_enabled() ) {
						$post_content_obj = et_fb_process_shortcode( $post_content );
						$response['et']['post_content_obj'] = $post_content_obj;
					} else {
						$response['et']['post_content'] = $post_content;
					}
					$response['et']['action'] = 'current editor is bb, updated to content that was built by fb'; // dev use
				} else {
					$response['et']['action'] = 'current editor is bb, content wasnt updated by fb'; // dev use
				}
			} else {
				// frontend builder in use and in focus

				$response['et']['is_built_by_bb'] = et_builder_is_builder_built( $post_id, 'bb' );
				// check if latest post_content is built by bb
				if ( et_builder_is_builder_built( $post_id, et_builder_bfb_enabled() ? 'fb' : 'bb' ) ) {
					$post_content_obj = et_fb_process_shortcode( $post_content );

					$response['et']['post_content_obj'] = $post_content_obj;
					$response['et']['action'] = 'current editor is fb, updated to content that was built by bb'; // dev use
				} else {
					$response['et']['action'] = 'current editor is fb, content wasnt updated by bb'; // dev use
				}
			}
		} else {
			$response['et']['post_not_modified'] = true;
			$response['et']['action'] = 'post content not modified externally'; // dev use
		}
	}

	return $response;
}
add_filter( 'heartbeat_send', 'et_pb_heartbeat_post_modified' );

/**
 * Save a post submitted via ETBuilder Heartbeat.
 *
 * Adapted from WordPress
 *
 * @copyright 2016 by the WordPress contributors.
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * This program incorporates work covered by the following copyright and
 * permission notices:
 *
 * b2 is (c) 2001, 2002 Michel Valdrighi - m@tidakada.com - http://tidakada.com
 *
 * b2 is released under the GPL
 *
 * WordPress - Web publishing software
 *
 * Copyright 2003-2010 by the contributors
 *
 * WordPress is released under the GPL
 *
 * @param array $post_data Associative array of the submitted post data.
 * @return mixed The value 0 or WP_Error on failure. The saved post ID on success.
 *               The ID can be the draft post_id or the autosave revision post_id.
 */

function et_fb_autosave( $post_data ) {
	if ( ! defined( 'DOING_AUTOSAVE' ) ) {
		define( 'DOING_AUTOSAVE', true );
	}

	$post_id = (int) $post_data['post_id'];
	$post_data['ID'] = $post_data['post_ID'] = $post_id;

	if ( false === wp_verify_nonce( $post_data['et_fb_autosave_nonce'], 'et_fb_autosave_nonce' ) ) {
		return new WP_Error( 'invalid_nonce', __( 'Error while saving.', 'et_builder' ) );
	}

	$_post = get_post( $post_id );
	$current_user_id = get_current_user_id();

	if ( ! et_fb_current_user_can_save( $post_id ) ) {
		return new WP_Error( 'edit_posts', __( 'Sorry, you are not allowed to edit this item.', 'et_builder' ) );
	}

	// NOTE, no stripslashes() needed first as it's already been done on the POST'ed $post_data prior
	$shortcode_data = json_decode( $post_data['content'], true );

	$options = array(
		'post_type' => sanitize_text_field( $post_data['post_type'] ),
	);
	$post_data['content'] = et_fb_process_to_shortcode( $shortcode_data, $options );

	if ( 'auto-draft' === $_post->post_status ) {
		$post_data['post_status'] = 'draft';
	}

	if ( ! wp_check_post_lock( $_post->ID ) && get_current_user_id() === $_post->post_author && ( 'auto-draft' === $_post->post_status || 'draft' === $_post->post_status ) ) {
		// Drafts and auto-drafts are just overwritten by autosave for the same user if the post is not locked
		return edit_post( wp_slash( $post_data ) );
	} else {
		// Non drafts or other users drafts are not overwritten. The autosave is stored in a special post revision for each user.
		return wp_create_post_autosave( wp_slash( $post_data ) );
	}
}

function et_pb_autosave_builder_settings( $post_id, $builder_settings ) {
	$current_user_id = get_current_user_id();
	// Builder settings autosave
	if ( !empty( $builder_settings ) ) {

		// Pseudo activate AB Testing for VB draft/builder-sync interface
		if ( isset( $builder_settings['et_pb_use_ab_testing'] ) ) {
			// Save autosave/draft AB Testing status
			update_post_meta(
				$post_id,
				'_et_pb_use_ab_testing_draft',
				sanitize_text_field( $builder_settings['et_pb_use_ab_testing'] )
			);

			// Format AB Testing data, since BB has UI and actual input IDs. FB uses BB's UI ID
			$builder_settings['et_pb_enable_ab_testing'] = $builder_settings['et_pb_use_ab_testing'];

			// Unset BB's actual input data
			unset( $builder_settings['et_pb_use_ab_testing'] );
		}

		// Pseudo save AB Testing subjects for VB draft/builder-sync interface
		if ( isset( $builder_settings['et_pb_ab_subjects'] ) ) {
			// Save autosave/draft subjects
			update_post_meta(
				$post_id,
				'_et_pb_ab_subjects_draft',
				sanitize_text_field( et_prevent_duplicate_item( $builder_settings['et_pb_ab_subjects'], ',' ) )
			);

			// Format subjects data into array
			$builder_settings['et_pb_ab_subjects'] = array_unique( explode( ',', $builder_settings['et_pb_ab_subjects'] ) );
		}

		$et_builder_settings_autosave_data = get_post_meta( $post_id, "_et_builder_settings_autosave_{$current_user_id}", true );

		// Merge incoming post meta changes with saved ones to avoid missing post meta changes that
		// has been synced but hasn't been delivered to VB. Let VB drops autosave once it has been
		// used / inserted into the layout
		if ( is_array( $et_builder_settings_autosave_data ) && is_array( $builder_settings ) ) {
			$et_builder_settings_autosave_data = wp_parse_args(
				$builder_settings,
				$et_builder_settings_autosave_data
			);
		} else {
			$et_builder_settings_autosave_data = $builder_settings;
		}

		return update_post_meta(
			$post_id,
			"_et_builder_settings_autosave_{$current_user_id}",
			$et_builder_settings_autosave_data
		);
	}
}

/**
 * Autosave with heartbeat
 *
 * Adapted from WordPress
 *
 * @copyright 2016 by the WordPress contributors.
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * This program incorporates work covered by the following copyright and
 * permission notices:
 *
 * b2 is (c) 2001, 2002 Michel Valdrighi - m@tidakada.com - http://tidakada.com
 *
 * b2 is released under the GPL
 *
 * WordPress - Web publishing software
 *
 * Copyright 2003-2010 by the contributors
 *
 * WordPress is released under the GPL
 *
 * @param array $response The Heartbeat response.
 * @param array $data     The $_POST data sent.
 * @return array The Heartbeat response.
 */

function et_fb_heartbeat_autosave( $response, $data ) {
	et_core_nonce_verified_previously();

	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( ! empty( $data['et_fb_autosave'] ) ) {
		$post_id = ! empty( $data['et_fb_autosave']['post_id'] ) ? absint( $data['et_fb_autosave']['post_id'] ) : '';

		if ( empty( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return $response;
		}

		$has_focus = !empty( $_POST['has_focus'] ) && 'true' === $_POST['has_focus'];
		$force_autosave = !empty( $data['et'] ) && !empty( $data['et']['force_autosave'] ) && 'true' === $data['et']['force_autosave'];

		$editor_1 = 'fb' === $data['et']['built_by'] ? 'fb' : 'bb';
		$editor_2 = 'fb' === $editor_1 ? 'bb' : 'fb';
		$editor_1_editing_cookie = isset( $_COOKIE[ 'et-editing-post-' . $post_id . '-' . $editor_1 ] ) ? $_COOKIE[ 'et-editing-post-' . $post_id . '-' . $editor_1 ] : false;
		$editor_2_editor_available_cookie = isset( $_COOKIE[ 'et-editor-available-post-' . $post_id . '-' . $editor_2 ] ) ? $_COOKIE[ 'et-editor-available-post-' . $post_id . '-' . $editor_2 ] : false;
		$editor_1_autosavable = !empty( $editor_1_editing_cookie ) && empty( $editor_2_editor_available_cookie );

		if ( !$has_focus && !$force_autosave && !$editor_1_autosavable ) {
			$response['et_fb_autosave'] = array( 'success' => false, 'message' => __( 'Not saved, editor out of focus', 'et_builder' ) );
			return $response;
		}

		$saved = et_fb_autosave( $data['et_fb_autosave'] );

		if ( ! is_wp_error( $saved ) && ! empty( $data['et_fb_autosave']['builder_settings'] ) ) {
			$builder_settings_autosaved = et_pb_autosave_builder_settings( $post_id, $data['et_fb_autosave']['builder_settings'] );
			$response['et_pb_autosave_builder_settings'] = array( 'success' => $builder_settings_autosaved, 'message' => __( 'Builder settings synced', 'et_builder' ) );
		}

		if ( is_wp_error( $saved ) ) {
			$response['et_fb_autosave'] = array( 'success' => false, 'message' => $saved->get_error_message() );
		} elseif ( empty( $saved ) ) {
			$response['et_fb_autosave'] = array( 'success' => false, 'message' => __( 'Error while saving.', 'et_builder' ) );
		} else {
			/* translators: draft saved date format, see https://secure.php.net/date */
			$draft_saved_date_format = __( 'g:i:s a', 'et_builder' );
			/* translators: %s: date and time */
			$response['et_fb_autosave'] = array( 'success' => true, 'message' => sprintf( __( 'Draft saved at %s.', 'et_builder' ), date_i18n( $draft_saved_date_format ) ) );
		}
	}

	return $response;
}
add_filter( 'heartbeat_received', 'et_fb_heartbeat_autosave', 499, 2 );

function et_bb_heartbeat_autosave( $response, $data ) {
	et_core_nonce_verified_previously();

	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( ! empty( $data['wp_autosave'] ) ) {
		$has_focus = !empty( $_POST['has_focus'] ) && 'true' === $_POST['has_focus'];
		$force_autosave = !empty( $data['et'] ) && !empty( $data['et']['force_autosave'] ) && 'true' === $data['et']['force_autosave'];

		if ( !$has_focus && !$force_autosave ) {
			$response['wp_autosave'] = array( 'success' => true, 'message' => __( 'Not saved, editor out of focus', 'et_builder' ) );
			remove_filter( 'heartbeat_received', 'heartbeat_autosave', 500, 2 );
			remove_filter( 'heartbeat_received', 'et_bb_heartbeat_builder_settings_autosave', 500, 2 );
		} else if ( $force_autosave ) {
			$response['wp_autosave_check'] = array( 'success' => true, 'message' => 'saved, because force_autosave ' );
		}
	}
	return $response;
}
add_filter( 'heartbeat_received', 'et_bb_heartbeat_autosave', 498, 2 );

function et_bb_heartbeat_builder_settings_autosave( $response, $data ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( ! empty( $data['wp_autosave'] ) ) {
		$post_id = ! empty( $data['wp_autosave']['post_id'] ) ? absint( $data['wp_autosave']['post_id'] ) : '';

		if ( empty( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return $response;
		}

		if ( ! empty( $data['wp_autosave']['builder_settings'] ) ) {
			$builder_settings_autosaved = et_pb_autosave_builder_settings( $post_id, $data['wp_autosave']['builder_settings'] );
			$response['et_pb_autosave_builder_settings'] = array( 'success' => $builder_settings_autosaved, 'message' => __( 'Builder settings synced', 'et_builder' ) );
		}
	}

	return $response;
}
add_filter( 'heartbeat_received', 'et_bb_heartbeat_builder_settings_autosave', 500, 2 );

function et_fb_wp_refresh_nonces( $response, $data, $screen_id ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return $response;
	}

	if ( ! isset( $data['et']['built_by'] ) || 'fb' !== $data['et']['built_by'] ) {
		return $response;
	}

	$response['et'] = array(
		'exportUrl'       => et_fb_get_portability_export_url(),
		'nonces'          => et_fb_get_nonces(),
		'heartbeat_nonce' => wp_create_nonce( 'heartbeat-nonce' ),
	);

	return $response;
}
add_filter( 'wp_refresh_nonces', 'et_fb_wp_refresh_nonces', 10, 3 );

function et_fb_get_portability_export_url() {
	$admin_url = is_ssl() ? admin_url() : admin_url( '', 'http' );
	$args      = array(
		'et_core_portability' => true,
		'context'             => 'et_builder',
		'name'                => 'temp_name',
		'nonce'               => wp_create_nonce( 'et_core_portability_nonce' ),
	);
	return add_query_arg( $args, $admin_url );
}

function et_fb_get_nonces() {
	$nonces    = apply_filters( 'et_fb_nonces', array() );
	$fb_nonces = array(
		'moduleContactFormSubmit'       => wp_create_nonce( 'et-pb-contact-form-submit' ),
		'et_admin_load'                 => wp_create_nonce( 'et_admin_load_nonce' ),
		'computedProperty'              => wp_create_nonce( 'et_pb_process_computed_property_nonce' ),
		'renderShortcode'               => wp_create_nonce( 'et_pb_render_shortcode_nonce' ),
		'updateAssets'                  => wp_create_nonce( 'et_fb_update_helper_assets_nonce' ),
		'loadAssets'                    => wp_create_nonce( 'et_fb_load_helper_assets_nonce' ),
		'renderSave'                    => wp_create_nonce( 'et_fb_save_nonce' ),
		'convertToShortcode'            => wp_create_nonce( 'et_fb_convert_to_shortcode_nonce' ),
		'dropAutosave'                  => wp_create_nonce( 'et_fb_drop_autosave_nonce' ),
		'prepareShortcode'              => wp_create_nonce( 'et_fb_prepare_shortcode_nonce' ),
		'processImportedData'           => wp_create_nonce( 'et_fb_process_imported_data_nonce' ),
		'retrieveLibraryModules'        => wp_create_nonce( 'et_fb_retrieve_library_modules_nonce' ),
		'saveLibraryModules'            => wp_create_nonce( 'et_fb_save_library_modules_nonce' ),
		'preview'                       => wp_create_nonce( 'et_pb_preview_nonce' ),
		'autosave'                      => wp_create_nonce( 'et_fb_autosave_nonce' ),
		'moduleEmailOptinFetchLists'    => wp_create_nonce( 'et_builder_email_fetch_lists_nonce' ),
		'moduleEmailOptinAddAccount'    => wp_create_nonce( 'et_builder_email_add_account_nonce' ),
		'moduleEmailOptinRemoveAccount' => wp_create_nonce( 'et_builder_email_remove_account_nonce' ),
		'uploadFontNonce'               => wp_create_nonce( 'et_fb_upload_font_nonce' ),
		'abTestingReport'               => wp_create_nonce( 'ab_testing_builder_nonce' ),
		'libraryLayoutsData'            => wp_create_nonce( 'et_builder_library_get_layouts_data' ),
		'libraryGetLayout'              => wp_create_nonce( 'et_builder_library_get_layout' ),
		'libraryUpdateAccount'          => wp_create_nonce( 'et_builder_library_update_account' ),
		'fetchAttachments'              => wp_create_nonce( 'et_fb_fetch_attachments' ),
		'droploaderProcess'             => wp_create_nonce( 'et_builder_droploader_process_nonce' ),
		'resolvePostContent'            => wp_create_nonce( 'et_fb_resolve_post_content' ),
	);

	return array_merge( $nonces, $fb_nonces );
}

if ( ! function_exists( 'et_builder_is_product_tour_enabled' ) ):
function et_builder_is_product_tour_enabled() {
	static $product_tour_enabled = null;

	if ( null !== $product_tour_enabled ) {
		return $product_tour_enabled;
	}

	if ( ! ( function_exists( 'et_fb_is_enabled' ) && et_fb_is_enabled() ) ) {
		// Do not update `$product_tour_enabled` at this point since we can run et_builder_is_product_tour_enabled() check later
		// when et_fb_is_enabled() will be available.
		return false;
	}

	/**
	 * Filters the on/off status of the product tour for the current user.
	 *
	 * @since 3.0.64
	 *
	 * @param string $product_tour_status_override Accepts 'on', 'off'.
	 */
	$product_tour_status_override = apply_filters( 'et_builder_product_tour_status_override', false );

	if ( false !== $product_tour_status_override ) {
		$product_tour_enabled = 'on' === $product_tour_status_override;
	} else {
		$user_id                    = (int) get_current_user_id();
		$product_tour_settings      = et_get_option( 'product_tour_status', array() );
		$product_tour_status_global = 'on' === et_get_option( 'et_pb_product_tour_global', 'on' );
		$product_tour_enabled       = $product_tour_status_global && ( ! isset( $product_tour_settings[ $user_id ] ) || 'on' === $product_tour_settings[ $user_id ] );
	}

	return $product_tour_enabled;
}
endif;

function et_pb_get_backbone_template() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$module_slugs = json_decode( str_replace( '\\', '', sanitize_text_field( $_POST['et_modules_slugs'] ) ) );
	$post_type   = sanitize_text_field( $_POST['et_post_type'] );

	// Enable zlib compression
	et_builder_enable_zlib_compression();
	// get the portion of templates for specified slugs
	$result = wp_json_encode( ET_Builder_Element::get_modules_templates( $post_type, $module_slugs->missing_modules_array ) );

	die( et_core_esc_previously( $result ) );
}
add_action( 'wp_ajax_et_pb_get_backbone_template', 'et_pb_get_backbone_template' );


if ( ! function_exists( 'et_builder_email_add_account' ) ):
/**
 * Ajax handler for the Email Opt-in Module's "Add Account" action.
 */
function et_builder_email_add_account() {
	et_core_security_check( 'manage_options', 'et_builder_email_add_account_nonce' );

	$provider_slug = isset( $_POST['et_provider'] ) ? sanitize_text_field( $_POST['et_provider'] ) : '';
	$name_key      = "et_{$provider_slug}_account_name";
	$account_name  = isset( $_POST[ $name_key ] ) ? sanitize_text_field( $_POST[ $name_key ] ) : '';
	$is_BB         = isset( $_POST['et_bb'] );

	if ( empty( $provider_slug ) || empty( $account_name ) ) {
		et_core_die();
	}

	unset( $_POST[ $name_key ] );

	$fields = et_builder_email_get_fields_from_post_data( $provider_slug );

	if ( false === $fields  ) {
		et_core_die();
	}

	$result = et_core_api_email_fetch_lists( $provider_slug, $account_name, $fields );
	$_      = ET_Core_Data_Utils::instance();

	// Get data in builder format
	$list_data = et_builder_email_get_lists_field_data( $provider_slug, $is_BB );

	if ( 'success' === $result ) {
		$result = array(
			'error'                    => false,
			'accounts_list'            => $_->array_get( $list_data, 'accounts_list', $list_data ),
			'custom_fields'            => $_->array_get( $list_data, 'custom_fields', array() ),
			'predefined_custom_fields' => ET_Core_API_Email_Providers::instance()->custom_fields_data(),
		);
	} else {
		$result = array(
			'error'                    => true,
			'message'                  => esc_html__( 'Error: ', 'et_builder' ) . esc_html( $result ),
			'accounts_list'            => $_->array_get( $list_data, 'accounts_list', $list_data ),
			'custom_fields'            => $_->array_get( $list_data, 'custom_fields', array() ),
			'predefined_custom_fields' => ET_Core_API_Email_Providers::instance()->custom_fields_data(),
		);
	}

	die( wp_json_encode( $result ) );
}
add_action( 'wp_ajax_et_builder_email_add_account', 'et_builder_email_add_account' );
endif;


if ( ! function_exists( 'et_builder_email_get_fields_from_post_data' ) ):
function et_builder_email_get_fields_from_post_data( $provider_slug ) {
	et_core_security_check( 'manage_options', 'et_builder_email_add_account_nonce' );

	$fields = ET_Core_API_Email_Providers::instance()->account_fields( $provider_slug );
	$result = array();

	if ( ! $fields ) {
		// If there are no fields to check then the check passes.
		return $fields;
	}

	foreach ( $fields as $field_name => $field_info ) {
		$key = "et_{$provider_slug}_{$field_name}";

		if ( empty( $_POST[$key] ) && ! isset( $field_info['not_required'] ) ) {
			return false;
		}

		$result[ $field_name ] = sanitize_text_field( $_POST[ $key ] );
	}

	return $result;
}
endif;


if ( ! function_exists( 'et_builder_email_get_lists_field_data' ) ):
/**
 * Get email list data in a builder's options field format.
 *
 * @param string $provider_slug
 * @param bool   $is_BB
 *
 * @return array|string The data in the BB's format if `$is_BB` is `true`, the FB's format otherwise.
 */
function et_builder_email_get_lists_field_data( $provider_slug, $is_BB = false ) {
	$signup     = new ET_Builder_Module_Signup();
	$fields     = $signup->get_fields();
	$field_name = $provider_slug . '_list';
	$field      = $fields[ $field_name ];

	if ( $is_BB ) {
		$field['only_options'] = true;
		$field['name']         = $field_name;
		$field_data            = $signup->render_field( $field );
	} else {
		$signup_field  = new ET_Builder_Module_Signup_Item;
		$field_data    = array(
			'accounts_list' => $field['options'],
			'custom_fields' => $signup_field->get_fields(),
		);
	}

	// Make sure the BB updates its cached templates
	et_pb_force_regenerate_templates();

	return $field_data;
}
endif;


if ( ! function_exists( 'et_builder_email_get_lists' ) ):
/**
 * Ajax handler for the Email Opt-in Module's "Fetch Lists" action.
 */
function et_builder_email_get_lists() {
	et_core_security_check( 'manage_options', 'et_builder_email_fetch_lists_nonce' );

	$provider_slug = isset( $_POST['et_provider'] ) ? sanitize_text_field( $_POST['et_provider'] ) : '';
	$account_name  = isset( $_POST['et_account'] ) ? sanitize_text_field( $_POST['et_account'] ) : '';
	$is_BB         = isset( $_POST['et_bb'] );

	if ( empty( $provider_slug ) || empty( $account_name ) ) {
		et_core_die();
	}

	// Make sure email component group is loaded;
	new ET_Core_API_Email_Providers();

	$_ = ET_Core_Data_Utils::instance();

	// Fetch lists from provider
	$message = et_core_api_email_fetch_lists( $provider_slug, $account_name );

	// Get data in builder format
	$list_data = et_builder_email_get_lists_field_data( $provider_slug, $is_BB );

	$result = array(
		'error'                    => false,
		'accounts_list'            => $_->array_get( $list_data, 'accounts_list', $list_data ),
		'custom_fields'            => $_->array_get( $list_data, 'custom_fields', array() ),
		'predefined_custom_fields' => ET_Core_API_Email_Providers::instance()->custom_fields_data(),
	);

	if ( 'success' !== $message ) {
		$result['error']   = true;
		$result['message'] = esc_html__( 'Error: ', 'et_core' ) . esc_html( $message );
	}

	die( wp_json_encode( $result ) );
}
add_action( 'wp_ajax_et_builder_email_get_lists', 'et_builder_email_get_lists' );
endif;


if ( ! function_exists( 'et_builder_email_maybe_migrate_accounts') ):
function et_builder_email_maybe_migrate_accounts() {
	$divi_migrated_key    = 'divi_email_provider_credentials_migrated';
	$builder_migrated_key = 'email_provider_credentials_migrated';

	$builder_options  = (array) get_option( 'et_pb_builder_options' );
	$builder_migrated = isset( $builder_options[ $builder_migrated_key ] );
	$divi_migrated    = false !== et_get_option( $divi_migrated_key, false );

	$data_utils = ET_Core_Data_Utils::instance();
	$migrations = array( 'builder' => $builder_migrated, 'divi' => $divi_migrated );
	$providers  = new ET_Core_API_Email_Providers(); // Ensure the email component group is loaded.

	if ( $data_utils->all( $migrations, true ) ) {
		// We've already migrated accounts data
		return;
	}

	foreach ( $migrations as $product => $completed ) {
		if ( 'builder' === $product ) {
			$account_name      = 'Divi Builder Plugin';
			$mailchimp_api_key = isset( $builder_options['newsletter_main_mailchimp_key'] ) ? $builder_options['newsletter_main_mailchimp_key'] : '';

			$consumer_key    = isset( $builder_options['aweber_consumer_key'] ) ? $builder_options['aweber_consumer_key'] : '';
			$consumer_secret = isset( $builder_options['aweber_consumer_secret'] ) ? $builder_options['aweber_consumer_secret'] : '';
			$access_key      = isset( $builder_options['aweber_access_key'] ) ? $builder_options['aweber_access_key'] : '';
			$access_secret   = isset( $builder_options['aweber_access_secret'] ) ? $builder_options['aweber_access_secret'] : '';
		} else if ( 'divi' === $product ) {
			$account_name      = 'Divi Builder';
			$mailchimp_api_key = et_get_option( 'divi_mailchimp_api_key' );

			$consumer_key    = et_get_option( 'divi_aweber_consumer_key' );
			$consumer_secret = et_get_option( 'divi_aweber_consumer_secret' );
			$access_key      = et_get_option( 'divi_aweber_access_key' );
			$access_secret   = et_get_option( 'divi_aweber_access_secret' );
		} else {
			continue; // Satisfy code linter.
		}

		$aweber_key_parts = array( $consumer_key, $consumer_secret, $access_key, $access_secret );

		if ( $data_utils->all( $aweber_key_parts ) ) {
			// Typically AWeber tokens have five parts. We don't have the last part (the verifier token) because
			// we didn't save it at the time it was originally input by the user. Thus, we add an additional separator
			// (|) so that the token passes the processing performed by ET_Core_API_Email_Aweber::_parse_ID().
			$aweber_api_key = implode( '|', array( $consumer_key, $consumer_secret, $access_key, $access_secret, '|' ) );
		}

		if ( ! empty( $mailchimp_api_key ) ) {
			et_core_api_email_fetch_lists( 'MailChimp', "{$account_name} MailChimp", $mailchimp_api_key );
		}

		if ( ! empty( $aweber_api_key ) ) {
			$aweber = $providers->get( 'Aweber', "{$account_name} Aweber", 'builder' );

			$aweber->data['api_key']         = $aweber_api_key;
			$aweber->data['consumer_key']    = $consumer_key;
			$aweber->data['consumer_secret'] = $consumer_secret;
			$aweber->data['access_key']      = $access_key;
			$aweber->data['access_secret']   = $access_secret;
			$aweber->data['is_authorized']   = true;

			$aweber->save_data();
			$aweber->fetch_subscriber_lists();
		}
	}

	// Make sure the BB updates its cached templates
	et_pb_force_regenerate_templates();

	$builder_options[ $builder_migrated_key ] = true;

	update_option( 'et_pb_builder_options', $builder_options );
	et_update_option( $divi_migrated_key, true );
}
endif;


if ( ! function_exists( 'et_builder_email_remove_account' ) ):
/**
 * Ajax handler for the Email Opt-in Module's "Remove Account" action.
 */
function et_builder_email_remove_account() {
	et_core_security_check( 'manage_options', 'et_builder_email_remove_account_nonce' );

	$provider_slug = sanitize_text_field( $_POST['et_provider'] );
	$account_name  = sanitize_text_field( $_POST['et_account'] );
	$is_BB         = isset( $_POST['et_bb'] );

	if ( empty( $provider_slug ) || empty( $account_name ) ) {
		et_core_die();
	}

	et_core_api_email_remove_account( $provider_slug, $account_name );

	$_ = ET_Core_Data_Utils::instance();

	// Get data in builder format
	$list_data = et_builder_email_get_lists_field_data( $provider_slug, $is_BB );

	$result = array(
		'error'                    => false,
		'accounts_list'            => $_->array_get( $list_data, 'accounts_list', $list_data ),
		'custom_fields'            => $_->array_get( $list_data, 'custom_fields', array() ),
		'predefined_custom_fields' => ET_Core_API_Email_Providers::instance()->custom_fields_data(),
	);

	die( wp_json_encode( $result ) );
}
add_action( 'wp_ajax_et_builder_email_remove_account', 'et_builder_email_remove_account' );
endif;


if ( ! function_exists( 'et_pb_submit_subscribe_form' ) ):
/**
 * Ajax handler for Email Opt-in Module form submissions.
 */
function et_pb_submit_subscribe_form() {
	et_core_security_check( '', 'et_frontend_nonce' );

	$providers = ET_Core_API_Email_Providers::instance();
	$utils     = ET_Core_Data_Utils::instance();

	$provider_slug = sanitize_text_field( $utils->array_get( $_POST, 'et_provider' ) );
	$account_name  = sanitize_text_field( $utils->array_get( $_POST, 'et_account' ) );
	$custom_fields = $utils->array_get( $_POST, 'et_custom_fields', array() );

	if ( ! $provider = $providers->get( $provider_slug, $account_name, 'builder' ) ) {
		et_core_die( esc_html__( 'Configuration Error: Invalid data.', 'et_builder' ) );
	}

	$args = array(
		'list_id'       => sanitize_text_field( $utils->array_get( $_POST, 'et_list_id' ) ),
		'email'         => sanitize_text_field( $utils->array_get( $_POST, 'et_email' ) ),
		'name'          => sanitize_text_field( $utils->array_get( $_POST, 'et_firstname' ) ),
		'last_name'     => sanitize_text_field( $utils->array_get( $_POST, 'et_lastname' ) ),
		'ip_address'    => sanitize_text_field( $utils->array_get( $_POST, 'et_ip_address' ) ),
		'custom_fields' => $utils->sanitize_text_fields( $custom_fields ),
	);

	if ( ! is_email( $args['email'] ) ) {
		et_core_die( esc_html__( 'Please input a valid email address.', 'et_builder' ) );
	}

	if ( empty( $args['list_id'] ) ) {
		et_core_die( esc_html__( 'Configuration Error: No list has been selected for this form.', 'et_builder' ) );
	}

	et_builder_email_maybe_migrate_accounts();

	$result = $provider->subscribe( $args );

	if ( 'success' === $result ) {
		$result  = array( 'success' => true );
	} else {
		$message = esc_html__( 'Subscription Error: ', 'et_builder' );
		$result  = array( 'error' => $message . $result );
	}

	die( wp_json_encode( $result ) );
}
add_action( 'wp_ajax_et_pb_submit_subscribe_form', 'et_pb_submit_subscribe_form' );
add_action( 'wp_ajax_nopriv_et_pb_submit_subscribe_form', 'et_pb_submit_subscribe_form' );
endif;


if ( ! function_exists( 'et_builder_has_limitation' ) ):
/**
 * Whether or not the builder currently has a certain limitation.
 *
 * @since 3.18
 *
 * @return bool
 */
function et_builder_has_limitation( $limit ) {
	if ( 'use_wrapped_styles' === $limit && et_builder_is_post_type_custom( get_post_type() ) ) {
		return true;
	}

	if ( ! et_builder_is_limited_mode() ) {
		return false;
	}

	$limited_builder        = et_get_limited_builder();
	$limited_builder_limits = et_get_limited_builder_defaults();

	switch ( $limited_builder ) {
		case 'divi-builder-plugin':
			$limited_builder_limits = array_merge( $limited_builder_limits, array(
				'use_wrapped_styles',
				'force_use_global_important',
				'use_limited_main',
				'use_additional_limiting_styles',
				'forced_icon_color_default',
				'register_fittext_script',
			) );
			break;
	}

	return in_array( $limit, $limited_builder_limits );
}
endif;

/**
 * Get the defaults for limited builder limitations.
 *
 * @since 3.18
 *
 * @return string[]
 */
function et_get_limited_builder_defaults() {
	return apply_filters( 'et_builder_get_limited_builder_defaults', array(
		'force_enqueue_theme_style',
	) );
}


/**
 * Get the slug name of the current limited builder.
 *
 * @since 3.18
 *
 * @return string The slug name of the current limited builder.
 */
function et_get_limited_builder() {
	$get = $_GET;
	$bfb = '1' === et_()->array_get( $get, 'et_bfb', '0' );
	$dbp = et_is_builder_plugin_active();

	if ( $dbp ) {
		return 'divi-builder-plugin';
	}

	if ( $bfb ) {
		return 'bfb';
	}

	return '';
}


if ( ! function_exists( 'et_builder_is_limited_mode' ) ):
/**
 * Is Builder in limited mode?
 *
 * @since 3.18
 *
 * @return bool  True - if the builder is in limited mode.
 */
function et_builder_is_limited_mode() {
	$get = $_GET;

	return '' !== et_get_limited_builder();
}
endif;

if ( ! function_exists( 'et_is_builder_plugin_active' ) ):
/**
 * Is Builder plugin active?
 *
 * @since 3.18
 * @deprecated ??
 *
 * @return bool  True - if the plugin is active.
 */
function et_is_builder_plugin_active() {
	return (bool) defined( 'ET_BUILDER_PLUGIN_ACTIVE' );
}
endif;

if ( ! function_exists( 'et_is_shortcodes_plugin_active' ) ):
	/**
	 * Is ET Shortcodes plugin active?
	 *
	 * @return bool  True - if the plugin is active
	 */
	function et_is_shortcodes_plugin_active() {
		return (bool) defined( 'ET_SHORTCODES_PLUGIN_VERSION' );
	}
endif;

/**
 * Saves the Role Settings into WP database
 * @return void
 */
function et_pb_save_role_settings() {
	if ( ! wp_verify_nonce( $_POST['et_pb_save_roles_nonce'] , 'et_roles_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	// handle received data and convert json string to array
	$data_json = str_replace( '\\', '' ,  $_POST['et_pb_options_all'] );
	$data = json_decode( $data_json, true );
	$processed_options = array();

	// convert settings string for each role into array and save it into et_pb_role_settings option
	if ( ! empty( $data ) ) {
		$role_capabilities = array();
		foreach( $data as $role => $settings ) {
			parse_str( $data[ $role ], $role_capabilities );
			foreach ( $role_capabilities as $capability => $value ) {
				$processed_options[ $role ][ $capability ] = $value;
			}
		}
	}

	update_option( 'et_pb_role_settings', $processed_options );
	// set the flag to reload backbone templates and make sure all the role settings applied correctly right away
	et_update_option( 'et_pb_clear_templates_cache', true );

	// Delete cached definitions / helpers
	et_fb_delete_builder_assets();

	die();
}
add_action( 'wp_ajax_et_pb_save_role_settings', 'et_pb_save_role_settings' );

/**
 * Filter callback to determine what shortcodes tags are to be removed.
 * Stips all non-builder shortcodes.
 *
 * @see strip_shortcodes_tagnames
 *
 * @param array $tags_to_remove An array of tags to be removed during strip_shortcodes() call.
 *
 * @return array An array of tags to be removed during strip_shortcodes() call.
 */
function et_pb_strip_non_builder_shortcodes_tagnames( $tags_to_remove ) {
	// Initial whitelist
	$valid_tags = ET_Builder_Element::get_module_slugs_by_post_type();

	/**
	 * What shortcode tags are valid (and safe) builder shortcodes,
	 * all other shortcodes will be stripped.
	 *
	 * @param array $valid_tags Array of valid shortcode tags.
	 */
	$valid_tags = apply_filters( 'et_pb_valid_builder_shortcodes', $valid_tags );

	// Generate a blacklist, by subtracting the whitelist from all registered shortcodes.
	$tags_to_remove = array_diff( $tags_to_remove, $valid_tags );

	return $tags_to_remove;
}

/**
 * Is Yoast SEO plugin active?
 *
 * @return bool  True - if the plugin is active
 */
if ( ! function_exists( 'et_is_yoast_seo_plugin_active' ) ) :
	function et_is_yoast_seo_plugin_active() {
		return class_exists( 'WPSEO_Options' );
	}
endif;

/**
 * Ajax callback used by BB to render builder shortcode content for use by
 * Yoast to generate a preview description.
 *
 * @return void
 */
function et_pb_yoast_execute_content_shortcodes() {
	if ( ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	// this is only for yoast support
	if ( et_is_yoast_seo_plugin_active() ) {
		die( -1 );
	}

	$unprocessed_data = str_replace( '\\', '', $_POST['et_pb_unprocessed_data'] );

	// Remove any non-builder shortcodes, since were just trying to generate a yoast preview description here. We just need Yoast to be able to pick up a few sentences.
	$unprocessed_data = et_pb_enforce_builder_shortcode( $unprocessed_data );

	die( et_core_intentionally_unescaped( do_shortcode( $unprocessed_data ), 'html' ) );
}
add_action( 'wp_ajax_et_pb_yoast_execute_content_shortcodes', 'et_pb_yoast_execute_content_shortcodes' );

/**
 * Remove all non-builder shortcodes from builder built post content.
 *
 * @param string $content Builder built post content.
 *
 * @return string Sanitized builder built post content.
 */
function et_pb_enforce_builder_shortcode( $content ) {
	add_filter( 'strip_shortcodes_tagnames', 'et_pb_strip_non_builder_shortcodes_tagnames' );

	$content = strip_shortcodes( $content );

	remove_filter( 'strip_shortcodes_tagnames', 'et_pb_strip_non_builder_shortcodes_tagnames' );

	// this will parse the shortcode to an array, then run it back through some sanity check and sanitization and reform into a shortcode again.
	$content = et_pb_sanitize_shortcode( $content, true );

	return $content;
}

if ( ! function_exists( 'et_pb_register_posttypes' ) ) :
function et_pb_register_posttypes() {
	$labels = array(
		'name'               => esc_html__( 'Projects', 'et_builder' ),
		'singular_name'      => esc_html__( 'Project', 'et_builder' ),
		'add_new'            => esc_html__( 'Add New', 'et_builder' ),
		'add_new_item'       => esc_html__( 'Add New Project', 'et_builder' ),
		'edit_item'          => esc_html__( 'Edit Project', 'et_builder' ),
		'new_item'           => esc_html__( 'New Project', 'et_builder' ),
		'all_items'          => esc_html__( 'All Projects', 'et_builder' ),
		'view_item'          => esc_html__( 'View Project', 'et_builder' ),
		'search_items'       => esc_html__( 'Search Projects', 'et_builder' ),
		'not_found'          => esc_html__( 'Nothing found', 'et_builder' ),
		'not_found_in_trash' => esc_html__( 'Nothing found in Trash', 'et_builder' ),
		'parent_item_colon'  => '',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'can_export'         => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'has_archive'        => true,
		'rewrite'            => apply_filters( 'et_project_posttype_rewrite_args', array(
			'feeds'      => true,
			'slug'       => 'project',
			'with_front' => false,
		) ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
	);

	register_post_type( 'project', apply_filters( 'et_project_posttype_args', $args ) );

	$labels = array(
		'name'              => esc_html__( 'Project Categories', 'et_builder' ),
		'singular_name'     => esc_html__( 'Project Category', 'et_builder' ),
		'search_items'      => esc_html__( 'Search Categories', 'et_builder' ),
		'all_items'         => esc_html__( 'All Categories', 'et_builder' ),
		'parent_item'       => esc_html__( 'Parent Category', 'et_builder' ),
		'parent_item_colon' => esc_html__( 'Parent Category:', 'et_builder' ),
		'edit_item'         => esc_html__( 'Edit Category', 'et_builder' ),
		'update_item'       => esc_html__( 'Update Category', 'et_builder' ),
		'add_new_item'      => esc_html__( 'Add New Category', 'et_builder' ),
		'new_item_name'     => esc_html__( 'New Category Name', 'et_builder' ),
		'menu_name'         => esc_html__( 'Categories', 'et_builder' ),
		'not_found'         => esc_html__( "You currently don't have any project categories.", 'et_builder' ),
	);

	register_taxonomy( 'project_category', array( 'project' ), array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => esc_html__( 'Project Tags', 'et_builder' ),
		'singular_name'     => esc_html__( 'Project Tag', 'et_builder' ),
		'search_items'      => esc_html__( 'Search Tags', 'et_builder' ),
		'all_items'         => esc_html__( 'All Tags', 'et_builder' ),
		'parent_item'       => esc_html__( 'Parent Tag', 'et_builder' ),
		'parent_item_colon' => esc_html__( 'Parent Tag:', 'et_builder' ),
		'edit_item'         => esc_html__( 'Edit Tag', 'et_builder' ),
		'update_item'       => esc_html__( 'Update Tag', 'et_builder' ),
		'add_new_item'      => esc_html__( 'Add New Tag', 'et_builder' ),
		'new_item_name'     => esc_html__( 'New Tag Name', 'et_builder' ),
		'menu_name'         => esc_html__( 'Tags', 'et_builder' ),
	);

	register_taxonomy( 'project_tag', array( 'project' ), array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
	) );
}
endif;


function et_admin_backbone_templates_being_loaded() {
	if ( ! et_core_security_check_passed( '', 'et_admin_load_nonce' ) ) {
		return false;
	}

	if ( ! is_admin() ) {
		return false;
	}

	if ( ! wp_doing_ajax() ) {
		return false;
	}

	if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return false;
	}

	if ( ! isset( $_POST['action'] ) || 'et_pb_get_backbone_templates' !== $_POST['action'] ) {
		return false;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}

	return true;
}

if ( ! function_exists( 'et_pb_attempt_memory_limit_increase' ) ) :
function et_pb_attempt_memory_limit_increase() {
	if ( ! isset( $_POST['et_admin_load_nonce'] ) || ! wp_verify_nonce( $_POST['et_admin_load_nonce'], 'et_admin_load_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	if ( et_increase_memory_limit() ) {
		et_update_option( 'set_memory_limit', '1' );

		die( wp_json_encode( array(
			'success' => true,
		) ) );
	} else {
		die( wp_json_encode( array(
			'error' => true,
		) ) );
	}

	die();
}
endif;

add_action( 'wp_ajax_et_pb_increase_memory_limit', 'et_pb_attempt_memory_limit_increase' );

if ( ! function_exists( 'et_reset_memory_limit_increase' ) ) :
function et_reset_memory_limit_increase() {
	if ( ! isset( $_POST['et_builder_reset_memory_limit_nonce'] ) || ! wp_verify_nonce( $_POST['et_builder_reset_memory_limit_nonce'], 'et_builder_reset_memory_limit_nonce' ) ) {
		die( -1 );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	if ( et_get_option( 'set_memory_limit' ) ) {
		et_delete_option( 'set_memory_limit' );
	}

	die( 'success' );
}
endif;

add_action( 'wp_ajax_et_reset_memory_limit_increase', 'et_reset_memory_limit_increase' );

if ( ! function_exists( 'et_builder_get_cache_notification_modal' ) ) :
function et_builder_get_cache_notification_modal() {
	$cache_plugin_message = '';

	if ( false !== ( $cache_plugin = et_pb_detect_cache_plugins() ) ) {
		$cache_plugin_message = sprintf(
			esc_html__( 'You are using the %1$s plugin. We recommend clearing the plugin cache after updating your theme.', 'et_builder' ),
			esc_html( $cache_plugin['name'] )
		);

		$cache_plugin_message = '<p>' . $cache_plugin_message . '</p>';

		$cache_plugin_message .= sprintf(
			'<a href="%1$s" class="et_builder_modal_action_button" target="_blank">%2$s</a>',
			esc_url( admin_url( $cache_plugin['page'] ) ),
			esc_html__( 'Clear Plugin Cache', 'et_builder' )
		);
	}

	$browser_cache_message = '<p>' . esc_html__( 'Builder files may also be cached in your browser. Please clear your browser cache.', 'et_builder' ) . '</p>';

	$browser_cache_message .= sprintf(
		'<a href="http://www.refreshyourcache.com/en/home/" class="et_builder_modal_action_button" target="_blank">%1$s</a>',
		esc_html__( 'Clear Browser Cache', 'et_builder' )
	);

	$output = sprintf(
		'<div class="et_pb_modal_overlay et_modal_on_top et_pb_failure_notification_modal et_pb_new_template_modal">
			<div class="et_pb_prompt_modal">
				<h2>%1$s</h2>

				<div class="et_pb_prompt_modal_inside">
					<p>%2$s</p>
					%4$s
					%5$s
					<p>%6$s</p>
				</div>

				<a href="#"" class="et_pb_prompt_dont_proceed et-pb-modal-close"></a>

				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et_pb_reload_builder button-primary et_pb_prompt_proceed">%3$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'Builder Cache Warning', 'et_builder' ),
		esc_html__( 'The Divi Builder has been updated, however your browser is loading an old cached version of the builder. Loading old files can cause the builder to malfunction.', 'et_builder' ),
		esc_html__( 'Reload The Builder', 'et_builder' ),
		et_core_esc_previously( $cache_plugin_message ),
		et_core_esc_previously( $browser_cache_message ),
		esc_html__( 'If you have cleared your plugin cache and browser cache, but still get this warning, then your files may be cached at the DNS or Server level. Contact your host or CDN for assistance.', 'et_builder' )
	);

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_failure_notification_modal' ) ) :
function et_builder_get_failure_notification_modal() {
	$warnings = et_builder_get_warnings();

	if ( false === $warnings ) {
		return '';
	}

	$messages = '';
	$i = 1;

	foreach( $warnings as $warning ) {
		$messages .= sprintf(
			'<p><strong>%1$s. </strong>%2$s</p>',
			esc_html( $i ),
			et_core_esc_previously( $warning )
		);

		$i++;
	}

	$output = sprintf(
		'<div class="et-core-modal-overlay et-builder-timeout et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>

				<div class="et-core-modal-content">
					<p><strong>%4$s</strong></p>

					%2$s
				</div>

				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et-core-modal-action">%3$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'Divi Builder Timeout', 'et_builder' ),
		et_core_esc_previously( $messages ),
		esc_html__( 'Reload The Builder', 'et_builder' ),
		esc_html__( 'Oops, it looks like the Divi Builder failed to load. Performing the following actions may help solve the problem.', 'et_builder' )
	);

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_no_builder_notification_modal' ) ) :
function et_builder_get_no_builder_notification_modal() {
	$output = sprintf(
		'<div class="et-core-modal-overlay et-builder-timeout et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>

				<div class="et-core-modal-content">
					<p><strong>%2$s</strong></p>
				</div>
			</div>
		</div>',
		esc_html__( 'Incompatible Post Type', 'et_builder' ),
		esc_html__( 'This post does not show the standard WordPress content area. Unfortunately, that means the Divi Builder cannot be used on this post.', 'et_builder' )
	);

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_exit_notification_modal' ) ) :
function et_builder_get_exit_notification_modal() {
	$output = sprintf(
		'<div class="et-core-modal-overlay et-core-modal-two-buttons et-builder-exit-modal et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>

				<div class="et-core-modal-content">
					<p>%2$s</p>
				</div>

				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et-core-modal-action et-core-modal-action-secondary">%3$s</a>
					<a href="#" class="et-core-modal-action">%4$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'You Have Unsaved Changes', 'et_builder' ),
		et_get_safe_localization( __( 'Your page contains changes that have not been saved. If you close the builder without saving, these changes will be lost. If you would like to leave the builder and save all changes, please select <strong>Save & Exit</strong>. If you would like to discard all recent changes, choose <strong>Discard & Exit</strong>.', 'et_builder' ) ),
		esc_html__( 'Discard & Exit', 'et_builder' ),
		esc_html__( 'Save & Exit', 'et_builder' )
	);

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_browser_autosave_notification_modal' ) ) :
function et_builder_get_browser_autosave_notification_modal() {
	$output = sprintf(
		'<div class="et-core-modal-overlay et-core-modal-two-buttons et-builder-autosave-modal et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>
				<div class="et-core-modal-content">
					<p>%2$s</p>
				</div>
				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et-core-modal-action et-core-modal-action-dont-restore et-core-modal-action-secondary">%3$s</a>
					<a href="#" class="et-core-modal-action et-core-modal-action-restore">%4$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'A Browser Backup Exists', 'et_builder' ),
		et_get_safe_localization( __( 'A browser backup exists for this post that is newer than  the version you are currently viewing. This backup was captured during your previous editing session, but you never saved it. Would you like to restore this backup and continue editing where you left off?', 'et_builder' ) ),
		esc_html__( "Don't Restore", 'et_builder' ), // left button
		esc_html__( 'Restore', 'et_builder' ) // right button
	);
	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_server_autosave_notification_modal' ) ) :
function et_builder_get_server_autosave_notification_modal() {
	$output = sprintf(
		'<div class="et-core-modal-overlay et-core-modal-two-buttons et-builder-autosave-modal et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>
				<div class="et-core-modal-content">
					<p>%2$s</p>
				</div>
				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et-core-modal-action et-core-modal-action-dont-restore et-core-modal-action-secondary">%3$s</a>
					<a href="#" class="et-core-modal-action et-core-modal-action-restore">%4$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'An Autosave Exists', 'et_builder' ),
		et_get_safe_localization( __( 'A recent autosave exists for this post that is newer than the version you are currently viewing. This autosave was captured during your previous editing session, but you never saved it. Would you like to restore this autosave and continue editing where you left off?', 'et_builder' ) ),
		esc_html__( "Don't Restore", 'et_builder' ), // left button
		esc_html__( 'Restore', 'et_builder' ) // right button
	);
	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_unsaved_notification_modal' ) ) :
function et_builder_get_unsaved_notification_modal() {
	$output = sprintf(
		'<div class="et-core-modal-overlay et-core-modal-two-buttons et-builder-unsaved-modal et-core-active">
			<div class="et-core-modal">
				<div class="et-core-modal-header">
					<h3 class="et-core-modal-title">%1$s</h3>
					<a href="#" class="et-core-modal-close" data-et-core-modal="close"></a>
				</div>
				<div class="et-core-modal-content">
					<p>%2$s</p>
					<p>%3$s</p>
					<p>%4$s</p>
				</div>
				<div class="et_pb_prompt_buttons">
					<br>
					<span class="spinner"></span>
					<a href="#" class="et-core-modal-action et-core-modal-action-secondary">%5$s</a>
					<a href="#" class="et-core-modal-action et-core-modal-action-primary">%6$s</a>
				</div>
			</div>
		</div>',
		esc_html__( 'Your Save Has Failed', 'et_builder' ),
		et_get_safe_localization( __( 'An error has occurred while saving your page. Various problems can cause a save to fail, such as a lack of server resources, firewall blockages, plugin conflicts or server misconfiguration. You can try saving again by clicking Try Again, or you can download a backup of your unsaved page by clicking Download Backup. Backups can be restored using the portability system while next editing your page.', 'et_builder' ) ),
		et_get_safe_localization( __( 'Contacting your host and asking them to increase the following PHP variables may help: memory_limit, max_execution_time, upload_max_filesize, post_max_size, max_input_time, max_input_vars. In addition, auditing your firewall error log (such as ModSecurity) may reveal false positives that are preventing saves from completing.', 'et_builder' ) ),
		et_get_safe_localization( __( 'Lastly, it is recommended that you temporarily disable all WordPress plugins and browser extensions and try to save again to determine if something is causing a conflict.', 'et_builder' ) ),
		esc_html__( 'Try Again', 'et_builder' ),
		esc_html__( 'Download Backup', 'et_builder' )
	);
	return $output;
}
endif;

if ( ! function_exists( 'et_builder_page_creation_modal' ) ) :
	function et_builder_page_creation_modal() {
		return '<div class="et-pb-page-creation-card <%= option.className %>" data-action="<%= id %>">
			<div class="et-pb-page-creation-content">
				<img src="<%= option.images_uri %>/<%= option.imgSrc %>" data-src="<%= option.images_uri %>/<%= option.imgSrc %>" data-hover="<%= option.images_uri %>/<%= option.imgHover %>" alt="<%= option.titleText %>" />
				<div class="et-pb-page-creation-text">
					<h3><%= option.titleText %></h3>
					<p><%= option.descriptionText %></p>
				</div>
			</div>
			<a href="#" class="et-pb-page-creation-link"><%= option.buttonText %></a>
		</div>';
	}
endif;

if ( ! function_exists( 'et_builder_get_warnings' ) ) :
function et_builder_get_warnings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$warnings = array();


	// WP_DEBUG check
	if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
		$warnings[] = sprintf(
			'%1$s. <a href="https://codex.wordpress.org/Debugging_in_WordPress" class="et_builder_modal_action_button" target="_blank">%2$s</a>',
			esc_html__( 'You have WP_DEBUG enabled. Please disable this setting in wp-config.php', 'et_builder' ),
			esc_html__( 'Disable Debug Mode', 'et_builder' )
		);
	}


	// Plugins check
	$third_party_plugins_active = false;

	$excluded_plugins = array(
		'wordpress-importer/wordpress-importer.php',
		'divi-builder/divi-builder.php',
		'elegant-themes-updater/elegant-themes-updater.php',
		'et-security-patcher/et-security-patcher.php',
	);

	$active_plugins = get_option( 'active_plugins' );

	if ( is_array( $active_plugins ) && ! empty( $active_plugins ) ) {
		foreach ( $active_plugins as $plugin ) {
			if ( in_array( $plugin, $excluded_plugins ) ) {
				continue;
			}

			$third_party_plugins_active = true;

			break;
		}
	}

	if ( $third_party_plugins_active ) {
		$warnings[] = sprintf(
			'%1$s <a href="%3$s" class="et_builder_modal_action_button" target="_blank">%2$s</a>',
			esc_html__( 'You are using third party plugins. Try disabling each plugin to see if one is causing a conflict.', 'et_builder' ),
			esc_html__( 'Manage Your Plugins', 'et_builder' ),
			esc_url( admin_url( 'plugins.php' ) )
		);
	}


	// WordPress update check
	require_once( ABSPATH . 'wp-admin/includes/update.php' );

	$updates = get_core_updates();

	if ( isset( $updates[0]->response ) && 'latest' !== $updates[0]->response ) {
		$warnings[] = sprintf(
			'%1$s <a href="%3$s" class="et_builder_modal_action_button" target="_blank">%2$s</a>',
			esc_html__( 'You are using an outdated version of WordPress. Please upgrade.', 'et_builder' ),
			esc_html__( 'Upgrade WordPress', 'et_builder' ),
			esc_url( admin_url( 'update-core.php' ) )
		);
	}


	// Memory check
	global $et_current_memory_limit;

	if ( ! empty( $et_current_memory_limit ) && intval( $et_current_memory_limit ) < 128 ) {
		$class = ' et_builder_increase_memory';

		$warnings[] = sprintf(
			'%1$s. <a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" class="et_builder_modal_action_button%3$s" target="_blank">%2$s</a>',
			esc_html__( 'Please increase your PHP Memory Limit. You can return the value to default via the Divi Theme Options in the future', 'et_builder' ),
			esc_html__( 'Increase Your Memory Limit Now', 'et_builder' ),
			esc_attr( $class )
		);
	}


	// Version check
	$et_update_themes = get_site_transient( 'et_update_themes' );

	if ( is_object( $et_update_themes ) && isset( $et_update_themes->response ) ) {
		$theme_info = wp_get_theme();

		if ( is_child_theme() ) {
			$theme_info = wp_get_theme( $theme_info->parent_theme );
		}

		$name    = $theme_info->get( 'Name' );
		$version = $theme_info->get( 'Version' );

		if ( isset( $et_update_themes->response[ $name ] ) && isset( $et_update_themes->response[ $name ]['new_version'] ) && version_compare( $version, $et_update_themes->response[ $name ]['new_version'], '<' ) ) {
			$warnings[] = sprintf(
				'%1$s <a href="%3$s" class="et_builder_modal_action_button" target="_blank">%2$s</a>',
				sprintf(
					esc_html__( 'You are using an outdated version of the theme. The latest version is %1$s', 'et_builder' ),
					esc_html( $et_update_themes->response[ $name ]['new_version'] )
				),
				esc_html__( 'Upgrade', 'et_builder' ),
				esc_url( admin_url( 'themes.php' ) )
			);
		}
	}

	if ( empty( $warnings ) ) {
		return false;
	}

	return $warnings;
}
endif;

if ( ! function_exists( 'et_increase_memory_limit' ) ) :
function et_increase_memory_limit() {
	if ( ! is_admin() ) {
		return false;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}

	// proceed only if current memory limit < 256
	if ( et_core_get_memory_limit() >= 256 ) {
		return true;
	}

	$result = @ini_set( 'memory_limit', '256M' );

	return ! empty( $result );
}
endif;

if ( ! function_exists( 'et_maybe_increase_memory_limit' ) ) :
function et_maybe_increase_memory_limit() {
	global $pagenow;

	if ( ! is_admin() ) {
		return;
	}

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( empty( $pagenow ) ) {
		return;
	}

	// increase memory limit on Edit Post page only
	if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
		return;
	}

	/**
	 * check if a user clicked "Increase Memory Limit" button
	 * in the "Failure Notification" modal window
	 */
	if ( ! et_should_memory_limit_increase() ) {
		return;
	}

	et_increase_memory_limit();
}
endif;
add_action( 'admin_init', 'et_maybe_increase_memory_limit' );

if ( ! function_exists( 'et_should_memory_limit_increase' ) ) :
function et_should_memory_limit_increase() {
	if ( '1' === ( $memory_limit = et_get_option( 'set_memory_limit' ) ) ) {
		return true;
	}

	return false;
}
endif;

if ( ! function_exists( 'et_reset_memory_limit_increase_setting' ) ) :
function et_reset_memory_limit_increase_setting() {
	wp_enqueue_script( 'et-builder-reset-memory-limit-increase', ET_BUILDER_URI . '/scripts/reset_memory_limit_increase_setting.js', array( 'jquery' ), ET_BUILDER_VERSION, true );
	wp_localize_script( 'et-builder-reset-memory-limit-increase', 'et_reset_memory_limit_increase', array(
		'et_builder_reset_memory_limit_nonce' => wp_create_nonce( 'et_builder_reset_memory_limit_nonce' ),
	) );

	printf(
		'<button class="et_disable_memory_limit_increase button button-primary button-large">%1$s</button>',
		esc_html__( 'Disable Memory Limit Increase' )
	);
}
endif;

if ( ! function_exists( 'et_pb_detect_cache_plugins' ) ) :
/**
 * Detect the activated cache plugins and return the link to plugin options and return its page link or false
 * @return string or bool
 */
function et_pb_detect_cache_plugins() {
	if ( function_exists( 'edd_w3edge_w3tc_activate_license' ) ) {
		return array(
			'name' => 'W3 Total Cache',
			'page' => 'admin.php?page=w3tc_pgcache',
		);
	}

	if ( function_exists( 'wpsupercache_activate' ) ) {
		return array(
			'name' => 'WP Super Cache',
			'page' => 'options-general.php?page=wpsupercache',
		);
	}

	if ( class_exists( 'HyperCache' ) ) {
		return array(
			'name' => 'Hyper Cache',
			'page' => 'options-general.php?page=hyper-cache%2Foptions.php',
		);
	}

	if ( class_exists( '\zencache\plugin' ) ) {
		return array(
			'name' => 'ZenCache',
			'page' => 'admin.php?page=zencache',
		);
	}

	if ( class_exists( 'WpFastestCache' ) ) {
		return array(
			'name' => 'WP Fastest Cache',
			'page' => 'admin.php?page=WpFastestCacheOptions',
		);
	}

	if ( '1' === get_option( 'wordfenceActivated' ) ) {
		// Wordfence removed their support of Falcon cache in v6.2.8, so we'll
		// just check against their `cacheType` setting (if it exists).
		if ( class_exists( 'wfConfig' ) && 'falcon' === wfConfig::get( 'cacheType' ) ) {
			return array(
				'name' => 'Wordfence',
				'page' => 'admin.php?page=WordfenceSitePerf',
			);
		}
	}

	if ( function_exists( 'cachify_autoload' ) ) {
		return array(
			'name' => 'Cachify',
			'page' => 'options-general.php?page=cachify',
		);
	}

	if ( class_exists( 'FlexiCache' ) ) {
		return array(
			'name' => 'FlexiCache',
			'page' => 'options-general.php?page=flexicache',
		);
	}

	if ( function_exists( 'rocket_init' ) ) {
		return array(
			'name' => 'WP Rocket',
			'page' => 'options-general.php?page=wprocket',
		);
	}

	if ( function_exists( 'cloudflare_init' ) ) {
		return array(
			'name' => 'CloudFlare',
			'page' => 'options-general.php?page=cloudflare',
		);
	}

	return false;
}
endif;

function et_pb_force_regenerate_templates() {
	// add option to indicate that templates cache should be updated in case of term added/removed/updated
	et_update_option( 'et_pb_clear_templates_cache', true );

	// Delete cached definitions / helpers
	et_fb_delete_builder_assets();
}

add_action( 'created_term', 'et_pb_force_regenerate_templates' );
add_action( 'edited_term', 'et_pb_force_regenerate_templates' );
add_action( 'delete_term', 'et_pb_force_regenerate_templates' );

//@Todo we should remove this hook after BB is retired
//purge BB microtemplates cache after Theme Customizer changes
add_action( 'customize_save_after', 'et_pb_force_regenerate_templates' );

function et_pb_ab_get_current_ab_module_id( $test_id, $subject_index = false ) {
	$all_subjects = false !== ( $all_subjects_raw = get_post_meta( $test_id, '_et_pb_ab_subjects' , true ) ) ? explode( ',', $all_subjects_raw ) : array();

	if ( false === $subject_index ) {
		$current_subject_index = false !== ( $saved_next_subject = get_post_meta( $test_id, '_et_pb_ab_next_subject' , true ) ) ? (int) $saved_next_subject : 0;
	} else {
		$current_subject_index = $subject_index;
	}

	if ( empty( $all_subjects ) ) {
		return 0;
	}

	if ( ! isset( $all_subjects[ $current_subject_index ] ) ) {
		return $all_subjects[0];
	}

	return $all_subjects[ $current_subject_index ];
}

/**
 * Increment current subject index value on post meta
 *
 * @param int post ID
 */
function et_pb_ab_increment_current_ab_module_id( $test_id ) {
	global $wpdb;

	// Get subjects and current subject index
	$all_subjects_raw      = get_post_meta( $test_id, '_et_pb_ab_subjects' , true );
	$all_subjects          = false !== $all_subjects_raw ? explode( ',', $all_subjects_raw ) : array();
	$saved_next_subject    = get_post_meta( $test_id, '_et_pb_ab_next_subject' , true );
	$current_subject_index = false !== $saved_next_subject ? (int) $saved_next_subject : 0;

	if ( empty( $all_subjects ) ) {
		return;
	}

	// increment the index of next subject, set to 0 if it's a last subject in the list
	$next_subject_index = ( count( $all_subjects ) - 1 ) < ( $current_subject_index + 1 ) ? 0 : $current_subject_index + 1;

	update_post_meta( $test_id, '_et_pb_ab_next_subject' , $next_subject_index );
}

/**
 * Add the record into AB Testing log table
 *
 * @return void
 */
function et_pb_add_stats_record( $stats_data_array ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'et_divi_ab_testing_stats';

	$record_date = current_time( 'mysql' );

	// sanitize and set vars
	$test_id = intval( $stats_data_array['test_id'] );
	$subject_id = intval( $stats_data_array['subject_id'] );
	$record_type = sanitize_text_field( $stats_data_array['record_type'] );
	$record_date = sanitize_text_field( $record_date );

	// Check visitor cookie and do not proceed if event already logged for current visitor
	if ( et_pb_ab_get_visitor_cookie( $test_id, $record_date ) ) {
		return;
	}

	$wpdb->insert(
		$table_name,
		array(
			'record_date' => $record_date,
			'test_id'     => $test_id,
			'subject_id'  => $subject_id,
			'event'       => $record_type,
		),
		array(
			'%s', // record_date
			'%d', // test_id
			'%d', // subject_id
			'%s', // event
		)
	);
}

/**
 * Set AB Testing formatted cookie
 *
 * @param int    post ID
 * @param string record type
 * @param mixed  cookie value
 *
 * @return bool|mixed
 */
function et_pb_ab_set_visitor_cookie( $post_id, $record_type, $value = true ) {
	$unique_test_id = get_post_meta( $post_id, '_et_pb_ab_testing_id', true );
	$cookie_name    = sanitize_text_field( "et_pb_ab_{$record_type}_{$post_id}{$unique_test_id}" );

	return setcookie( $cookie_name, $value );
}

/**
 * Get AB Testing formatted cookie
 *
 * @param int    post ID
 * @param string record type
 *
 * @return bool|mixed
 */
function et_pb_ab_get_visitor_cookie( $post_id, $record_type ) {
	$unique_test_id = get_post_meta( $post_id, '_et_pb_ab_testing_id', true );
	$cookie_name    = "et_pb_ab_{$record_type}_{$post_id}{$unique_test_id}";

	return isset( $_COOKIE[ $cookie_name ] ) ? $_COOKIE[ $cookie_name ] : false;
}

/**
 * Get subjects of particular post / AB Testing
 *
 * @param int    post id
 * @param string array|string type of output
 * @param mixed  string|bool  prefix that should be prepended
 */
function et_pb_ab_get_subjects( $post_id, $type = 'array', $prefix = false, $is_cron_task = false ) {
	$subjects_data = get_post_meta( $post_id, '_et_pb_ab_subjects', true );
	$fb_enabled = function_exists( 'et_fb_enabled' ) ? et_fb_enabled() : false;

	// Get autosave/draft subjects if post hasn't been published
	if ( ! $is_cron_task && ! $subjects_data && $fb_enabled && 'publish' !== get_post_status() ) {
		$subjects_data = get_post_meta( $post_id, '_et_pb_ab_subjects_draft', true );
	}

	// If user wants string
	if ( 'string' === $type ) {
		return $subjects_data;
	}

	// Convert into array
	$subjects = explode(',', $subjects_data );

	if ( ! empty( $subjects ) && $prefix ) {

		$prefixed_subjects = array();

		// Loop subject, add prefix
		foreach ( $subjects as $subject ) {
			$prefixed_subjects[] = $prefix . (string) $subject;
		}

		return $prefixed_subjects;
	}

	return $subjects;
}

/**
 * Unhashed hashed subject id
 *
 * @param int    post ID
 * @param string hashed subject id
 *
 * @return string subject ID
 */
function et_pb_ab_unhashed_subject_id( $post_id, $hashed_subject_id ) {
	if ( ! $post_id || ! $hashed_subject_id ) {
		return false;
	}

	$ab_subjects = et_pb_ab_get_subjects( $post_id );
	$ab_hash_key = defined( 'NONCE_SALT' ) ? NONCE_SALT : 'default-divi-hash-key';
	$subject_id  = false;

	// Compare subjects against hashed subject id found on cookie to verify whether cookie value is valid or not
	foreach ( $ab_subjects as $ab_subject ) {
		// Valid subject_id is found
		if ( hash_hmac( 'md5', $ab_subject, $ab_hash_key ) === $hashed_subject_id ) {
			$subject_id = $ab_subject;

			// no need to continue
			break;
		}
	}

	// If no valid subject found, get the first one
	if ( ! $subject_id && isset( $ab_subjects[0] ) ) {
		$subject_id = $ab_subjects[0];
	}

	return $subject_id;
}

function et_pb_ab_get_subject_id() {
	if ( ! isset( $_POST['et_frontend_nonce'] ) || ! wp_verify_nonce( $_POST['et_frontend_nonce'], 'et_frontend_nonce' ) ) {
		die( -1 );
	}

	$test_id              = intval( $_POST['et_pb_ab_test_id'] );
	$hashed_subject_id    = et_pb_ab_get_visitor_cookie( $test_id, 'view_page' );
	$current_ab_module_id = et_pb_ab_unhashed_subject_id( $test_id, $hashed_subject_id );

	// retrieve the cached subjects HTML
	$subjects_cache = get_post_meta( $test_id, 'et_pb_subjects_cache', true );

	$result = array(
		'id'      => $current_ab_module_id,
		'content' => isset( $subjects_cache[ $current_ab_module_id ] ) ? $subjects_cache[ $current_ab_module_id ] : '',
	);

	die( wp_json_encode( $result ) );
}
add_action( 'wp_ajax_et_pb_ab_get_subject_id', 'et_pb_ab_get_subject_id' );
add_action( 'wp_ajax_nopriv_et_pb_ab_get_subject_id', 'et_pb_ab_get_subject_id' );

/**
 * Register Builder Portability.
 *
 * @since 2.7.0
 */
function et_pb_register_builder_portabilities() {
	global $shortname;

	// Don't overwrite global.
	$_shortname = empty( $shortname ) ? 'divi' : $shortname;

	// Make sure the Portability is loaded.
	et_core_load_component( 'portability' );

	if ( current_user_can( 'edit_theme_options' ) ) {
		// Register the Roles Editor portability.
		et_core_portability_register( 'et_pb_roles', array(
			'name'   => esc_html__( 'Divi Role Editor Settings', 'et_builder' ),
			'type'   => 'options',
			'target' => 'et_pb_role_settings',
			'view'   => ( isset( $_GET['page'] ) && $_GET['page'] === "et_{$_shortname}_role_editor" ), // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		) );

		// Register the Builder Layouts Post Type portability.
		et_core_portability_register( 'et_builder_layouts', array(
			'name'   => esc_html__( 'Divi Builder Layouts', 'et_builder' ),
			'type'   => 'post_type',
			'target' => ET_BUILDER_LAYOUT_POST_TYPE,
			'view'   => ( isset( $_GET['post_type'] ) && $_GET['post_type'] === ET_BUILDER_LAYOUT_POST_TYPE ), // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		) );
	}

	if ( current_user_can( 'edit_posts' ) ) {
		// Register the Builder individual layouts portability.
		et_core_portability_register( 'et_builder', array(
			'name' => esc_html__( 'Divi Builder Layout', 'et_builder' ),
			'type' => 'post',
			'view' => ( function_exists( 'et_builder_should_load_framework' ) && et_builder_should_load_framework() ),
		) );
	}
}
add_action( 'admin_init', 'et_pb_register_builder_portabilities' );

/**
 * Modify the portability export WP query.
 *
 * @since To define
 *
 * @return string New query.
 */
function et_pb_modify_portability_export_wp_query( $query ) {
	// Exclude predefined layout from export.
	return array_merge( $query, array(
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => '_et_pb_predefined_layout',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_et_pb_predefined_layout',
				'value'   => 'on',
				'compare' => 'NOT LIKE',
			),
		),
	) );
}
add_filter( 'et_core_portability_export_wp_query_et_builder_layouts', 'et_pb_modify_portability_export_wp_query' );

/**
 * Check whether current page is pagebuilder preview page
 * @return bool
 */
function is_et_pb_preview() {
	global $wp_query;
	return ( 'true' === $wp_query->get( 'et_pb_preview' ) && isset( $_GET['et_pb_preview_nonce'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
}

if ( ! function_exists( 'et_pb_is_pagebuilder_used' ) ) :

function et_pb_is_pagebuilder_used( $page_id = 0 ) {
	if ( 0 === $page_id ) {
		$page_id = et_core_page_resource_get_the_ID();
	}

	return (
		'on' === get_post_meta( $page_id, '_et_pb_use_builder', true ) ||
		// Divi layout post type always use the builder
		'et_pb_layout' === get_post_type( $page_id ) ||
		// Extra Category post type always use the builder
		'layout' === get_post_type( $page_id )
	);
}
endif;

if ( ! function_exists( 'et_fb_is_enabled' ) ) :
/**
 * @internal NOTE: Don't use this from outside builder code! {@see et_core_is_fb_enabled()}.
 *
 * @return bool
 */
function et_fb_is_enabled( $post_id = false ) {
	if ( ! $post_id ) {
		global $post;

		$post_id = isset( $post->ID ) ? $post->ID : false;
	}

	if ( is_admin() ) {
		return false;
	}

	if ( is_customize_preview() ) {
		return false;
	}

	if ( ! $post_id ) {
		return false;
	}

	if ( empty( $_GET['et_fb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		return false;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}

	if ( ! et_pb_is_pagebuilder_used( $post_id ) ) {
		return false;
	}

	if ( ! et_pb_is_allowed( 'use_visual_builder' ) ) {
		return false;
	}

	return true;
}
endif;

if ( ! function_exists( 'et_fb_auto_activate_builder' ) ) :
function et_fb_auto_activate_builder() {
	$post_id = get_the_ID();

	if (
		! is_admin() &&
		$post_id &&
		current_user_can( 'edit_post', $post_id ) &&
		isset( $_GET['et_fb_activation_nonce'] ) &&
		wp_verify_nonce( $_GET['et_fb_activation_nonce' ], 'et_fb_activation_nonce_' . get_the_ID() )
	) {
		$set_content  = et_builder_set_content_activation( $post_id );
		$post_url     = get_permalink( $post_id );
		$redirect_url = $set_content ? add_query_arg( 'et_fb', '1', $post_url ) : $post_url;

		wp_redirect( $redirect_url );
		exit();
	}
}
endif;
add_action( 'template_redirect', 'et_fb_auto_activate_builder' );

function et_builder_set_content_activation( $post_id = false ) {
	$_post = get_post( $post_id );

	if ( ! $post_id || ! $_post || ! is_object( $_post ) ) {
		return false;
	}

	// Update builder status
	$activate_builder = update_post_meta( $post_id, '_et_pb_use_builder', 'on' );

	if ( false === $activate_builder ) {
		return false;
	}

	// Set page creation flow flag to on.
	update_post_meta( $post_id, '_et_pb_show_page_creation', 'on' );

	// If content already has a section, it means builder is active and activation has to be
	// skipped to avoid nested and unwanted builder structure
	if ( has_shortcode( $_post->post_content, 'et_pb_section' ) ) {
		return true;
	}

	// Save old content
	$saved_old_content = get_post_meta( $post_id, '_et_pb_old_content', true );
	$save_old_content = update_post_meta( $post_id, '_et_pb_old_content', $_post->post_content );

	if ( false === $save_old_content && $saved_old_content !== $_post->post_content && '' !== $_post->post_content ) {
		return false;
	}

	$text_module = '' !== $_post->post_content ? '[et_pb_text admin_label="Text"]'. $_post->post_content .'[/et_pb_text]' : '';

	// Re-format content
	$updated_content = '[et_pb_section admin_label="section"]
		[et_pb_row admin_label="row"]
			[et_pb_column type="4_4"]'. $text_module .'[/et_pb_column]
		[/et_pb_row]
	[/et_pb_section]';

	// Update post_content
	$_post->post_content = $updated_content;

	// Update post
	$update_post = wp_update_post( $_post );

	if ( 0 < $update_post ) {
		setup_postdata( $_post );
	}

	return 0 < $update_post;
}

if ( ! function_exists( 'et_builder_get_font_family' ) ) :
function et_builder_get_font_family( $font_name, $use_important = false ) {
	$user_fonts = et_builder_get_custom_fonts();
	$fonts = isset( $user_fonts[ $font_name ] ) ? $user_fonts : et_builder_get_fonts();
	$removed_fonts_mapping = et_builder_old_fonts_mapping();

	$font_style = $font_weight = '';

	$font_name_ms = isset( $fonts[ $font_name ] ) && isset( $fonts[ $font_name ]['add_ms_version'] ) ? "'{$font_name} MS', " : "";

	if ( isset( $removed_fonts_mapping[ $font_name ] ) && isset( $removed_fonts_mapping[ $font_name ]['parent_font'] ) ) {
		$font_style = $removed_fonts_mapping[ $font_name ]['styles'];
		$font_name = $removed_fonts_mapping[ $font_name ]['parent_font'];
	}

	if ( '' !== $font_style ) {
		$font_weight = sprintf( ' font-weight: %1$s;', esc_html( $font_style ) );
	}

	$style = sprintf( 'font-family: \'%1$s\', %5$s%2$s%3$s;%4$s',
		esc_html( $font_name ),
		isset( $fonts[ $font_name ] ) ? et_builder_get_websafe_font_stack( $fonts[ $font_name ]['type'] ) : 'sans-serif',
		( $use_important ? ' !important' : '' ),
		$font_weight,
		$font_name_ms
	);

	return $style;
}
endif;

if ( ! function_exists( 'et_builder_get_fonts' ) ) :
function et_builder_get_fonts( $settings = array() ) {
	// Only return websafe fonts if google fonts disabled
	if ( ! et_core_use_google_fonts() ) {
		return et_builder_get_websafe_fonts();
	}

	$defaults = array(
		'prepend_standard_fonts' => true,
	);

	$settings = wp_parse_args( $settings, $defaults );

	$fonts = $settings['prepend_standard_fonts']
		? array_merge( et_builder_get_websafe_fonts(), et_builder_get_google_fonts() )
		: array_merge( et_builder_get_google_fonts(), et_builder_get_websafe_fonts() );

	return $fonts;
}
endif;

if ( ! function_exists( 'et_builder_get_websafe_font_stack' ) ) :
function et_builder_get_websafe_font_stack( $type = 'sans-serif' ) {
	$font_stack = $type;

	switch ( $type ) {
		case 'sans-serif':
			$font_stack = 'Helvetica, Arial, Lucida, sans-serif';
			break;
		case 'serif':
			$font_stack = 'Georgia, "Times New Roman", serif';
			break;
		case 'cursive':
			$font_stack = 'cursive';
			break;
	}

	return $font_stack;
}
endif;

if ( ! function_exists( 'et_builder_get_websafe_fonts' ) ) :
function et_builder_get_websafe_fonts() {
	$websafe_fonts = array(
		'Georgia' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,greek,latin',
			'type'			=> 'serif',
		),
		'Times New Roman' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'arabic,cyrillic,greek,hebrew,latin',
			'type'			=> 'serif',
		),
		'Arial' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'arabic,cyrillic,greek,hebrew,latin',
			'type'			=> 'sans-serif',
		),
		'Trebuchet' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,latin',
			'type'			=> 'sans-serif',
			'add_ms_version'=> true,
		),
		'Verdana' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,latin',
			'type'			=> 'sans-serif',
		),
	);

	$_websafe_fonts = array();

	foreach ( $websafe_fonts as $font_name => $settings ) {
		$settings['standard'] = true;

		$_websafe_fonts[ $font_name ] = $settings;
	}

	$websafe_fonts = $_websafe_fonts;

	return apply_filters( 'et_websafe_fonts', $websafe_fonts );
}
endif;

if ( ! function_exists( 'et_builder_get_font_weight_list' ) ) :
function et_builder_get_font_weight_list() {
	$default_font_weights_list = array(
		'100' => esc_html__( 'Thin', 'et_builder' ),
		'200' => esc_html__( 'Ultra Light', 'et_builder' ),
		'300' => esc_html__( 'Light', 'et_builder' ),
		'400' => esc_html__( 'Regular', 'et_builder' ),
		'500' => esc_html__( 'Medium', 'et_builder' ),
		'600' => esc_html__( 'Semi Bold', 'et_builder' ),
		'700' => esc_html__( 'Bold', 'et_builder' ),
		'800' => esc_html__( 'Ultra Bold', 'et_builder' ),
		'900' => esc_html__( 'Heavy', 'et_builder' ),
	);

	return apply_filters( 'et_builder_all_font_weights', $default_font_weights_list );
}
endif;

if ( ! function_exists( 'et_builder_get_custom_fonts' ) ) :
function et_builder_get_custom_fonts() {
	$all_custom_fonts = get_option( 'et_uploaded_fonts', array() );
	return apply_filters( 'et_builder_custom_fonts', $all_custom_fonts );
}
endif;

function et_builder_old_fonts_mapping() {
	return array(
		'Raleway Light' => array(
			'parent_font' => 'Raleway',
			'styles'      => '300',
		),
		'Roboto Light' => array(
			'parent_font' => 'Roboto',
			'styles'      => '100',
		),
		'Source Sans Pro Light' => array(
			'parent_font' => 'Source Sans Pro',
			'styles'      => '300',
		),
		'Lato Light' => array(
			'parent_font' => 'Lato',
			'styles'      => '300',
		),
		'Open Sans Light' => array(
			'parent_font' => 'Open Sans',
			'styles'      => '300',
		),
	);
}

if ( ! function_exists( 'et_builder_google_fonts_sync' ) ) :
function et_builder_google_fonts_sync() {
	$google_api_key = et_pb_get_google_api_key();

	if ( '' === $google_api_key || ! et_core_use_google_fonts() ) {
		return;
	}

	$google_fonts_api_url = sprintf( 'https://www.googleapis.com/webfonts/v1/webfonts?key=%1$s', $google_api_key );
	$google_fonts_response = wp_remote_get( esc_url_raw( $google_fonts_api_url ) );

	$all_google_fonts = is_array( $google_fonts_response ) ? json_decode( wp_remote_retrieve_body( $google_fonts_response ), true ) : array();

	if ( empty( $all_google_fonts ) || empty( $all_google_fonts['items'] ) ) {
		return;
	}

	$google_fonts = array();

	foreach ( $all_google_fonts['items'] as $font_data ) {
		$google_fonts[ sanitize_text_field( $font_data['family'] ) ] = array(
			'styles'        => sanitize_text_field( implode( ',', $font_data['variants'] ) ),
			'character_set' => sanitize_text_field( implode( ',', $font_data['subsets'] ) ),
			'type'          => sanitize_text_field( $font_data['category'] ),
		);
	}

	if ( ! empty( $google_fonts ) ) {
		// save google fonts
		update_option( 'et_google_fonts_cache', $google_fonts );
	}
}
endif;
add_action( 'et_builder_fonts_cron', 'et_builder_google_fonts_sync' );

if ( ! function_exists( 'et_builder_schedule_fonts_sync' ) ) :
function et_builder_schedule_fonts_sync() {
	// schedule daily event to sync google fonts
	if ( ! wp_next_scheduled( 'et_builder_fonts_cron', array( 'interval' => 'daily' ) ) ) {
		wp_schedule_event( time(), 'daily', 'et_builder_fonts_cron', array( 'interval' => 'daily' ) );
	}
}
endif;

if ( ! function_exists( 'et_builder_get_google_fonts' ) ) :
function et_builder_get_google_fonts() {
	// Google Fonts disabled
	if ( ! et_core_use_google_fonts() ) {
		return array();
	}

	$google_fonts_cache = get_option( 'et_google_fonts_cache', array() );

	if ( ! empty( $google_fonts_cache ) ) {
		// Use cache if it's not empty
		return apply_filters( 'et_builder_google_fonts', $google_fonts_cache );
	}
	require_once( ET_BUILDER_DIR . 'google-fonts-data.php' );

	// schedule Google fonts sync
	et_builder_schedule_fonts_sync();

	// use hardcoded google fonts as fallback if no cache exists
	return apply_filters( 'et_builder_google_fonts', et_pb_get_saved_google_fonts() );
}
endif;


if ( ! function_exists( 'et_pb_register_global_js' ) ) :
function et_pb_register_global_js() {
	wp_register_script( 'et_pb_admin_global_js', ET_BUILDER_URI . '/scripts/admin_global_functions.js', array(), ET_BUILDER_VERSION, true );
}
endif;
add_action( 'admin_enqueue_scripts', 'et_pb_register_global_js' );

/**
 * Use correct conditional tag for compute callback. Compute callback can use actual conditional tag
 * on page load. Compute callback relies on passed conditional tag params for update due to the
 * ajax-admin.php nature
 *
 * @param string conditional tag name
 * @param array  all conditional tags params
 * @return bool  conditional tag value
 */
function et_fb_conditional_tag( $name, $conditional_tags ) {

	if ( defined( 'DOING_AJAX' ) && isset( $conditional_tags[ $name ] ) ) {
		return $conditional_tags[ $name ] === 'true' ? true : false;
	}

	return is_callable( $name ) ? $name() : false;
}

/*
 * Retrieves the content of saved modules and process the shortcode into array.
 *
 */
function et_fb_get_saved_templates() {
	if ( ! wp_verify_nonce( $_POST['et_fb_retrieve_library_modules_nonce'], 'et_fb_retrieve_library_modules_nonce' ) ){
		die(-1);
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	$layout_type = ! empty( $_POST['et_layout_type'] ) ? sanitize_text_field( $_POST['et_layout_type'] ) : 'layout';
	$module_width = ! empty( $_POST['et_module_width'] ) && 'module' === $layout_type ? sanitize_text_field( $_POST['et_module_width'] ) : '';
	$is_global = ! empty( $_POST['et_is_global'] ) ? sanitize_text_field( $_POST['et_is_global'] ) : 'all';
	$specialty_query = ! empty( $_POST['et_specialty_columns'] ) && 'row' === $layout_type ? sanitize_text_field( $_POST['et_specialty_columns'] ) : '0';
	$post_type = ! empty( $_POST['et_post_type'] ) ? sanitize_text_field( $_POST['et_post_type'] ) : 'post';
	$start_from = ! empty( $_POST['et_templates_start_page'] ) ? sanitize_text_field( $_POST['et_templates_start_page'] ) : 0;

	if ( 'all' === $is_global ) {
		$templates_data_regular = et_pb_retrieve_templates( $layout_type, $module_width, 'not_global', $specialty_query, $post_type, '', array( $start_from, 25 ) );
		$templates_data_global = et_pb_retrieve_templates( $layout_type, $module_width, 'global', $specialty_query, $post_type, '', array( $start_from, 25 ) );
		$templates_data = array_merge( $templates_data_regular, $templates_data_global );
	} else {
		$templates_data = et_pb_retrieve_templates( $layout_type, $module_width, $is_global, $specialty_query, $post_type, array( $start_from, 50 ) );
	}

	$templates_data_processed = $templates_data;
	$next_page = 'none';

	if ( 0 !== $start_from && empty( $templates_data ) ) {
		$templates_data_processed = array();
	} else {
		if ( empty( $templates_data ) ) {
			$templates_data_processed = array( 'error' => esc_html__( 'You have not saved any items to your Divi Library yet. Once an item has been saved to your library, it will appear here for easy use.', 'et_builder' ) );
		} else {
			foreach( $templates_data as $index => $data ) {
				$templates_data_processed[ $index ]['shortcode'] = et_fb_process_shortcode( $data['shortcode'] );

				if ( 'global' === $templates_data_processed[ $index ]['is_global'] && 'module' === $templates_data_processed[ $index ]['layout_type'] ) {
					$templates_data_processed[ $index ]['shortcode'][0]['unsyncedGlobalSettings'] = $templates_data_processed[ $index ]['unsynced_options'];

					if ( empty( $templates_data_processed[ $index ]['unsynced_options'] ) && isset( $templates_data_processed[ $index ]['shortcode'][0]['attrs']['saved_tabs'] ) && 'all' !== $templates_data_processed[ $index ]['shortcode'][0]['attrs']['saved_tabs'] ) {
						$templates_data_processed[ $index ]['shortcode'][0]['unsyncedGlobalSettings'] = et_pb_get_unsynced_legacy_options( $post_type, $templates_data_processed[ $index ]['shortcode'][0] );
					}
				}
			}
			$next_page = 'all' === $is_global ? $start_from + 25 : $start_from + 50;
		}
	}

	$json_templates = wp_json_encode( array( 'templates_data' => $templates_data_processed, 'next_page' => $next_page ) );

	die( et_core_esc_previously( $json_templates ) );
}
add_action( 'wp_ajax_et_fb_get_saved_templates', 'et_fb_get_saved_templates' );

function et_pb_get_supported_font_formats() {
	return apply_filters( 'et_pb_supported_font_formats', array( 'ttf', 'otf' ) );
}

function et_pb_process_custom_font() {
	et_core_security_check( 'upload_files', 'et_fb_upload_font_nonce' );

	// action "add" or "remove"
	$action = ! empty( $_POST['et_pb_font_action'] ) ? sanitize_text_field( $_POST['et_pb_font_action'] ) : 'save';

	if ( 'add' === $action ) {
		$supported_font_files = et_pb_get_supported_font_formats();
		$custom_font_name = ! empty( $_POST['et_pb_font_name'] ) ? sanitize_text_field( $_POST['et_pb_font_name'] ) : '';
		$custom_font_settings = ! empty( $_POST['et_pb_font_settings'] ) ? sanitize_text_field( $_POST['et_pb_font_settings'] ) : '';
		$custom_font_settings_processed = '' === $custom_font_settings ? array() : json_decode( str_replace( '\\', '', $custom_font_settings ), true );
		$fonts_array = array();

		foreach ( $supported_font_files as $format ) {
			if ( isset( $_FILES['et_pb_font_file_' . $format ] ) ) {
				$fonts_array[ $format ] = $_FILES['et_pb_font_file_' . $format ];
			}
		}

		die( wp_json_encode( et_pb_add_font( $fonts_array, $custom_font_name, $custom_font_settings_processed ) ) );
	} elseif ( 'remove' === $action ) {
		$font_slug = ! empty( $_POST['et_pb_font_name'] ) ? sanitize_text_field( $_POST['et_pb_font_name'] ) : '';
		die( wp_json_encode( et_pb_remove_font( $font_slug ) ) );
	}
}

add_action( 'wp_ajax_et_pb_process_custom_font', 'et_pb_process_custom_font' );

/**
 * Drag and Droploader :: Process Media
 */
if ( ! function_exists( 'et_builder_droploader_process') ):
function et_builder_droploader_process() {
	et_core_security_check( 'upload_files', 'et_builder_droploader_process_nonce' );

	$post_id = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : '';

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		die( -1 );
	}

	et_core_security_check( 'edit_posts', 'et_builder_droploader_process_nonce' );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attachment_id = media_handle_upload( 'file', (int) $_POST['post_id'] );

	if ( is_wp_error( $attachment_id ) ) {
		wp_send_json_error( $attachment_id->get_error_message() );
	}

	wp_send_json_success( $attachment_id );
}
endif;
add_action( 'wp_ajax_et_builder_droploader_process', 'et_builder_droploader_process' );

/**
 * Add allowed mime types and file extensions for font files.
 *
 * @return array
 */
function et_pb_filter_upload_mimes_custom_fonts() {
	return array(
		'otf'        => 'application/x-font-opentype',
		'ttf'        => 'application/x-font-ttf',
		'woff'       => 'application/font-woff',
		'woff2'      => 'application/font-woff2',
		'eot'        => 'application/vnd.ms-fontobject',
	);
}

/*
 * Save the font-file.
 *
 */
function et_pb_add_font( $font_files, $font_name, $font_settings ) {
	if ( ! isset( $font_files ) || empty( $font_files ) ) {
		return array( 'error' => esc_html__( 'No Font File Provided', 'et_builder' ) );
	}

	// remove all special characters from the font name
	$font_name = preg_replace( '/[^A-Za-z0-9\s\_-]/', '', $font_name );

	if ( '' === $font_name ) {
		return array( 'error' => esc_html__( 'Font Name Cannot be Empty and Cannot Contain Special Characters', 'et_builder' ) );
	}

	$google_fonts = et_builder_get_google_fonts();
	$all_custom_fonts = get_option( 'et_uploaded_fonts', array() );

	// Don't allow to add fonts with the names which already used by User Fonts or Google Fonts.
	if ( isset( $all_custom_fonts[ $font_name ] ) || isset( $google_fonts[ $font_name ] ) ) {
		return array( 'error' => esc_html__( 'Font With This Name Already Exists. Please Use a Different Name', 'et_builder' ) );
	}

	// set the upload Directory for builder font files
	add_filter( 'upload_dir', 'et_pb_set_fonts_upload_dir' );

	// Set the upload_mimes filter before uploading font file.
	add_filter( 'upload_mimes', 'et_pb_filter_upload_mimes_custom_fonts' );

	$uploaded_files_error = '';
	$uploaded_files = array(
		'font_file' => array(),
		'font_url'  => array(),
	);

	foreach ( $font_files as $ext => $font_file) {
		// Try to upload font file.
		$upload = wp_handle_upload( $font_file, array(
			'test_size' => false,
			'test_form' => false,
			'mimes'     => et_pb_filter_upload_mimes_custom_fonts(),
		) );

		// try with different MIME types if uploading .otf file and error occurs
		if ( 'otf' === $ext && ! empty( $upload['error'] ) ) {
			foreach ( array( 'application/x-font-ttf', 'application/vnd.ms-opentype' ) as $mime_type ) {
				if ( ! empty( $upload['error'] ) ) {
					$upload = wp_handle_upload( $font_file, array(
						'test_size' => false,
						'test_form' => false,
						'mimes'     => array(
						  'otf' => $mime_type,
						),
					) );
				}
			}
		}

		if ( ! empty( $upload['error'] ) ) {
			$uploaded_files_error = $upload['error'];
		} else {
			$uploaded_files['font_file'][$ext] = esc_url( $upload['file'] );
			$uploaded_files['font_url'][$ext] = esc_url( $upload['url'] );
		}
	}

	// Reset the upload Directory after uploading font file
	remove_filter( 'upload_dir', 'et_pb_set_fonts_upload_dir' );

	// Reset the upload_mimes filter after uploading font file.
	remove_filter( 'upload_mimes', 'et_pb_filter_upload_mimes_custom_fonts' );

	// return error if no files were uploaded
	if ( empty( $uploaded_files['font_file'] ) && '' !== $uploaded_files_error ) {
		return array( 'error' => $uploaded_files_error );
	}

	//organize uploaded files
	$all_custom_fonts[ $font_name ] = array(
		'font_file' => $uploaded_files['font_file'],
		'font_url'  => $uploaded_files['font_url'],
	);

	if ( ! empty( $font_settings ) ) {
		$all_custom_fonts[ $font_name ]['styles'] = ! isset( $font_settings['font_weights'] ) || 'all' === $font_settings['font_weights'] ? '100,200,300,400,500,600,700,800,900' : $font_settings['font_weights'];
		$all_custom_fonts[ $font_name ]['type'] = isset( $font_settings['generic_family'] ) ? $font_settings['generic_family'] : 'serif';
	}

	update_option( 'et_uploaded_fonts', $all_custom_fonts );
	// Need to update cached assets because custom fonts are included in static helpers.
	et_fb_delete_builder_assets();

	return array( 'error' => array(), 'success' => true, 'uploaded_font' => $font_name, 'updated_fonts' => $all_custom_fonts );
}

function et_pb_remove_font( $font_name ) {
	if ( '' === $font_name ) {
		return array( 'error' => esc_html__( 'Font Name Cannot be Empty', 'et_builder' ) );
	}

	$all_custom_fonts = get_option( 'et_uploaded_fonts', array() );

	if ( ! isset( $all_custom_fonts[ $font_name ] ) ) {
		return array( 'error' => esc_html__( 'Font Does not Exist', 'et_builder' ) );
	}

	// remove all uploaded font files if array
	if ( is_array( $all_custom_fonts[ $font_name ]['font_file'] ) ) {
		foreach ( $all_custom_fonts[ $font_name ]['font_file'] as $ext => $font_file ) {
			et_pb_safe_unlink_font_file( $font_file );
		}
	} else {
		$font_file = $all_custom_fonts[ $font_name ]['font_file'];
		et_pb_safe_unlink_font_file( $font_file );
	}

	unset( $all_custom_fonts[ $font_name ] );

	update_option( 'et_uploaded_fonts', $all_custom_fonts );
	// Need to update cached assets because custom fonts are included in static helpers.
	et_fb_delete_builder_assets();

	return array( 'error' => array(), 'success' => true, 'updated_fonts' => $all_custom_fonts );
}

function et_pb_safe_unlink_font_file( $font_file ) {
	$data_utils = ET_Core_Data_Utils::instance();

	// get the extensions from our list of allowed font ext/mimes.
	$valid_font_exts = array_keys( et_pb_filter_upload_mimes_custom_fonts() );

	// set the upload Directory for builder font files, so we can retrieve the proper font upload dir info.
	add_filter( 'upload_dir', 'et_pb_set_fonts_upload_dir' );

	$wp_upload_dir_array = wp_get_upload_dir();

	// get the absolute path to the et fonts upload dir.
	$et_fonts_dir = $wp_upload_dir_array['path'];

	// reset the upload Directory after getting the upload dir.
	remove_filter( 'upload_dir', 'et_pb_set_fonts_upload_dir' );

	// expand all symbolic links and resolve references to /./, /../ and extra / characters in the input path and return the canonicalized absolute pathname.
	$file_realpath = realpath( $font_file );

	// get information about the path.
	$file_pathinfo = pathinfo( $font_file );

	// Build the full file path based on the parsed pathinfo pieces.
	$file_pathinfo_filename = $file_pathinfo['dirname'] . '/' . $file_pathinfo['basename'];

	// make sure the realpath matches the parsed pathinfo file path, so there is no funny business.
	if ( $data_utils->normalize_path( $file_realpath ) !== $data_utils->normalize_path( $file_pathinfo_filename ) ) {
		return false;
	}

	// make sure the font file to be deleted is an actual font file extension (not an arbitrarty PHP file somehow for example).
	if ( ! in_array( $file_pathinfo['extension'], $valid_font_exts ) ) {
		return false;
	}

	// the proper upload dir for fonts.
	$proper_font_file_path = $et_fonts_dir . '/' . $file_pathinfo['basename'];

	// make sure the file is located in the proper fonts upload dir.
	if ( $data_utils->normalize_path( $file_realpath ) !== $data_utils->normalize_path( $proper_font_file_path ) ) {
		return false;
	}

	// now that all checks have passed, the file can be safely deleted.
	return unlink( $file_realpath );
}

function et_pb_set_fonts_upload_dir( $directory ) {
	$directory['path'] = $directory['basedir'] . '/et-fonts';
	$directory['url'] = $directory['baseurl'] . '/et-fonts';
	$directory['subdir'] = '/et-fonts';

	return $directory;
}

function et_pb_get_unsynced_legacy_options( $post_type, $shortcode_data ) {
	if ( ! isset( $shortcode_data['attrs']['saved_tabs'] ) && 'all' === $shortcode_data['attrs']['saved_tabs'] ) {
		return array();
	}

	// get all options
	$general_fields = ET_Builder_Element::get_general_fields( $post_type, 'all', $shortcode_data['type'] );
	$advanced_fields = ET_Builder_Element::get_advanced_fields( $post_type, 'all', $shortcode_data['type'] );
	$css_fields = ET_Builder_Element::get_custom_css_fields( $post_type, 'all', $shortcode_data['type'] );
	$saved_fields = array_keys( $shortcode_data['attrs'] );

	// content fields should never be included into unsynced options. We use different key for the content options.
	$saved_fields[] = 'content';
	$saved_fields[] = 'raw_content';

	$all_fields = array_merge( array_keys( $general_fields ), array_keys( $advanced_fields ), array_keys( $css_fields ) );

	// compare all options with saved options to get array of unsynced ones.
	$unsynced_options = array_diff( $all_fields, $saved_fields );

	if ( false === strpos( $shortcode_data['attrs']['saved_tabs'], 'general' ) ) {
		$unsynced_options[] = 'et_pb_content_field';
	}

	return $unsynced_options;
}

// prepare the ssl link for FB
function et_fb_prepare_ssl_link( $link ) {
 	// replace http:// with https:// if FORCE_SSL_ADMIN option enabled
 	if ( defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ) {
 		return str_replace( 'http://', 'https://', $link );
 	}

 	return $link;
}

/**
 * Filterable options for backend and visual builder. Designed to be filtered
 * by theme/plugin since builder is shared accross Divi, Extra, and Divi Builder
 * @return array builder options values
 */
if ( ! function_exists( 'et_builder_options' ) ) :
function et_builder_options() {
	return apply_filters( 'et_builder_options', array(
		'all_buttons_icon' => 'yes', // Default appearance of button icon
	) );
}
endif;

/**
 * Get specific builder option (fetched from et_builder_options())
 * @param string option name
 * @return mixed builder option value
 */
if ( ! function_exists( 'et_builder_option' ) ) :
function et_builder_option( $name ) {
	$options = et_builder_options();

	$option = isset( $options[ $name ] ) ? $options[ $name ] : false;

	return apply_filters( "et_builder_option_{$name}", $option );
}
endif;

/**
 * Pass thru semantical previously escaped acknowledgement
 *
 * @deprecated {@see et_core_esc_previously()}
 *
 * @since 3.17.1 Deprecated
 *
 * @param string value being passed through
 * @return string
 */
function et_esc_previously( $passthru ) {
	et_debug( "You're Doing It Wrong! Attempted to call " . __FUNCTION__ . "(), use et_core_esc_previously() instead." );
	return $passthru;
}

/**
 * Pass thru semantical escaped by WordPress core acknowledgement
 *
 * @deprecated {@see et_core_esc_wp()}
 *
 * @since 3.17.1 Deprecated
 *
 * @param string value being passed through
 * @return string
 */

function et_esc_wp( $passthru ) {
	et_debug( "You're Doing It Wrong! Attempted to call " . __FUNCTION__ . "(), use et_core_esc_wp() instead." );
	return $passthru;
}

/**
 * Pass thru semantical intentionally unescaped acknowledgement
 *
 * @deprecated {@see et_core_intentionally_unescaped()}
 *
 * @since 3.17.1 Deprecated
 *
 * @param string value being passed through
 * @param string excuse the value is allowed to be unescaped
 * @return string
 */

function et_intentionally_unescaped( $passthru, $excuse ) {
	et_debug( "You're Doing It Wrong! Attempted to call " . __FUNCTION__ . "(), use et_core_intentionally_unescaped() instead." );

	// Add valid excuses as they arise
	$valid_excuses = array(
		'cap_based_sanitized',
		'fixed_string',
		'react_jsx',
		'underscore_template',
	);

	if ( ! in_array( $excuse, $valid_excuses ) ) {
		et_debug( "You're Doing It Wrong! This is not a valid excuse to not escape the passed value." );
	}

	return $passthru;
}

/**
 * Sanitize value depending on user capability
 *
 * @deprecated {@see et_core_sanitize_value_by_cap()}
 *
 * @since 3.17.1 Deprecated
 *
 * @return string value being passed through
 */
function et_sanitize_value_by_cap( $passthru, $sanitize_function = 'et_sanitize_html_input_text', $cap = 'unfiltered_html' ) {
	et_debug( "You're Doing It Wrong! Attempted to call " . __FUNCTION__ . "(), use et_core_sanitize_value_by_cap() instead." );

	if ( ! current_user_can( $cap ) ) {
		$passthru = $sanitize_function( $passthru );
	}

	return $passthru;
}

/**
 * Pass thru semantical intentionally unsanitized acknowledgement
 *
 * @deprecated {@see et_core_intentinally_unsanitized()}
 *
 * @since 3.17.1 Deprecated
 *
 * @param string value being passed through
 * @param string excuse the value is allowed to be unsanitized
 * @return string
 */

function et_intentionally_unsanitized( $passthru, $excuse ) {
	et_debug( "You're Doing It Wrong! Attempted to call " . __FUNCTION__ . "(), use et_core_intentionally_unsanitized() instead." );

	// Add valid excuses as they arise
	$valid_excuses = array();

	if ( ! in_array( $excuse, $valid_excuses ) ) {
		et_debug( "You're Doing It Wrong! This is not a valid excuse to not sanitize the passed value." );
	}

	return $passthru;
}

/**
 * Prevent delimiter-separated string from having duplicate item
 * @param string delimiter-separated string
 * @param string delimiter
 * @return string filtered delimiter-separated string
 */
function et_prevent_duplicate_item( $stringList, $delimiter ) {
	$list = explode( $delimiter, $stringList );

	return implode( $delimiter, array_unique( $list ) );
}

/**
 * Determining whether unminified scripts should be loaded or not.
 * @return bool
 */
function et_load_unminified_scripts() {
	static $should_load = null;

	if ( null === $should_load ) {
		$is_script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$should_load = apply_filters( 'et_load_unminified_scripts', $is_script_debug );
	}

	return $should_load;
}

/**
 * Determining whether unminified styles should be loaded or not
 */
function et_load_unminified_styles() {
	static $should_load = null;

	if ( null === $should_load ) {
		$is_script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$should_load = apply_filters( 'et_load_unminified_styles', $is_script_debug );
	}

	return $should_load;
}

/**
 * Enable / Disable classic editor based on saved option in Theme Options page.
 * Only applies to versions of WordPress that have the Gutenberg editor.
 *
 * @since ??
 *
 * @param bool
 *
 * @return bool
 */
function et_builder_enable_classic_editor( $enable ) {
	if ( 'on' === et_get_option( 'et_enable_classic_editor', 'off' ) ) {
		return true;
	}

	return $enable;
}
if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>=' ) ) {
	add_filter( 'et_builder_enable_classic_editor', 'et_builder_enable_classic_editor' );
}

/**
 * Check whether the BFB is enabled.
 *
 * @since 3.18
 *
 * @return bool
 */
function et_builder_bfb_enabled() {
	return apply_filters( 'et_builder_bfb_enabled', false );
}

if ( ! function_exists( 'et_builder_filter_bfb_enabled') ):
/**
 * Theme implementation for BFB enabled check.
 *
 * @since 3.18
 *
 * @return bool
 */
function et_builder_filter_bfb_enabled() {
	global $pagenow;

	$bfb_settings = get_option( 'et_bfb_settings' );
	$enabled = isset( $bfb_settings['enable_bfb'] ) && 'on' === $bfb_settings['enable_bfb'];

	if ( is_admin() && ! in_array( $pagenow, array( 'post.php', 'post-new.php', 'admin-ajax.php' ) ) ) {
		$enabled = false;
	} else if ( ! is_admin() && ! isset( $_GET['et_bfb'] ) ) {
		$enabled = false;
	} else if ( ! et_pb_is_allowed( 'use_visual_builder' ) ) {
		$enabled = false;
	}

	return $enabled;
}
endif;

if ( ! function_exists( 'et_builder_is_fresh_install') ):
/**
 * Get whether the builder is freshly installed.
 *
 * @since 3.18
 *
 * @return bool
 */
function et_builder_is_fresh_install() {
	return apply_filters( 'et_builder_is_fresh_install', false );
}
endif;

if ( ! function_exists( 'et_builder_filter_is_fresh_install') ):
/**
 * Theme implementation for fresh install check.
 *
 * @since 3.18
 *
 * @return bool
 */
function et_builder_filter_is_fresh_install() {
	global $shortname;

	return false === et_get_option( $shortname . '_logo' );
}
endif;

if ( ! function_exists( 'et_builder_toggle_bfb') ):
/**
 * Toggle BFB.
 *
 * @since 3.18
 *
 * @param bool $enable
 *
 * @return void
 */
function et_builder_toggle_bfb( $enable ) {
	do_action( 'et_builder_toggle_bfb', $enable );
}
endif;

if ( ! function_exists( 'et_builder_action_toggle_bfb') ):
/**
 * Theme implementation for BFB toggle.
 *
 * @since 3.18
 *
 * @param bool $enable
 *
 * @return void
 */
function et_builder_action_toggle_bfb( $enable ) {
	$bfb_value = $enable ? 'on' : 'off';

	et_update_option( '', $bfb_value, true, 'et_bfb_settings', 'enable_bfb' );
}
endif;

if ( ! function_exists( 'et_builder_filter_show_bfb_optin_modal') ):
/**
 * Theme implementation for show BFB opt-in modal check.
 *
 * @since 3.18
 *
 * @param bool $default
 *
 * @return bool
 */
function et_builder_filter_show_bfb_optin_modal( $default ) {
	global $shortname;

	$shown = et_get_option( $shortname . '_bfb_optin_modal_shown', 'unset' );

	// $shown === 'no' - modal is queued to be shown, but has not had the chance yet.
	return 'unset' === $shown ? $default : $shown === 'no';
}
endif;

if ( ! function_exists( 'et_builder_action_bfb_optin_modal_shown') ):
/**
 * Theme implementation for BFB opt-in modal shown.
 *
 * @since 3.18
 *
 * @return void
 */
function et_builder_action_bfb_optin_modal_shown() {
	global $shortname;

	et_update_option( $shortname . '_bfb_optin_modal_shown', 'yes' );
}
endif;

if ( ! function_exists( 'et_builder_action_queue_bfb_optin_modal') ):
/**
 * Theme implementation for queue BFB opt-in modal.
 *
 * @since 3.18
 *
 * @return void
 */
function et_builder_action_queue_bfb_optin_modal() {
	global $shortname;

	if ( ! ET_BUILDER_ENABLE_BFB_OPTIN_MODAL ) {
		return;
	}

	if ( et_builder_bfb_enabled() ) {
		return;
	}

	et_update_option( $shortname . '_bfb_optin_modal_shown', 'no' );
}
endif;

if ( ! function_exists( 'et_builder_show_bfb_optin_modal' ) ) :
/**
 * Show the BFB opt-in modal.
 *
 * @since 3.18
 *
 * @return void
 */
function et_builder_show_bfb_optin_modal() {
	global $pagenow;

	if ( 'post.php' !== $pagenow ) {
		return;
	}

	if ( apply_filters( 'et_builder_show_bfb_optin_modal', false ) === false ) {
		return;
	}

	do_action( 'et_builder_bfb_optin_modal_shown' );

	$new_builder_url = add_query_arg( array(
		'action'  => 'et_builder_toggle_bfb',
		'enable'  => '1',
		'welcome' => '1',
		'nonce'   => wp_create_nonce( 'et_builder_toggle_bfb' ),
	), admin_url( 'admin-ajax.php' ) );
	?>
	<div class="et-core-modal-overlay et-core-modal-two-buttons et-builder-bfb-optin-modal">
		<div class="et-core-modal">
			<div class="et-core-modal-header">
				<h3 class="et-core-modal-title"><?php esc_html_e( 'Try The New Experience', 'et_builder' ); ?></h3>
			</div>

			<div class="et-core-modal-content">
				<p><?php esc_html_e( 'A new and improved Divi Builder experience is now available. This new experience brings various interface enchancements as well as visual editing capabilities to the back end. You can try the new experience today, or you can continue using the classic builder for a limited time. Once the new experience has been activated, you can still switch back to the classic editor at any time - no worries!', 'et_builder' ); ?></p>
				<?php // TODO add link to docs ?>
				<p><a href="#" target="_blank"><?php esc_html_e( 'Learn more about the new experience here.', 'et_builder' ); ?></a></p>
			</div>

			<div class="et_pb_prompt_buttons">
				<a href="#" class="et-core-modal-action et-core-modal-action-secondary" data-et-core-modal="close"><?php esc_html_e( 'No Thanks', 'et_builder' ); ?></a>
				<a href="<?php echo esc_url( $new_builder_url ); ?>" class="et-core-modal-action"><?php esc_html_e( 'Try It Out', 'et_builder' ); ?></a>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function() {
			etCore.modalOpen(jQuery('.et-builder-bfb-optin-modal:first'));
		});
	</script>
	<?php
}
endif;
add_action( 'admin_footer', 'et_builder_show_bfb_optin_modal' );

if ( ! function_exists( 'et_builder_show_bfb_welcome_modal' ) ) :
/**
 * Show the BFB welcome modal.
 *
 * @since 3.18
 *
 * @return void
 */
function et_builder_show_bfb_welcome_modal() {
	if ( ! get_transient( 'et_builder_show_bfb_welcome_modal' ) ) {
		return;
	}

	delete_transient( 'et_builder_show_bfb_welcome_modal' );
	?>
	<div class="et-core-modal-overlay et-builder-bfb-welcome-modal">
		<div class="et-core-modal">
			<div class="et-core-modal-header">
				<h3 class="et-core-modal-title"><?php esc_html_e( 'Welcome To The New Builder', 'et_builder' ); ?></h3>
			</div>

			<div class="et-core-modal-content">
				<p><?php esc_html_e( 'You are now using the new Divi Builder experience! This new version of the builder comes with a lot of great interface enhancements that were previously only available in the Visual Builder. If you run into problems, you can always switch back to the classic builder using the button at the top of the page.', 'et_builder' ); ?></p>
			</div>

			<div class="et_pb_prompt_buttons">
				<a href="#" class="et-core-modal-action" data-et-core-modal="close"><?php esc_html_e( 'Start Building', 'et_builder' ); ?></a>
			</div>
		</div>
	</div>																					   );
	<script>
		jQuery(document).ready(function() {
			etCore.modalOpen(jQuery('.et-builder-bfb-welcome-modal:first'));
		});
	</script>
	<?php
}
endif;
add_action( 'admin_footer', 'et_builder_show_bfb_welcome_modal' );

if ( ! function_exists( 'et_builder_maybe_queue_bfb_optin_modal') ):
/**
 * Maybe queue BFB opt-in modal.
 *
 * @since 3.18
 *
 * @return void
 */
function et_builder_prepare_bfb() {
	if ( ! ET_BUILDER_ENABLE_BFB_OPTIN_MODAL ) {
		return;
	}

	if ( et_builder_bfb_enabled() ) {
		return;
	}

	// Enable BFB for all new users.
	if ( et_builder_is_fresh_install() ) {
		et_builder_toggle_bfb( true );
		return;
	}

	// Queue opt-in modal for old users who have not yet seen the modal.
	if ( apply_filters( 'et_builder_show_bfb_optin_modal', true ) === true ) {
		do_action( 'et_builder_queue_bfb_optin_modal' );
	}
}
endif;
add_action( 'after_switch_theme', 'et_builder_prepare_bfb' );
add_action( 'upgrader_process_complete', 'et_builder_prepare_bfb' );
add_action( 'activated_plugin', 'et_builder_prepare_bfb', 10, 0 );
add_action( 'deactivated_plugin', 'et_builder_prepare_bfb', 10, 0 );

/**
 * Add the divi builder body class.
 *
 * @param $classes
 *
 * @return array
 */
function et_builder_add_body_class( $classes ) {
	$classes[] = 'et-db';

	return $classes;
}
add_filter( 'body_class', 'et_builder_add_body_class' );

/**
 * Add builder inner content wrapper classes.
 *
 * @since 3.10
 *
 * @param $classes
 *
 * @return array
 */
function et_builder_add_builder_inner_content_class( $classes ) {
	$page_custom_gutter = get_post_meta( get_the_ID(), '_et_pb_gutter_width', true );
	$valid_gutter_width = array( '1', '2', '3', '4' );
	$gutter_width       = in_array( $page_custom_gutter, $valid_gutter_width ) ? $page_custom_gutter : '3';
	$classes[]          = "et_pb_gutters{$gutter_width}";

	return $classes;
}
add_filter( 'et_builder_inner_content_class', 'et_builder_add_builder_inner_content_class' );

/**
 * Wrap post builder content.
 *
 * @since 3.10
 *
 * @param $content
 *
 * @return string
 */
function et_builder_add_builder_content_wrapper( $content ) {
	if ( ! et_pb_is_pagebuilder_used( get_the_ID() ) && ! is_et_pb_preview() ) {
		return $content;
	}

	// Divi builder layout should only be used in singular template
	if ( ! is_singular() ) {
		return $content;
	}

	$outer_class   = apply_filters( 'et_builder_outer_content_class', array( 'et-boc' ) );
	$outer_classes = implode( ' ', $outer_class );
	$outer_id      = apply_filters( 'et_builder_outer_content_id', 'et-boc' );
	$inner_class   = apply_filters( 'et_builder_inner_content_class', array( 'et_builder_inner_content' ) );
	$inner_classes = implode( ' ', $inner_class );

	$is_dbp                   = et_is_builder_plugin_active();
	$dbp_compat_wrapper_open  = $is_dbp ? '<div id="et_builder_outer_content" class="et_builder_outer_content">' : '';
	$dbp_compat_wrapper_close = $is_dbp ? '</div>' : '';

	$content = sprintf(
		'<div id="%1$s" class="%2$s">
			%3$s
			<div class="%4$s">
				%5$s
			</div>
			%6$s
		</div>',
		esc_attr( $outer_id ),
		esc_attr( $outer_classes ),
		et_core_intentionally_unescaped( $dbp_compat_wrapper_open, 'fixed_string' ),
		esc_attr( $inner_classes ),
		$content,
		et_core_intentionally_unescaped( $dbp_compat_wrapper_close, 'fixed_string' )
	);

	return $content;
}
add_filter( 'the_content', 'et_builder_add_builder_content_wrapper' );

/**
 * Wraps a copy of a css selector and then returns both selectors.
 * Wrapping a copy of a selector instead of the original is necessary for selectors
 * that target elements both inside AND outside the wrapper element.
 *
 * @since 3.10
 *
 * @param string  $selector CSS selector to wrap.
 * @param string  $suffix   Selector partial to add to the wrapped selector after the wrapper (a space will be added first).
 * @param boolean $clone    Duplicate the selector, wrap the duplicate, and then return both selectors. Default `true`.
 *
 * @return string
 */
function et_builder_maybe_wrap_css_selector( $selector, $suffix = '', $clone = true ) {
	static $should_wrap_selectors = null;

	if ( is_null( $should_wrap_selectors ) ) {
		$should_wrap_selectors = et_pb_is_pagebuilder_used() && ( et_is_builder_plugin_active() || et_builder_post_is_of_custom_post_type() );
	}

	if ( is_bool( $suffix ) ) {
		$clone  = $suffix;
		$suffix = '';
	}

	if ( ! $should_wrap_selectors ) {
		return trim( "{$selector} {$suffix}" );
	}

	$wrapper = '.et-db #et-boc';
	$result  = '';

	if ( $clone ) {
		$result .= $suffix ? "{$selector} {$suffix}, " : "{$selector}, ";
	}

	if ( $suffix ) {
		// $suffix param allows caller to split selector into two parts (1. outside builder and 2. inside builder)
		// so that it can be wrapped properly. It was implemented before the regex solution below.
		if ( preg_match( '/et_fb_preview|et_fb_desktop_mode/', $selector ) ) {
			// Selector targets html element using a custom class
			$result .= "{$selector} {$wrapper} {$suffix}";
		} else {
			// Selector targets body element either directly or using a custom class
			$result .= "{$selector}{$wrapper} {$suffix}";
		}

	} else if ( preg_match('/^(html[^ ]*)?(?: *)(body[^ ]*)?(?: *)(.*?)(?: *)([^ ]*\.et[_-](?:pb|fb)[_-].+)/', $selector, $matches ) ) {
		// The selector includes elements outside builder content so we can't just prepend the wrapper to it.
		list( $_, $html, $body, $outside_builder, $inside_builder ) = $matches;

		$parts   = array_filter( array( $html, "{$body}.et-db", $outside_builder, '#et-boc', $inside_builder ) );
		$result .= implode( ' ', $parts );

	} else {
		$result .= "{$wrapper} {$selector}";
	}

	return trim( $result );
}

/**
 * Wrapper for {@see et_builder_maybe_wrap_css_selector()} to support multiple selectors
 * at once (eg. selector1, selector2, selector3)
 *
 * @since 3.10
 *
 * @param string $selector CSS selectors to wrap.
 * @param bool   $clone    {@see et_builder_maybe_wrap_css_selector()}
 *
 * @return string
 */
function et_builder_maybe_wrap_css_selectors( $selector, $clone = true ) {
	static $should_wrap_selectors = null;

	if ( is_null( $should_wrap_selectors ) ) {
		$should_wrap_selectors = et_pb_is_pagebuilder_used() && ( et_is_builder_plugin_active() || et_builder_post_is_of_custom_post_type() );
	}

	if ( ! $should_wrap_selectors ) {
		return $selector;
	}

	$selectors = explode( ',', $selector );
	$result    = array();

	foreach ( $selectors as $css_selector ) {
		$result[] = et_builder_maybe_wrap_css_selector( $css_selector, $clone );
	}

	return implode( ',', $result );
}


function _et_pb_code_module_unprep_content( $content ) {
	// before we swap out the placeholders,
	// remove all the <p> tags and \n that wpautop added!
	$content = preg_replace( '/\n/smi', '', $content );
	$content = preg_replace( '/<p>/smi', '', $content );
	$content = preg_replace( '/<\/p>/smi', '', $content );

	$content = str_replace( '<!–- [et_pb_br_holder] -–>', '<br />', $content );

	// convert the <pee tags back to <p
	// see et_pb_prep_code_module_for_wpautop()
	$content = str_replace( '<pee', '<p', $content );
	$content = str_replace( '</pee>', '</p> ', $content );

	return $content;
}

function _et_pb_code_module_unprep_content_regex_cb( $matches ) {
	$prepped_content = $matches[1];

	$prepped_content = _et_pb_code_module_unprep_content( $prepped_content );

	return str_replace( $matches[1], $prepped_content, $matches[0] );
}

function et_pb_unprep_code_module_for_wpautop( $content ) {
	$content = preg_replace_callback('/\[et_pb_code.*?\](.*)\[\/et_pb_code\]/mis', '_et_pb_code_module_unprep_content_regex_cb', $content );
	$content = preg_replace_callback('/\[et_pb_fullwidth_code.*?\](.*)\[\/et_pb_fullwidth_code\]/mis', '_et_pb_code_module_unprep_content_regex_cb', $content );

	return $content;
}

function _et_pb_code_module_prep_content( $content ) {
	// convert <br /> tags into placeholder so wpautop will leave them alone
	$content = preg_replace( '|<br[\s]?[\/]?>|', '<!–- [et_pb_br_holder] -–>', $content );

	// convert <p> tag to <pee> tag, so wpautop will leave them alone,
	// *and* so that we can clearly spot the <p> tags that wpautop adds
	// so we can quickly remove them.
	$content = preg_replace( '|<p |', '<pee ', $content );
	$content = preg_replace( '|<p>|', '<pee>', $content );
	$content = preg_replace( '|<\/p>|', '</pee>', $content );

	return $content;
}

function _et_pb_code_module_prep_content_regex_cb( $matches ) {
	$prepped_content = $matches[1];

	$prepped_content = _et_pb_code_module_prep_content( $prepped_content );

	return str_replace( $matches[1], $prepped_content, $matches[0] );
}

function et_pb_prep_code_module_for_wpautop( $content ) {
	$content = preg_replace_callback('/\[et_pb_code.*?\](.*?)\[\/et_pb_code\]/mis', '_et_pb_code_module_prep_content_regex_cb', $content );
	$content = preg_replace_callback('/\[et_pb_fullwidth_code.*?\](.*?)\[\/et_pb_fullwidth_code\]/mis', '_et_pb_code_module_prep_content_regex_cb', $content );

	return $content;
}

function et_fb_dynamic_asset_exists( $prefix, $post_type = false ) {
	// Get post type if it isn't being defined
	if ( ! $post_type ) {
		if ( wp_doing_ajax() ) {
			$post_type = isset( $_REQUEST['et_post_type'] ) ? $_REQUEST['et_post_type'] : 'post';
			$post_type = sanitize_text_field( $post_type );
		} else {
			global $post;

			$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
		}
	}

	$uploads = wp_upload_dir();
	$prefix  = esc_attr( $prefix );
	$files   = glob( sprintf( '%s/%s-%s-*.js', ET_Core_PageResource::get_cache_directory(), $prefix, $post_type ) );

	return is_array( $files ) && count( $files ) > 0;
}

if ( ! function_exists( 'et_fb_delete_builder_assets' ) ):
function et_fb_delete_builder_assets() {
	if ( $files = glob( sprintf( '%s/*.js', ET_Core_PageResource::get_cache_directory() ) ) ) {
		foreach ( $files as $file ) {
			@unlink( $file );
		}
	}
}
endif;
