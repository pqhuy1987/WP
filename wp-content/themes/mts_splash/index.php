<?php
/**
 * The main template file.
 *
 * Used to display the homepage when home.php doesn't exist.
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header();

$default_layout = isset($mts_options['mts_home_layout']) && !empty($mts_options['mts_home_layout']) ? $mts_options['mts_home_layout'] : 'list';
$selected_layout = !empty($_COOKIE['selected_layout']) ? $_COOKIE['selected_layout'] : $default_layout;
?>

<div id="page" class="clearfix">
	<div class="article">
		<div id="content_box">

			<?php if ( !is_paged() ) {

				if ( is_home() && $mts_options['mts_featured_slider'] == '1' ) { ?>
					<div class="primary-slider-container clearfix loading">
						<div id="slider" class="primary-slider">
							<?php if ( empty( $mts_options['mts_custom_slider'] ) ) {
								// prevent implode error
								if ( empty( $mts_options['mts_featured_slider_cat'] ) || !is_array( $mts_options['mts_featured_slider_cat'] ) ) {
									$mts_options['mts_featured_slider_cat'] = array('0');
								}
								$posts_per_page = !empty( $mts_options['mts_featured_slider_num'] ) ? $mts_options['mts_featured_slider_num'] : '3';
								$slider_cat = implode( ",", $mts_options['mts_featured_slider_cat'] );
								$slider_query = new WP_Query('cat='.$slider_cat.'&posts_per_page='.$posts_per_page);
								while ( $slider_query->have_posts() ) : $slider_query->the_post();
								?>
								<div class="primary-slider-item">
									<a href="<?php echo esc_url( get_the_permalink() ); ?>">
										<?php the_post_thumbnail('splash-slider',array('title' => '')); ?>
										<div class="slide-caption">
											<h2 class="slide-title"><?php echo substr(the_title( $before = '', $after = '', FALSE), 0, 60) . '...'; ?></h2>
											<span class="slide-text"><?php echo mts_excerpt(22);?></span>
										</div>
									</a> 
								</div>
								<?php endwhile; wp_reset_postdata();
							} else {
								foreach( $mts_options['mts_custom_slider'] as $slide ) : ?>
									<div class="primary-slider-item">
										<a href="<?php echo esc_url( $slide['mts_custom_slider_link'] ); ?>">
											<?php echo wp_get_attachment_image( $slide['mts_custom_slider_image'], 'splash-slider', false, array('title' => '') ); ?>
											<div class="slide-caption">
												<h2 class="slide-title"><?php echo esc_html( $slide['mts_custom_slider_title'] ); ?></h2>
												<span class="slide-text"><?php echo esc_html( $slide['mts_custom_slider_text'] ); ?></span>
											</div>
										</a>
									</div>
								<?php endforeach;
							} ?>
						</div><!-- .primary-slider -->
					</div><!-- .primary-slider-container -->
				<?php }

				if ( have_posts() ) {
					if($mts_options['mts_sorting'] == '1') { ?>  
						<div class="viewstyle">
							<span class="viewtext"><?php _e('Show Posts in','splash'); ?></span>
							<div class="viewsbox">
								<div id="list" <?php echo ($selected_layout == 'list' ? 'class="active"' : ''); ?>><a><i class="fa fa-list"></i> <?php _e('List View','splash'); ?></a></div>
								<div id="grid" <?php echo ($selected_layout == 'grid' ? 'class="active"' : ''); ?>><a><i class="fa fa-th"></i> <?php _e('Grid View','splash'); ?></a></div>
							</div>
							<div style="clear:both;"></div>
						</div>
					<?php }
				}

				$featured_categories = array();
				if ( !empty( $mts_options['mts_featured_categories'] ) ) {
					foreach ( $mts_options['mts_featured_categories'] as $section ) {
						$category_id = $section['mts_featured_category'];
						$featured_categories[] = $category_id;
						$posts_num = $section['mts_featured_category_postsnum'];
						if ( 'latest' == $category_id ) {
							$j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<article class="latestPost excerpt <?php if($selected_layout == 'grid') echo ' grid'; ?>">
									<?php mts_archive_post(); ?>
								</article>
							<?php endwhile; endif; ++$j;
							
							if ( $j !== 0 ) { // No pagination if there is no posts ?>
								<?php mts_pagination();
							} ?>
							
						<?php } else { // if $category_id != 'latest': ?>
							<h3 class="featured-category-title"><a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" title="<?php echo esc_attr( get_cat_name( $category_id ) ); ?>"><?php echo esc_html( get_cat_name( $category_id ) ); ?></a></h3>
							<?php
							$j = 0;
							$cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num);
							if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
								<article class="latestPost excerpt <?php if($selected_layout == 'grid') echo ' grid'; ?>">
									<?php mts_archive_post(); ?>
								</article>
							<?php
							endwhile; endif; ++$j; wp_reset_postdata();
						}
					}
				}

			} else { //Paged
				if ( have_posts() ) {
					if($mts_options['mts_sorting'] == '1') { ?>  
						<div class="viewstyle">
							<span class="viewtext"><?php _e('Show Posts in','splash'); ?></span>
							<div class="viewsbox">
								<div id="list" <?php echo ($selected_layout == 'list' ? 'class="active"' : ''); ?>><a><i class="fa fa-list"></i> <?php _e('List View','splash'); ?></a></div>
								<div id="grid" <?php echo ($selected_layout == 'grid' ? 'class="active"' : ''); ?>><a><i class="fa fa-th"></i> <?php _e('Grid View','splash'); ?></a></div>
							</div>
							<div style="clear:both;"></div>
						</div>
					<?php }
				}
				$j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="latestPost excerpt <?php if($selected_layout == 'grid') echo ' grid'; ?>">
					<?php mts_archive_post(); ?>
				</article>
				<?php endwhile; endif; ++$j;

				if ( $j !== 0 ) { // No pagination if there is no posts ?>
					<?php mts_pagination();
				}

			} ?>

		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>