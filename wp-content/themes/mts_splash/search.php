<?php
/**
 * The template for displaying search results pages.
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header(); ?>

<div id="page">
	<div class="article">
		<div id="content_box">
			<h1 class="postsby">
				<span><?php _e("Search Results for:", 'splash' ); ?></span> <?php the_search_query(); ?>
			</h1>
			<?php if(have_posts()) {
				$default_layout = isset($mts_options['mts_home_layout']) && !empty($mts_options['mts_home_layout']) ? $mts_options['mts_home_layout'] : 'list';
				$selected_layout = !empty($_COOKIE['selected_layout']) ? $_COOKIE['selected_layout'] : $default_layout;
				if($mts_options['mts_sorting'] == '1') { ?>	
					<div class="viewstyle">
						<span class="viewtext"><?php _e('Show Posts in','mythemeshop'); ?></span>
						<div class="viewsbox">
							<div id="list" <?php echo ($selected_layout == 'list' ? 'class="active"' : ''); ?>><a><i class="fa fa-list"></i> <?php _e('List View','mythemeshop'); ?></a></div>
							<div id="grid" <?php echo ($selected_layout == 'grid' ? 'class="active"' : ''); ?>><a><i class="fa fa-th"></i> <?php _e('Grid View','mythemeshop'); ?></a></div>
						</div>
						<div style="clear:both;"></div>
					</div>
			<?php }
			} ?>
			<?php $j = 0; if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article class="latestPost excerpt<?php if($selected_layout == 'grid') echo ' grid'; ?>">
					<?php mts_archive_post(); ?>
				</article><!--.post excerpt-->
			<?php endwhile; ++$j; else: ?>
				<div class="no-results">
					<h2><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'splash' ); ?></h2>
					<?php get_search_form(); ?>
				</div><!--noResults-->
			<?php endif;

			if ( $j !== 0 ) { // No pagination if there is no posts ?>
				<?php mts_pagination();
			} ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>