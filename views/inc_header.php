<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta name="description" content="<?php print $this->getSetting("site-description"); ?>">
	<meta name="viewport" content="width=device-width; initial-scale=1.0, maximum-scale=1">
	<!-- iPhone -->
	<meta name="apple-mobile-web-app-capable" content="no">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	<!-- IE -->
	<meta name="application-name" content="<?php $this->getSetting("site-title"); ?>">
	<meta name="msapplication-tooltip" content="<?php print $this->getSetting("site-description"); ?>">
	<meta name="msapplication-starturl" content="/?iePinned=true">
	<!-- /IE -->
	<!-- Facebook -->
	<meta property="og:title" content="<?php 
		if(!empty($page_title)) 
			print $page_title . "|"; 
		print $this->getSetting("site-title"); ?>">
	<meta property="og:description" content="">
	<meta property="og:url" content="/">
	<meta property="og:image" content="">
	<!-- /Facebook -->
	
	<title><?php                 
		if(!empty($page_title))  
                        print $page_title . "|"; 
                print $this->getSetting("site-title"); ?></title>
	
	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	<link rel="sitemap" href="/sitemap.xml" type="application/xml" title="Sitemap">
	
	<link rel="stylesheet" href="/css/style.css?v=1" type="text/css">
	
	<script src="/scripts/modernizr-2.5.3.min.js"></script>
</head>
<?php
if(empty($menu)) 
	print "<body>";
else
	print "<body class=\"$menu\">";
?>
	<!--[if lt IE 7 ]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	
	<header role="banner">
		<hgroup>
			<h1><a href="/" title="<?php print $this->getSetting("site-title"); ?>"><?php print $this->getSetting("site-title"); ?></a></h1>
			<h2>Site Slogan/Tag Line</h2>
		</hgroup>		
		
		<nav role="navigation">
			<ul>
				<li><a href="/" title="<?php $this->getSetting("site-title"); ?>" class="{currentMenu var="home"}">Home</a></li>
				<li><a href="/about/" title="About Us" class="{currentMenu var="about"}">About</a></li>
				<li><a href="/contact/" title="Contact Us" class="{currentMenu var="contact"}">Contact</a></li>
			</ul>
		</nav>
	</header>
	
	<div class="main pull-left" role="main">
