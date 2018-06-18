<?php
/**
 * The template for displaying all single posts.
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header(); ?>

<div id="page" class="<?php mts_single_page_class(); ?>">
	
	<?php $header_animation = mts_get_post_header_effect(); ?>
	<?php if ( 'parallax' === $header_animation ) {?>
		<?php if (mts_get_thumbnail_url()) : ?>
			<div id="parallax" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div>
		<?php endif; ?>
	<?php } else if ( 'zoomout' === $header_animation ) {?>
		 <?php if (mts_get_thumbnail_url()) : ?>
			<div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div></div>
		<?php endif; ?>
	<?php } ?>

	<article class="<?php mts_article_class(); ?>">
		<div id="content_box" >
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
					<?php if ($mts_options['mts_breadcrumb'] == '1') { ?>
						<div class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#"><?php mts_the_breadcrumb(); ?></div>
					<?php }
					
					// Single post parts ordering
					if ( isset( $mts_options['mts_single_post_layout'] ) && is_array( $mts_options['mts_single_post_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_single_post_layout'] ) ) {
						$single_post_parts = $mts_options['mts_single_post_layout']['enabled'];
					} else {
						$single_post_parts = array( 'content' => 'content', 'related' => 'related', 'author' => 'author' );
					}
					foreach( $single_post_parts as $part => $label ) { 
						switch ($part) {
							case 'content':
								?>
								<div class="single_post">
									<header>
										<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
										<?php mts_the_postinfo( 'single' ); ?>
									</header><!--.headline_area-->
									<div class="post-single-content box mark-links entry-content">
										<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
											<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
												<div class="topad">
													<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
												</div>
											<?php } ?>
										<?php } ?>
										<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'top') mts_social_buttons(); ?>
										<div class="thecontent">
											<?php the_content(); ?>
										</div>
										<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next', 'splash' ), 'previouspagelink' => __('Previous', 'splash' ), 'pagelink' => '%','echo' => 1 )); ?>
										<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
											<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
												<div class="bottomad">
													<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
												</div>
											<?php } ?>
										<?php } ?> 
										<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] !== 'top') mts_social_buttons(); ?>

										<?php 
											// old reviews box
											$old_review_score = get_post_meta( $post->ID, 'mts_overall_score', true );
											$old_review_criteria = get_post_meta( $post->ID, 'mts_critera_1', true );
											if ($old_review_score || $old_review_criteria) {
											?>
											<div class="reviewbox">
												<div class="pdetails">
													<?php if(get_post_meta($post->ID, 'mts_overall_score', true)): ?>
														<h3><div class="overall-score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_overall_score', true); ?>.png" alt="" /></div></h3>
													<?php endif; ?>
													<div class="reviewmeta">
														<?php if(get_post_meta($post->ID, 'mts_critera_1', true)): ?>
														<div class="clearfix"><?php echo get_post_meta($post->ID, 'mts_critera_1', true); ?><span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_critera_1_score', true); ?>.png" alt="" /></span></div>
														<?php endif; ?>
														<?php if(get_post_meta($post->ID, 'mts_critera_2', true)): ?>
														<div class="clearfix"><?php echo get_post_meta($post->ID, 'mts_critera_2', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_critera_2_score', true); ?>.png" alt="" /></span></div>
														<?php endif; ?>
														<?php if(get_post_meta($post->ID, 'mts_critera_3', true)): ?>
														<div class="clearfix"><?php echo get_post_meta($post->ID, 'mts_critera_3', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_critera_3_score', true); ?>.png" alt="" /></span></div>
														<?php endif; ?>
														<?php if(get_post_meta($post->ID, 'mts_critera_4', true)): ?>
														<div class="clearfix"><?php echo get_post_meta($post->ID, 'mts_critera_4', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_critera_4_score', true); ?>.png" alt="" /></span></div>
														<?php endif; ?>
														<?php if(get_post_meta($post->ID, 'mts_critera_5', true)): ?>
														<div class="clearfix"><?php echo get_post_meta($post->ID, 'mts_critera_5', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'mts_critera_5_score', true); ?>.png" alt="" /></span></div>
														<?php endif; ?>
														<div id="user-rating" class="clearfix">
															<span><?php _e('User rating', 'splash'); ?></span>
															<div class="user-rating"></div>
														</div>
													</div>
												</div>
												<div class="proscons">
													<?php if(get_post_meta($post->ID, 'mts_pros', true) || get_post_meta($post->ID, 'mts_cons', true)): ?>
														<?php if(get_post_meta($post->ID, 'mts_pros', true)): ?>
															<div class="pros"><b>Pros:</b>&nbsp;<span><?php echo get_post_meta($post->ID, 'mts_pros', true); ?></span></div>
														<?php endif; ?>
														<?php if(get_post_meta($post->ID, 'mts_cons', true)): ?>
															<div class="cons"><b>Cons:</b>&nbsp;<span><?php echo get_post_meta($post->ID, 'mts_cons', true); ?></span></div>
														<?php endif; ?>
													<?php else: ?>
														<?php if(get_post_meta($post->ID, 'mts_pdescription', true)): ?>
															<div class="cons"><span><?php echo get_post_meta($post->ID, 'mts_pdescription', true); ?></span></div>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
											<?php 
											} else {
											// pros/cons added with wp_review_get_data filter
											}
											?>
									</div><!--.post-single-content-->
								</div><!--.single_post-->
								<?php
							break;

							case 'tags':
								?>
								<?php mts_the_tags('<div class="tags"><span class="tagtext">'.__('Tags', 'splash' ).':</span>',', ') ?>
								<?php
							break;

							case 'related':
								mts_related_posts();
							break;

							case 'author':
								?>
								<div class="postauthor">
									<h4><?php _e('About The Author', 'splash' ); ?></h4>
									<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '100' );  } ?>
									<h5 class="vcard author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author_meta( 'display_name' ); ?></a></h5>
									<p><?php the_author_meta('description') ?></p>
								</div>
								<?php
							break;
						}
					}
					?>
				</div><!--.g post-->
				<?php comments_template( '', true ); ?>
			<?php endwhile; /* end loop */ ?>
		</div>
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
