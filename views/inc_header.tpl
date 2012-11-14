<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="description" content="{getSetting tag="description"}">
	<meta name="viewport" content="width=device-width">
	<!-- Facebook -->
	<meta property="og:title" content="{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="title"}">
	<meta property="og:description" content="">
	<meta property="og:url" content="/">
	<meta property="og:image" content="">
	<!-- /Facebook -->

	<title>{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="title"}</title>

	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	<link rel="sitemap" href="/sitemap.xml" type="application/xml" title="Sitemap">
	<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/feed/">

	<!-- Remove if you have a favicon.ico in your root dir -->
	<link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=">

	<link rel="stylesheet" href="/css/style.css?v=1" type="text/css">

	<script src="/scripts/modernizr-2.6.2.min.js"></script>
</head>
{flush()}
<body{if !empty($menu)} class="{$menu}"{/if}>
	<!--[if lt IE 7 ]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->

	<header role="banner">
		<hgroup>
			<h1><a href="/" title="{getSetting tag="title"}">{getSetting tag="title"}</a></h1>
			<h2>Site Slogan/Tag Line</h2>
		</hgroup>

		<nav role="navigation">
			<ul>
				<li><a href="/" title="{getSetting tag="title"}" class="{currentMenu var="home"}">Home</a></li>
				<li><a href="/about/" title="About Us" class="{currentMenu var="about"}">About</a></li>
				<li><a href="/contact/" title="Contact Us" class="{currentMenu var="contact"}">Contact</a></li>
			</ul>
		</nav>
	</header>

	<div class="main" role="main">