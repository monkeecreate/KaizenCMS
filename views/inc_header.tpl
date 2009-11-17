<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{if !empty($page_title)}{$page_title} - {/if}CMS Demo</title>
	<link href="/css/generic.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/css/style.css" media="screen" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	<script type="text/javascript" src="/scripts/jquery/jquery-1.3.2.min.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<ul id="menu">
			<li><a href="/"{selected_menu var="home"}>Home</a></li>
			<li><a href="/news/"{selected_menu var="news"}>News</a></li>
			<li><a href="/calendar/"{selected_menu var="calendar"}>Calendar</a></li>
			<li><a href="/events/"{selected_menu var="events"}>Events</a></li>
			<li><a href="/faq/"{selected_menu var="faq"}>FAQ</a></li>
			<li><a href="/links/"{selected_menu var="links"}>Links</a></li>
			<li><a href="/documents/"{selected_menu var="documents"}>Documents</a></li>
			<li><a href="/testimonials/"{selected_menu var="testimonials"}>Testimonials</a></li>
			<li><a href="/galleries/"{selected_menu var="galleries"}>Photo Gallery</a></li>
			<li><a href="/test-content/"{selected_menu var="test-content"}>Test Menu</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div id="content">