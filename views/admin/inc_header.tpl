<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<!-- iPhone -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!-- /iPhone -->
	<!-- IE -->
	<meta name="application-name" content="{getSetting tag="title"} Admin">
	<meta name="msapplication-tooltip" content="Website Admin Area">
	<meta name="msapplication-starturl" content="/?iePinned=true">
	<!-- /IE -->
	
	<title>{if !empty($sPageTitle)}{$sPageTitle} | {/if}{getSetting tag="title"}</title>
	
	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
	
	<link href="/css/admin/bootstrap.min.css" rel="stylesheet">
	<link href="/css/admin/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/css/admin/style.css" rel="stylesheet">
	<link href="/js/datatables/css/data_table.css" rel="stylesheet">
	<link href="/js/validationEngine/validationEngine.jquery.css" rel="stylesheet">
	<link href="/js/ui-themes/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	
	<script src="/js/modernizr-2.0.6.min.js"></script>
</head>
<body class="{$menu}">
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<img src="/images/logo.png" alt="logo" class="pull-left" style="margin-right: 15px;">
			
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				
				<a class="brand" href="/">{getSetting tag="title"}</a>
				
				<div class="nav-collapse pull-right">
					<ul class="nav">
					<li><a href="#">Edit Account</a></li>
					<li><a href="#about">Help &amp; Support</a></li>
					<li><a href="#contact">Logout</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						{foreach from=$aAdminFullMenu item=aMenu key=x}
							<li{if $menu == $x} class="active"{/if}><a href="{$aMenu.menu[0].link}" title="{$aMenu.title|clean_html}">{$aMenu.title|clean_html}</a></li>
						{/foreach}

					</ul>
				</div><!--/.well -->
				
				<div class="thumbnail">
		            <img src="http://placehold.it/295x100" alt="">
		            <div class="caption">
		              <h5>Are you running Facebook Ads?</h5>
		              <p>If you aren't running Facebook Ads then you are missing out on 80,000 potential customers. Don't worry, we can help. <a href="#">Contact Us</a>.</p>
		            </div>
		          </div>
			</div><!--/span-->
			
			<div class="span9">
			  <div class="row-fluid">