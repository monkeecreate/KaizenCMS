<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>{if !empty($page_title)}{$page_title} - {/if}{getSetting tag="title"} Admin</title>

	<link rel="stylesheet" href="/css/admin/reset.css" type="text/css">
	<link rel="stylesheet" href="/css/admin/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/scripts/ui-themes/smoothness/jquery-ui-1.8.custom.css" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,bold" rel="stylesheet" type="text/css">
	<!--[if IE]><link rel="stylesheet" href="/css/admin/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="/css/admin/ie7.css" type="text/css" media="screen, projection" /><![endif]-->

	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">

	<script src="/scripts/modernizr-2.6.2.min.js"></script>
	<script src="/scripts/jquery-1.8.1.min.js"></script>
	<script src="/scripts/jquery-ui-1.8.custom.min.js"></script>
	<script src="/scripts/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script src="/scripts/jquery.rsv.js"></script>
	<script src="/scripts/jquery.validateForm.js"></script>
	<script src="/scripts/jquery.cookie.js"></script>
	<script src="/scripts/tiny_mce/tiny_mce.js"></script>
	<script src="/scripts/common_admin.js"></script>
	<!--[if lt IE 9]>
	<script src="/scripts/IE9.js">IE7_PNG_SUFFIX=".png";</script>
	<![endif]-->
</head>
<body class="{$page_style}">
	<div id="wrapper">
		<header>
			<div class="backToSite">
				<a href="/" title="{getSetting tag="title"}"><span class="iconic">6</span> back to site</a>
			</div>
			{if !empty($user_details)}
				<div class="loggedIn">Logged in as <b>{$user_details.fname} {$user_details.lname}</b> <span class="divider">|</span> <a href="/admin/users/edit/{$user_details.id}/">Edit Profile</a> <span class="divider">|</span> <a href="/admin/logout/" title="Logout">Logout</a></div>
			{/if}

			<h1>{getSetting tag="title"} Admin</h1>

			{if $loggedin == 1}
				<nav>
					<ul>
						{foreach from=$aAdminMainMenu item=aMenu key=x}
							<li><a{if $menu == $x} class="active"{/if} href="{$aMenu.menu[0].link}" tabindex="-1">{$aMenu.title|clean_html}</a></li>
						{/foreach}
						{if !empty($aAdminSubMenu)}
							<li class="adminSubMenu">
								<a href="">%</a>
								<ul class="dropdown">
									{foreach from=$aAdminSubMenu item=aMenu}
										<li><a{if $menu == $x} class="active"{/if} href="{$aMenu.menu[0].link}" tabindex="-1">{$aMenu.title|clean_html}</a></li>
									{/foreach}
								</ul>
							</li>
						{/if}
					</ul>
				</nav>
			{else}
				&nbsp;
			{/if}
		</header>

		<div id="wrapper-inner">
			{if !empty($sSecurityError)}
				<div class="ui-state-error ui-corner-all notice">
					<span class="icon ui-icon ui-icon-alert"></span>
					{$sSecurityError}
				</div>
			{/if}
			{if !empty($smarty.get.errors)}
				{foreach from=$smarty.get.errors item=sPageError}
					<div class="ui-state-error ui-corner-all notice">
						<span class="icon ui-icon ui-icon-alert"></span>
						{$sPageError|urldecode}
					</div>
				{/foreach}
			{/if}
			{if !empty($page_error)}
				<div class="ui-state-error ui-corner-all notice">
					<span class="icon ui-icon ui-icon-alert"></span>
					{$page_error}
				</div>
			{/if}
			{if !empty($page_notice)}
				<div class="ui-state-highlight ui-corner-all notice">
					<span class="icon ui-icon ui-icon-info"></span>
					{$page_notice}
				</div>
			{/if}