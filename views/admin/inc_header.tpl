<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title>{if !empty($sPageTitle)}{$sPageTitle} | {/if}{getSetting tag="site-title"}</title>

	<link rel="shortcut icon" href="/images/favicon.ico">
	<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
	<link rel="author" href="/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">

	<link href="/css/admin/style.css?v1" rel="stylesheet">
	<link href="/js/jqueryui/smoothness/jquery-ui-1.9.0.custom.css" rel="stylesheet">

	<script src="/js/modernizr-2.6.2.min.js"></script>
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

				<a class="brand" href="/" title="Visit http://{$smarty.server.SERVER_NAME}/" rel="tooltip" data-placement="bottom">{getSetting tag="site-title"}</a>

				<div class="nav-collapse pull-right">
					<ul class="nav">
					<li><a href="#edit-account-modal" data-toggle="modal" rel="popover" data-trigger="hover" data-content="Change your password or update your personal information. Remember to keep your email address up to date." data-original-title="Edit Account" data-placement="bottom">Edit Account</a></li>
					<li><a href="#support" rel="popover" data-trigger="hover" data-content="Coming Soon!" data-placement="bottom">Help &amp; Support</a></li>
					<li><a href="/admin/logout/" title="Log Out">Log Out</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<!--[if lt IE 7]><div class="alert alert-warning">
			Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.
		</div><![endif]-->
		{if $menu != "login"}
		<div class="row-fluid">
			<div class="span3">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						<li class="nav-{$x}{if $menu === "dashboard"} active{/if}"><a href="/admin/" title="Dashboard" rel="tooltip" data-placement="right">Dashboard</a></li>
						{foreach from=$aAdminFullMenu item=aMenu key=x}
							<li class="nav-{$x}{if $menu == $x} active{/if}"><a href="{$aMenu.menu[0].link}" title="{$aMenu.title}" rel="tooltip" data-placement="right">{$aMenu.title}</a></li>
						{/foreach}
					</ul>
				</div><!--/.well -->

				<div class="thumbnail">
					<img src="http://placehold.it/295x100" alt="">
		            <div class="caption">
						<h5>Are you running Facebook Ads?</h5>
						<p>If you aren't running Facebook Ads then you are missing out on 80,000 potential customers. Don't worry, we can help. <a href="http://crane-west.com" title="Contact Crane | West">Contact Us</a>.</p>
		        	</div>
		        </div>
			</div><!--/span-->
			{/if}

			<div class="span9">
				<div class="row-fluid">