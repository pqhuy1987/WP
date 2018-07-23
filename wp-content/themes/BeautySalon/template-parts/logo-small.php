<?php
$beautysalon_logo_upload       = get_theme_mod('beautysalon_logo_upload');
$beautysalon_logo_small_upload = get_theme_mod('beautysalon_logo_small_upload');
$beautysalon_header_style      = (get_theme_mod('beautysalon_header_style', 'default') !='style3') ? 'uk-align-left' : '';

?>

<?php if ($beautysalon_logo_upload or $beautysalon_logo_small_upload) : ?>
<div itemtype="http://schema.org/Organization" itemscope="itemscope" class="uk-navbar-content uk-visible-small">
    <a class="tm-logo-small" href="<?php echo esc_url(home_url('/')); ?>" itemprop="url" title="<?php bloginfo( 'name' );?>">
        <?php if ($beautysalon_logo_small_upload) : ?>
        <img alt="<?php bloginfo( 'name' );?>" src="<?php echo esc_url($beautysalon_logo_small_upload); ?>" itemprop="logo"  />
        <?php else : ?>
        <img alt="<?php bloginfo( 'name' );?>" src="<?php echo esc_url($beautysalon_logo_upload); ?>" itemprop="logo" />
        <?php endif; ?>
        
    </a>
</div>
<?php else : ?>
<div class="uk-navbar-content uk-visible-small">
    <a class="tm-logo-small" href="<?php echo esc_url(home_url('/')); ?>">
		<?php bloginfo( 'name' );?>
	</a>
</div>						
<?php endif; ?>