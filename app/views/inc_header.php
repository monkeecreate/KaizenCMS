<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<!-- May the source be with you! -->
	<meta charset="utf-8">
	<meta name="description" content="<?= $this->getSetting("site-description") ?>">
	<!-- Facebook -->
	<meta property="og:title" content="<?php if(!empty($page_title)){ echo $page_title.' | '; } echo $this->getSetting("site-title"); ?>">
	<meta property="og:description" content="">
	<meta property="og:url" content="/">
	<meta property="og:image" content="">
	<!-- /Facebook -->

	<title><?php if(!empty($page_title)){ echo $page_title.' | '; } echo $this->getSetting("site-title"); ?></title>

	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	<link rel="sitemap" href="/sitemap.xml" type="application/xml" title="Sitemap">

	<!-- Remove if you have a favicon.ico in your root dir -->
	<link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=">

	<link rel="stylesheet" href="/css/style.css?v=1" type="text/css">

	<script src="/js/modernizr-2.6.2.min.js"></script>
</head>
<body <?php if(!empty($menu)): ?> class="page-$menu"<?php endif; ?>>
	<!--[if lt IE 7 ]><p class="browsehappy">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

	<header role="banner">
		<hgroup>
			<h1><a href="/" title="<?= $this->getSetting("site-title") ?>"><?= $this->getSetting("site-title") ?></a></h1>
			<h2>Site Slogan/Tag Line</h2>
		</hgroup>

		<nav role="navigation">
			<ul>
				<li><a href="/" title="<?= $this->getSetting("site-title") ?>" class="<?= currentMenu(['home']) ?>">Home</a></li>
				<li><a href="/about/" title="About Us" class="<?= currentMenu(['about']) ?>">About</a></li>
				<li><a href="/contact/" title="Contact Us" class="<?= currentMenu(['contact']) ?>">Contact</a></li>
			</ul>
		</nav>
	</header>

	<div class="main" role="main">
