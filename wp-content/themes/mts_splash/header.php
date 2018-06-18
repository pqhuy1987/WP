<?php
/**
 * The template for displaying the header.
 *
 * Displays everything from the doctype declaration down to the navigation.
 */
?>
<!DOCTYPE html>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body id="blog" <?php body_class('main'); ?>>	   
	<div class="main-container">
		<header id="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
			<div class="container clearfix">
				<?php if ( !empty( $mts_options['mts_show_primary_nav'] ) && $mts_options['mts_show_primary_nav'] == '1' ) { ?>
					<div id="navigation" class="primary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php if ( $mts_options['mts_show_secondary_nav'] !== '1' ) {?><a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu', 'splash' ); ?></a><?php } ?>
						<nav class="navigation clearfix<?php if ( $mts_options['mts_show_secondary_nav'] !== '1' ) echo ' mobile-menu-wrapper'; ?>">
							<?php if ( has_nav_menu( 'primary-menu' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) );
							} else { ?>
								<ul class="menu clearfix">
									<?php wp_list_pages('title_li='); ?>
								</ul>
							<?php } ?>
						</nav>
						<?php if($mts_options['mts_header_search'] == '1') { ?>
							<div class="header-search"><?php get_search_form( ); ?></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="container clearfix">
				<div id="header">
					<div class="logo-wrap">
						<?php if ($mts_options['mts_logo'] != '') { ?>
							<?php
							$logo_id = mts_get_image_id_from_url( $mts_options['mts_logo'] );
							$logo_w_h = '';
							if ( $logo_id ) {
								$logo	 = wp_get_attachment_image_src( $logo_id, 'full' );
								$logo_w_h = ' width="'.$logo[1].'" height="'.$logo[2].'"';
							}
							if( is_front_page() || is_home() || is_404() ) { ?>
								<h1 id="logo" class="image-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php echo $logo_w_h; ?>></a>
								</h1><!-- END #logo -->
							<?php } else { ?>
								<h2 id="logo" class="image-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php echo $logo_w_h; ?>></a>
								</h2><!-- END #logo -->
							<?php }
						} else {
							if( is_front_page() || is_home() || is_404() ) { ?>
								<h1 id="logo" class="text-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
								</h1><!-- END #logo -->
							<?php } else { ?>
								 <h2 id="logo" class="text-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
								</h2><!-- END #logo -->
							<?php } ?>
							<div class="site-description" itemprop="description">
								<?php bloginfo( 'description' ); ?>
							</div>
						<?php } ?>
					</div>
					<?php dynamic_sidebar('widget-header');
					if ( !empty( $mts_options['mts_show_secondary_nav'] ) && $mts_options['mts_show_secondary_nav'] == '1' ) {
					if( $mts_options['mts_sticky_nav'] == '1' ) { ?>
						<div id="catcher" class="clear" ></div>
						<div id="navigation" class="secondary-navigation sticky-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php } else { ?>
						<div id="navigation" class="secondary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php } ?>
						<a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu', 'splash' ); ?></a>
						<?php if ( has_nav_menu( 'mobile' ) ) { ?>
							<nav class="navigation clearfix">
								<?php if ( has_nav_menu( 'secondary-menu' ) ) {
									wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) );
								} else { ?>
									<ul class="menu clearfix">
										<?php wp_list_categories('title_li='); ?>
									</ul>
								<?php }
								mts_cart(); ?>
							</nav>
							<nav class="navigation mobile-only clearfix mobile-menu-wrapper">
								<?php wp_nav_menu( array( 'theme_location' => 'mobile', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) );
								mts_cart(); ?>
							</nav>
						<?php } else { ?>
							<nav class="navigation clearfix mobile-menu-wrapper">
								<?php if ( has_nav_menu( 'secondary-menu' ) ) {
									wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) );
								} else { ?>
									<ul class="menu clearfix">
										<?php wp_list_categories('title_li='); ?>
									</ul>
								<?php }
								mts_cart(); ?>
							</nav>
						<?php } ?>
					</div>
					<?php } ?>
				</div><!--#header-->
			</div><!--.container-->
		</header>