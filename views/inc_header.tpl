<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta name="keywords" content="{getSetting tag="keywords"}">
	<meta name="description" content="{getSetting tag="description"}">
	<!-- iPhone -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	
	<title>{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="title"}</title>
	
	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="stylesheet" href="/css/reset.css" type="text/css">	
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print">
	<link rel="stylesheet" href="/css/screen.css?v=1" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/css/iphone.css" media="only screen and (max-width: 480px)" type="text/css">
	<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="/css/iphone4.css"> <!-- Target iPhone 4 Retina Display -->
	<!--[if IE]><link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" href="/css/ie7.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" href="/css/ie6.css" type="text/css" media="screen, projection" /><![endif]-->
	
	<script src="/scripts/modernizr-1.5.min.js"></script>
</head>
<!--[if lt IE 7 ]> <body class="ie6 {$menu}"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7 {$menu}"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8 {$menu}"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9 {$menu}"> <![endif]-->
<!--[if gt IE 9]>  <body class="{$menu}">     <![endif]-->
<!--[if !IE]><!--> <body class="{$menu}"> <!--<![endif]-->
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