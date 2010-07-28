<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="keywords" content="{getSetting tag="keywords"}">
	<meta name="description" content="{getSetting tag="description"}">
	<!-- iPhone -->
	<meta name="viewport" content="width=device-width">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	
	<title>{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="title"}</title>
	
	<link rel="stylesheet" href="/css/reset.css" type="text/css">	
	<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print">
	<link rel="stylesheet" href="/css/iphone.css" media="only screen and (max-width: 480px)" type="text/css">
	<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="/css/iphone4.css"> <!-- Target iPhone 4 Retina Display -->
	<!--[if IE]><link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" href="/css/ie7.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" href="/css/ie6.css" type="text/css" media="screen, projection" /><![endif]-->
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	
	<script src="/scripts/jquery-1.4.2.min.js"></script>
	<script src="/scripts/jquery.scrollTo.min.js"></script>
	<script src="/scripts/jquery.rsv.js"></script>
	<script src="/scripts/common.js"></script>
	<!--[if lt IE 9]>
	<script src="/scripts/html5.js"></script>
	<![endif]-->
</head>
<body class="{$menu}">
	<div id="wrapper">
		<header>
			<h1><a href="/" title="">Kaizen CMS</a></h1>
			
			<nav>
				<ul>
					<li><a href="/" title="" class="{currentMenu var="home"}">Home</a></li>
					<li><a href="/about/" title="About Us" class="{currentMenu var="about"}">About</a></li>
					<li><a href="/contact/" title="Contact Us" class="{currentMenu var="contact"}">Contact</a></li>
				</ul>
			</nav>
		</header>
		
		<section id="content" class="content">