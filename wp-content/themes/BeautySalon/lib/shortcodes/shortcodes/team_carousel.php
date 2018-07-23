<?php
// Bdthemes Team Carousel

if (!function_exists('bdthemes_team_carousel')) {
	function bdthemes_team_carousel($atts){
		extract(shortcode_atts(array(
			'posts'          => '6',
			'categories'     => 'all',
			'expertise'      => 'true',
			'excerpt'        => 'true',
			'style'          => 'white', // white, lightgrey
			'large'          => 4,
			'medium'         => 2,
			'small'          => 1,
			'scroll'         => 1,
			'arrows'         => 'true',
			'arrow_position' => 'default',
			'pagination'     => 'false',
			'autoplay'       => 'true',
			'delay'          => 4000,
			'speed'          => 350,
			'hoverpause'     => 'false',
			'lazyload'       => 'false',
			'loop'           => 'true',
			'gutter'		 => ''
		), $atts));

		global $post;

		if ($gutter == 'large') { $gutter = 50; }
		elseif ($gutter == 'medium') { $gutter = 25;}
		elseif ($gutter == 'small') { $gutter = 10;}
		elseif ($gutter == 'collapse') { $gutter = 0;}
		else { $gutter = 30; }

		$args = array(
			'post_type'      => 'team',
			'posts_per_page' => $posts,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'post_status'    => 'publish'
		);
		
		if($categories != 'all'){
			$str = $categories;
			$arr = explode(',', $str); // string to array

			$args['tax_query'][] = array(
				'taxonomy' => 'team-category',
				'field'    => 'slug',
				'terms'    => $arr
			);
		}

		wp_enqueue_script('owl-carousel');

		$wp_query = new WP_Query($args);
		$return = '';

		if( $wp_query->have_posts() ) :

			$output[] = '<div class="bdt-team-carousel style-'.esc_attr($style).'"">';

			$output[] = '<div class="bdt-owl-carousel" data-autoplay="' . $autoplay .'" data-delay="' . $delay . '" data-speed="' . $speed . '" data-arrows="' . $arrows .'" data-pagination="' . $pagination . '" data-lazyload="' . $lazyload . '" data-hoverpause="' . $hoverpause . '" data-large="' . $large . '" data-medium="' . $medium . '" data-small="' . $small . '" data-margin="' . $gutter . '" data-scroll="' . $scroll . '" data-loop="' . $loop . '">';

			$output[] = '<div class="bdt-carousel-slides">';
		
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$saved_meta = BDT_TEAM_FIELDS(get_the_ID());
		  
		  		$blog_thumbnail= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'team' );

		  		$output[] = '<div class="bdt-carousel-slide">';

		  		if($blog_thumbnail[0] != '') {
		  
		  		$output[] = '<a href="'.esc_url(get_permalink()).'" title="' . esc_attr(get_the_title()) . '" class="team-pic"><img src="'.esc_url($blog_thumbnail[0]).'" alt="' . esc_attr(get_the_title()) . '" /><span class="team-overlay"></span><i class="fa fa-align-left"></i></a>';
		  		}
		  		
		  		$output[] = '<div class="team-item-description">
								<h4><a href="'.esc_url(get_permalink()).'" title="' . esc_attr(get_the_title()) . '">'.esc_html(get_the_title()) .'</a></h4>';
					if ($expertise != 'false') {
						$output[] = '<span class="tc-expertise">'.$saved_meta['expertise'].'</span>';
					}
					if ($excerpt != 'false') {
						$output[] =	'<div>'.wp_kses_post(bdthemes_custom_excerpt(8)).'</div>';
					}
					$output[] =	'</div>';

				$output[] = '</div>';
		  
			endwhile;

			$output[] ='</div>';
			$output[] ='</div>';
		
			$output[] ='</div><div class="clear"></div>';
		
		 	wp_reset_postdata();
	  
		endif;

		return implode("\n", $output);
	}
	add_shortcode('bdt_team_carousel', 'bdthemes_team_carousel');
}