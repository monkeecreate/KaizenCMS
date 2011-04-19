<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
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
	<link rel="stylesheet" href="/css/style.css?v=1" type="text/css">
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print"> 
	
	<script src="/scripts/modernizr-1.7.min.js"></script>
</head>
{flush()}
<body{if !empty($menu)} class="{$menu}"{/if}>
	<div id="wrapper">
		<header>
			<h1><a href="/" title="{getSetting tag="title"}">{getSetting tag="title"}</a></h1>
			
			<nav>
				<ul>
					<li><a href="/" title="" class="{currentMenu var="home"}">Home</a></li>
					<li><a href="/about/" title="About Us" class="{currentMenu var="about"}">About</a></li>
					<li><a href="/contact/" title="Contact Us" class="{currentMenu var="contact"}">Contact</a></li>
				</ul>
			</nav>
		</header>
		
		<section id="content" class="content">