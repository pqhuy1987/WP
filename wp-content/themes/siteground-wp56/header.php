<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if lte IE 6]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" />
<![endif]-->


<?php wp_head(); ?>
</head>
<body>
<div id="page_bg">	
	<div id="wrapper">
		<div id="page">

					<div id="holder">
						<div style="float:left;width:760px;">
							<div id="header">
								<div id="headerimg">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td style="text-align: center; vertical-align: middle;">
												<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
												<div class="description"><?php bloginfo('description'); ?></div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							
							<div id="menu">
									<table cellpadding="0" cellspacing="0" style="margin:0;padding: 12px 0 0 10px;width: 590px; float: left;">
										<tr>
											<td style="vertical-align: top; text-align: left;">
												<ul class="nav">
													<li class="page_item"><a href="<?php echo get_settings('home'); ?>/" title="Home">Home</a></li>
													<?php wp_list_pages('sort_column=menu_order&depth=1&title_li=');?>
												</ul>
											</td>	
										</tr>
									</table>
							<div id="wrpr">
								<div id="searchbox"> <?php include (TEMPLATEPATH . '/searchform.php'); ?> </div>
							</div>
							<div class="clr"></div>
							</div>
						
	