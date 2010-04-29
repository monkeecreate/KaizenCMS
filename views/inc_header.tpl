<!DOCTYPE html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="{getSetting tag="description"}">
	<meta name="keywords" content="{getSetting tag="keywords"}">
	<meta name="robots" content="index, follow">
	
	<title>{if !empty($page_title)}{$page_title} | {/if}{getSetting tag="title"}</title>
	
	<link href="/css/generic.css" rel="stylesheet" type="text/css" />
	<link href="/css/style.css" media="screen, projection, print" rel="stylesheet" type="text/css" />
	<link href="/css/print.css" media="print" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	
	<script type="text/javascript" src="/scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="/scripts/common.js"></script>
	
	<!--[if IE]><script type="text/javascript" src="/scripts/html5.js"></script><![endif]-->
</head>
<body class="{$menu}">
<div id="wrapper">
	<header>
		<h1><a href="/" title="Kaizen CMS">Kaizen CMS</a></h1>

		<nav>
			<ul>
				<li><a href="/" class="{currentMenu var="home"}">Home</a></li>
				<li><a href="/news/" class="{currentMenu var="news"}">News</a></li>
				<li><a href="/calendar/" class="{currentMenu var="calendar"}">Calendar</a></li>
				<li><a href="/events/" class="{currentMenu var="events"}">Events</a></li>
				<li><a href="/faq/" class="{currentMenu var="faq"}">FAQ</a></li>
				<li><a href="/links/" class="{currentMenu var="links"}">Links</a></li>
				<li><a href="/documents/" class="{currentMenu var="documents"}">Documents</a></li>
				<li><a href="/testimonials/" class="{currentMenu var="testimonials"}">Testimonials</a></li>
				<li><a href="/galleries/" class="{currentMenu var="galleries"}">Photo Gallery</a></li>
				<li><a href="/directory/" class="{currentMenu var="directory"}">Directory</a></li>
				<li><a href="/social/" class="{currentMenu var="social"}">Social</a></li>
				<li><a href="/test-content/" class="{currentMenu var="test-content"}">Test Menu</a></li>
			</ul>
		</nav>
	</header>
	
	<div id="wrapper-inner">
		<section id="content" class="content column">