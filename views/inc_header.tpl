<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="description" content="{getSetting tag="site-description"}">
	<!-- iPhone -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	<!-- IE -->
	<meta name="application-name" content="{getSetting tag="site-title"}">
	<meta name="msapplication-tooltip" content="{getSetting tag="site-description"}">
	<meta name="msapplication-starturl" content="/?iePinned=true">
	<!-- /IE -->
	<!-- Facebook -->
	<meta property="og:title" content="{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="site-title"}">
	<meta property="og:description" content="">
	<meta property="og:image" content="">
	<!-- /Facebook -->
	
	<title>{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="site-title"}</title>
	
	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	<link rel="sitemap" href="/sitemap.xml" type="application/xml" title="Sitemap">
	
	<link rel="stylesheet" href="/css/style.css?v=1" type="text/css">
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print"> 
	
	<script src="/scripts/modernizr-2.0.6.min.js"></script>
</head>
{flush()}
<body{if !empty($menu)} class="{$menu}"{/if}>
	<div id="no-js" class="hide"><p>For full functionality of this site it is necessary to enable JavaScript. Here are the <a href="http://www.enable-javascript.com/" target="_blank"> instructions how to enable JavaScript in your web browser</a>.</p></div>
	
	<header role="banner">
		<hgroup>
			<h1><a href="/" title="{getSetting tag="site-title"}">{getSetting tag="site-title"}</a></h1>
			<h2>Site Slogan/Tag Line</h2>
		</hgroup>		
		
		<nav role="navigation">
			<ul>
				<li><a href="/" title="{getSetting tag="site-title"}" class="{currentMenu var="home"}">Home</a></li>
				<li><a href="/about/" title="About Us" class="{currentMenu var="about"}">About</a></li>
				<li><a href="/contact/" title="Contact Us" class="{currentMenu var="contact"}">Contact</a></li>
			</ul>
		</nav>
	</header>
	
	<div class="main" role="main">