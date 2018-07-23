<?php 

function beautysalon_comment( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

		<article class="uk-comment" id="comment-<?php comment_ID(); ?>"> 
		 
			<header class="uk-comment-header">
				<div class="uk-comment-avatar"><?php echo get_avatar($comment, $size = '50'); ?></div>
				<h3 class="uk-comment-title"><?php if($comment->comment_author_url == '' || $comment->comment_author_url == 'http://Website'){ echo get_comment_author(); } else { echo comment_author_link(); } ?></h3>
				<p class="uk-comment-meta"><?php printf(esc_html__('%1$s at %2$s', 'beautysalon'), get_comment_date(),  get_comment_time() ) ?><?php edit_comment_link( esc_html__( '(Edit)', 'beautysalon'),'  ','' ) ?>
				&middot; <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>  </p>  
			</header>

			<div class="uk-comment-body"><?php comment_text() ?></div>

			<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'beautysalon' ) ?></em>
			<?php endif; ?>

		</article>
	<?php
}

?>

<div class="uk-clearfix"></div>

<hr class="uk-article-divider">

<div id="comments">

	<?php
	
		if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
			die ('Please do not load this page directly. Thanks!');
	
		if ( post_password_required() ) { ?>
			<div class="uk-alert uk-alert-warning"><?php esc_html_e('This post is password protected. Enter the password to view comments.', 'beautysalon'); ?></div></div>
		<?php
			return;
		}
	?>
	
	<?php if ( have_comments() ) : ?>
		
		<div class="uk-margin itemComments">

			<h3 class="uk-h3"><?php comments_number(esc_html__('Comments', 'beautysalon'), esc_html__('1 Comment', 'beautysalon'), esc_html__('% Comments', 'beautysalon') );?></h3>
		
			<div class="navigation">
				<div class="next-posts"><?php previous_comments_link() ?></div>
				<div class="prev-posts"><?php next_comments_link() ?></div>
			</div>
		
			<ul class="uk-comment-list">
				 <?php wp_list_comments(array( 'callback' => 'beautysalon_comment' )); ?>
			</ul>
		
			<div class="navigation">
				<div class="next-posts"><?php previous_comments_link() ?></div>
				<div class="prev-posts"><?php next_comments_link() ?></div>
			</div>
		</div>

		<hr class="uk-article-divider">
		
	 <?php else : // this is displayed if there are no comments so far ?>
	
		<?php if ( comments_open() ) : ?>
			<!-- If comments are open, but there are no comments. -->
	
		 <?php else : // comments are closed ?>
			<div class="uk-alert uk-alert-warning"><?php esc_html_e('Comments are closed.', 'beautysalon'); ?></div>
	
		<?php endif; ?>
		
	<?php endif; ?>
		
		
<?php if ( comments_open() ) : ?>

	<div class="comments-reply">

	<?php
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		//Custom Fields
		$fields =  array(
			'author' => '<div id="respond-inputs" class="uk-grid"><p class="uk-width-1-1 uk-width-medium-1-3"><input name="author" type="text" placeholder="' . esc_html__('Name (required)', 'beautysalon') . '" size="30"' . $aria_req . '  class="uk-width-1-1" /></p>',
			
			'email'  => '<p class="uk-width-1-1 uk-width-medium-1-3"><input name="email" type="text" placeholder="' . esc_html__('E-Mail (required)', 'beautysalon') . '" size="30"' . $aria_req . '  class="uk-width-1-1" /></p>',
			
			'url'    => '<p class="uk-width-1-1 uk-width-medium-1-3"><input name="url" type="text" placeholder="' . esc_html__('Website', 'beautysalon') . '" size="30" class="uk-width-1-1" /></p></div>',
		);

		//Comment Form Args
        $comments_args = array(
        	'class_form' => 'uk-form',
			'fields'        => $fields,
			'title_reply'   => esc_html__('Leave a reply', 'beautysalon'),
			'comment_field' => '<div id="respond-textarea"><p class="uk-width-1-1"><textarea id="comment" class="uk-width-1-1" placeholder="'.esc_html__('Your comment here (required)', 'beautysalon').'" name="comment" aria-required="true" cols="58" rows="10" tabindex="4"></textarea></p></div>',
			'label_submit'  => esc_html__('Submit Comment','beautysalon'),
			'class_submit' => 'uk-button uk-button-primary uk-button-large',
		);
		
		// Show Comment Form
		comment_form($comments_args);

	?>

	</div>	

<?php endif;  ?>

</div>