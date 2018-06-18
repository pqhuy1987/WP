<?php
/**
 * The template for displaying the footer.
 */
$mts_options = get_option(MTS_THEME_NAME);
// default = 3
$first_footer_num  = 3;
?>
	</div><!--#page-->
	<footer id="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
			<?php if ( isset( $mts_options['mts_first_footer'] ) && $mts_options['mts_first_footer']) : ?>
				<div class="footer-widgets first-footer-widgets widgets-num-<?php echo $first_footer_num; ?>">
				<?php
				for ( $i = 1; $i <= $first_footer_num; $i++ ) {
					$sidebar = ( $i == 1 ) ? 'footer-1' : 'footer-'.$i;
					$class = ( $i == $first_footer_num ) ? 'f-widget last f-widget-'.$i : 'f-widget f-widget-'.$i;
					?>
					<div class="<?php echo $class;?>">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $sidebar ) ) : ?><?php endif; ?>
					</div>
					<?php
				}
				?>
			<?php endif; ?>
			<div class="copyrights">
				<?php mts_copyrights_credit(); ?>
			</div> 
		</div><!--.container-->
	</footer><!--#site-footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>