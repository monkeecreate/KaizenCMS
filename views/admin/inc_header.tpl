<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title>{if !empty($page_title)}{$page_title} - {/if}Site Admin</title>
	
	<link rel="stylesheet" href="/css/admin/generic.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	<link type="text/css" href="/scripts/ui-themes/crane-west/crane-west.css" rel="stylesheet" />
	
	<script type="text/javascript" src="/scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="/scripts/common_admin.js"></script>
</head>
<body>
<div id="site-container">
	<div id="header">
		<div id="header-userinfo">
			{if !empty($user_details)}
				Logged in as <b>{$user_details.fname} {$user_details.lname}</b> <span class="divider">|</span> <a href="/admin/users/edit/{$user_details.id}/">Edit Profile</a> <span class="divider">|</span> <a href="/admin/logout/" title="Logout">Logout</a>
			{else}
				&nbsp;
			{/if}
		</div>
		<h1>Site Admin</h1>
	</div>
	<div id="content-container">
		<div id="menu">
			{if $loggedin == 1}
				<div class="menu">
					{assign var="sActiveMenu" value="0"}
					{assign var="sActiveFound" value="0"}
					{foreach from=$aAdminMenu item=aMenu key=x}
						{if $x != $menu && $sActiveFound == 0}
							{math equation="x + y" x=$sActiveMenu y=1 assign="sActiveMenu"}
						{else}
							{assign var="sActiveFound" value="1"}
						{/if}
						<div class="header"><a href="#" tabindex="-1">{$aMenu.title|clean_html}</a></div>
						<div class="info">
							<ul>
								{foreach from=$aMenu.menu item=aItem}
									<li>
										<a href="{$aItem.link}">
											<div class="icon ui-icon ui-icon-{$aItem.icon|clean_html|default:'circle-triangle-e'}"></div> {$aItem.text|clean_html}
										</a>
									</li>
								{/foreach}
							</ul>
						</div>
					{/foreach}
				</div>
				{if $sActiveFound == 0}
					{assign var="sActiveMenu" value="0"}
				{/if}
				<script type="text/javascript">
				$(function(){ldelim}
					$('.menu').accordion({ldelim}
						collapsible: false,
						autoHeight: false,
						header: ".header",
						active: {$sActiveMenu}
					{rdelim});
				{rdelim});
				</script>
			{else}
				&nbsp;
			{/if}
		</div>
		<div id="body-container"{if $loggedin != 1} style="width:448px;"{/if}>
			{if !empty($page_error)}
				<div class="ui-state-error ui-corner-all notice">
					<div class="icon ui-icon ui-icon-alert"></div>
					{$page_error}
				</div>
			{/if}
			{if !empty($page_notice)}
				<div class="notice ui-state-highlight ui-corner-all notice">
					<div class="icon ui-icon ui-icon-info"></div>
					{$page_notice}
				</div>
			{/if}
			<div id="content" class="portlet"{if $page_login == 1} style="width:440px;"{/if}>
				<div class="portlet-header">{$page_title}</div>
				<div class="body-content portlet-content">