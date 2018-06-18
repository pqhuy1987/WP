<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Recent Reviews
	Description: A widget for displaying recent reviews.
	Version: 3.0

-----------------------------------------------------------------------------------*/
class mts_recent_reviews_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mts_recent_reviews_widget',
			sprintf( __('%sRecent Reviews', 'splash' ), MTS_THEME_WHITE_LABEL ? '' : 'MTS ' ),
			array( 'description' => __( 'Displays recent Review Posts with star rating.', 'splash' ) )
		);
	}

 	public function form( $instance ) {
		$defaults = array(
			'title_length' => 7,
			'comment_num' => 1,
			'show_thumb2' => 1,
			'box_layout' => 'horizontal-small',
			'show_excerpt' => 1,
			'excerpt_length' => 10
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Recent Reviews', 'splash' );
		$qty = isset( $instance[ 'qty' ] ) ? esc_attr( $instance[ 'qty' ] ) : 5;
		$title_length = isset( $instance[ 'title_length' ] ) ? intval( $instance[ 'title_length' ] ) : 7;
		$comment_num = isset( $instance[ 'comment_num' ] ) ? esc_attr( $instance[ 'comment_num' ] ) : 1;
		$show_excerpt = isset( $instance[ 'show_excerpt' ] ) ? esc_attr( $instance[ 'show_excerpt' ] ) : 1;
		$excerpt_length = isset( $instance[ 'excerpt_length' ] ) ? intval( $instance[ 'excerpt_length' ] ) : 10;
		$show_thumb2 = isset( $instance[ 'show_thumb2' ] ) ? esc_attr( $instance[ 'show_thumb2' ] ) : 1;
		$box_layout = $instance['box_layout'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'splash' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'qty' ); ?>"><?php _e( 'Number of Posts to show', 'splash' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'qty' ); ?>" name="<?php echo $this->get_field_name( 'qty' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $qty ); ?>" />
		</p>

		<p>
		   <label for="<?php echo $this->get_field_id( 'title_length' ); ?>"><?php _e( 'Title Length:', 'splash' ); ?>
		   <input id="<?php echo $this->get_field_id( 'title_length' ); ?>" name="<?php echo $this->get_field_name( 'title_length' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $title_length ); ?>" />
		   </label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("show_thumb2"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_thumb2"); ?>" name="<?php echo $this->get_field_name("show_thumb2"); ?>" value="1" <?php if (isset($instance['show_thumb2'])) { checked( 1, $instance['show_thumb2'], true ); } ?> />
				<?php _e( 'Show Thumbnails', 'splash' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('box_layout'); ?>"><?php _e('Posts layout:', 'splash' ); ?></label>
			<select id="<?php echo $this->get_field_id('box_layout'); ?>" name="<?php echo $this->get_field_name('box_layout'); ?>">
				<option value="horizontal-small" <?php selected($box_layout, 'horizontal-small', true); ?>><?php _e('Horizontal', 'splash' ); ?></option>
				<option value="vertical-small" <?php selected($box_layout, 'vertical-small', true); ?>><?php _e('Vertical', 'splash' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("comment_num"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>" value="1" <?php checked( 1, $instance['comment_num'], true ); ?> />
				<?php _e( 'Show number of comments', 'splash' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>" value="1" <?php checked( 1, $instance['show_excerpt'], true ); ?> />
				<?php _e( 'Show excerpt', 'splash' ); ?>
			</label>
		</p>
		
		<p>
		   <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length:', 'splash' ); ?>
		   <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $excerpt_length ); ?>" />
		   </label>
	   </p>
	   
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['qty'] = intval( $new_instance['qty'] );
		$instance['title_length'] = intval( $new_instance['title_length'] );
		$instance['comment_num'] = intval( $new_instance['comment_num'] );
		$instance['show_thumb2'] = intval( $new_instance['show_thumb2'] );
		$instance['box_layout'] = $new_instance['box_layout'];
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? intval( $new_instance['show_excerpt'] ) : 0;
		$instance['excerpt_length'] = intval( $new_instance['excerpt_length'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title_length = $instance['title_length'];
		$comment_num = $instance['comment_num'];
		$qty = (int) $instance['qty'];
		$show_thumb2 = (int) $instance['show_thumb2'];
		$box_layout = isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small';
		$show_excerpt = $instance['show_excerpt'];
		$excerpt_length = $instance['excerpt_length'];

		$before_widget = preg_replace('/class="([^"]+)"/i', 'class="$1 '.(isset($instance['box_layout']) ? $instance['box_layout'] : 'horizontal-small').'"', $before_widget); // Add horizontal/vertical class to widget
		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		echo self::get_cat_posts( $qty, $title_length, $comment_num, $show_thumb2, $box_layout, $show_excerpt, $excerpt_length );
		echo $after_widget;
	}

	public function get_cat_posts( $qty, $title_length, $comment_num, $show_thumb2, $box_layout, $show_excerpt, $excerpt_length ) {
		
		$no_image = ( $show_thumb2 ) ? '' : ' no-thumb';

		if ( 'horizontal-small' === $box_layout ) {
			$thumbnail	 = 'widgetthumb';
			$open_li_item  = '<li class="post-box horizontal-small horizontal-container'.$no_image.'"><div class="horizontal-container-inner">';
			$close_li_item = '</div></li>';
		} else {
			$thumbnail	 = 'widgetfull';
			$open_li_item  = '<li class="post-box vertical-small'.$no_image.'">';
			$close_li_item = '</li>';
		}

		$posts = new WP_Query( array(
			'post_type' => 'reviews',
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => $qty,
			'ignore_sticky_posts' => true,
			'no_found_rows' => true,
			'post_status' => 'publish',
		) );

		echo '<ul class="recent-reviews">';
		
		while ( $posts->have_posts() ) { $posts->the_post(); ?>
			<?php echo $open_li_item; ?>
				<?php if ( $show_thumb2 == 1 ) : ?>
				<div class="post-img">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"  class="mts-posts">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail( 'splash-' . $thumbnail, array( 'title' => '' ) ); ?>
						<?php } else { ?>
							<img class="wp-post-image" src="<?php echo get_template_directory_uri() . '/images/nothumb-splash-' . $thumbnail . '.png'; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
						<?php } ?>
						<?php if ( $comment_num == 1 ) : ?>
							<div class="post-number"><i class="fa fa-comments"></i>&nbsp;<?php comments_number(__('0','splash'),__('1','splash'),'%'); ?></div>
						<?php endif; ?>
					</a>
				</div>
				<?php endif; ?>
				<div class="post-title">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo esc_html( mts_truncate( get_the_title(), $title_length, 'words' ) ); ?></a>
					<?php if ( $show_excerpt == 1 ) : ?>
						<?php echo mts_excerpt($excerpt_length); ?>
					<?php endif; ?>
					<div><?php if(get_post_meta(get_the_ID(), 'mts_overall_score', true)): ?>
						<span class="rating3"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta(get_the_ID(), 'mts_overall_score', true); ?>.png"/></span>
					<?php else: ?>
						<?php if (function_exists('wp_review_show_total')) wp_review_show_total(); ?>
					<?php endif; ?></div>
				</div>
				<div class="post-info">
				</div> <!--.post-info-->
			<?php echo $close_li_item; ?>
		<?php }
		wp_reset_postdata();
		echo '</ul>'."\r\n";
	}

}

// Register widget
add_action( 'widgets_init', 'register_mts_recent_reviews_widget' );
function register_mts_recent_reviews_widget() {
	register_widget( 'mts_recent_reviews_widget' );
}