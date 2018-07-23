<?php

function bdt_headerStyle ($this) { ?>
	<div class="header-wrapper">

		<?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>
			<div class="toolbar-wrapper uk-hidden-small">
				<div class="uk-container uk-container-center">
					<div class="tm-toolbar uk-clearfix uk-hidden-small">
						<?php if ($this['widgets']->count('toolbar-l')) : ?>
						<div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
						<?php endif; ?>

						<?php if ($this['widgets']->count('toolbar-r')) : ?>
						<div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="uk-container uk-container-center">

			<!-- Start default header style -->
			<?php if ($this['config']->get('header') == 'default') : ?>
				<div class="tm-headerbar uk-clearfix">

					<?php get_template_part( 'template-parts/logo-default'); ?>

					<?php if ($this['widgets']->count('menu')) : ?>
						<div class="navigation-wrapper uk-align-right">
							<nav class="tm-navbar uk-navbar" data-uk-sticky id="tmMainMenu">

								<?php if ($this['widgets']->count('menu')) : ?>
								<?php echo $this['widgets']->render('menu'); ?>
								<?php endif; ?>

								<?php get_template_part( 'template-parts/logo-small' ); ?>
								
								<?php if ($this['widgets']->count('offcanvas')) : ?>
								<a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
								<?php endif; ?>

								<?php if ($this['widgets']->count('search')) : ?>
								<div class="uk-navbar-flip">
									<div class="uk-navbar-content uk-hidden-small"><?php echo $this['widgets']->render('search'); ?></div>
								</div>
								<?php endif; ?>


							</nav>
						</div>
					<?php endif; ?>

					<?php echo $this['widgets']->render('headerbar'); ?>
				</div>
			<?php endif; ?>
			<!-- // End default header style -->

			<!-- Start style2 header style -->
			<?php if ($this['config']->get('header') == 'style2') : ?>
					<div class="tm-headerbar uk-clearfix">
						<div class="tm-logo-headerbar uk-clearfix uk-hidden-small">
							<?php get_template_part( 'template-parts/logo-default'); ?>
							
							<?php if ($this['widgets']->count('headerbar')) : ?>
								<div class="headerbar-wrapper uk-align-right">
									<?php echo $this['widgets']->render('headerbar'); ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ($this['widgets']->count('menu')) : ?>
							<div class="navigation-wrapper">
								<nav class="tm-navbar uk-navbar" data-uk-sticky id="tmMainMenu">

									<?php if ($this['widgets']->count('menu')) : ?>
									<?php echo $this['widgets']->render('menu'); ?>
									<?php endif; ?>

									<?php if ($this['widgets']->count('offcanvas')) : ?>
									<a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
									<?php endif; ?>

									<?php if ($this['widgets']->count('search')) : ?>
									<div class="uk-navbar-flip">
										<div class="uk-navbar-content uk-hidden-small"><?php echo $this['widgets']->render('search'); ?></div>
									</div>
									<?php endif; ?>

									<?php get_template_part( 'template-parts/logo-small' ); ?>

								</nav>
							</div>
						<?php endif; ?>
					</div>
			<?php endif; ?>
			<!-- // End style2 header style -->

			<!-- Start style3 header style -->
			<?php if ($this['config']->get('header') == 'style3') : ?>
					<div class="tm-headerbar uk-clearfix">
						
						<div class="tm-logo-headerbar uk-clearfix uk-hidden-small">
							<div class="tm-logo-wrapper uk-align-center">
								<?php get_template_part( 'template-parts/logo-default' ); ?>
							</div>
						</div>

						<?php if ($this['widgets']->count('menu')) : ?>
							<div class="navigation-wrapper">
								<nav class="tm-navbar uk-navbar" data-uk-sticky id="tmMainMenu">
									<?php if ($this['widgets']->count('menu')) : ?>
									<?php echo $this['widgets']->render('menu'); ?>
									<?php endif; ?>

									<?php if ($this['widgets']->count('offcanvas')) : ?>
									<a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
									<?php endif; ?>

									<?php if ($this['widgets']->count('search')) : ?>
									<div class="uk-navbar-flip">
										<div class="uk-navbar-content uk-hidden-small"><?php echo $this['widgets']->render('search'); ?></div>
									</div>
									<?php endif; ?>

									<?php get_template_part( 'template-parts/logo-small' ); ?>
								</nav>
							</div>
						<?php endif; ?>

						<?php if ($this['widgets']->count('headerbar')) : ?>
							<div class="headerbar-wrapper">
								<?php echo $this['widgets']->render('headerbar'); ?>
							</div>
						<?php endif; ?>
					</div>
			<?php endif; ?>
			<!-- // End style3 header style -->
		</div>
	</div><?php 
}