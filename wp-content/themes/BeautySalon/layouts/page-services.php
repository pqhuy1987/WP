<?php get_header(); ?>


<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

    <article class="uk-article">

        <?php if (has_post_thumbnail()) : ?>
            <?php
            $width = get_option('thumbnail_size_w'); //get the width of the thumbnail setting
            $height = get_option('thumbnail_size_h'); //get the height of the thumbnail setting
            ?>
            <?php the_post_thumbnail(array($width, $height), array('class' => '')); ?>
        <?php endif; ?>

        <h1 class="uk-article-title"><?php the_title(); ?></h1>

        <?php the_content(''); ?>

        <?php edit_post_link(__('Edit this post.', 'warp'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

    </article>

    <?php endwhile; ?>
<?php endif; ?>

<?php if(get_theme_mod('beautysalon_comment_show', 1) == 1) { ?>
    <?php comments_template(); ?>
<?php } ?>


 <?php get_footer(); ?>