<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{if !empty($page_title)}{$page_title} - {/if}Las Palapas</title>
	<link rel="stylesheet" href="/css/admin/generic.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link type="text/css" href="/scripts/jquery-ui/themes/crane-west/crane-west.css" rel="stylesheet" />
	<script type="text/javascript" src="/scripts/jquery/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.core.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.accordion.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.slider.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.dialog.js"></script>
	<script type="text/javascript" src="/scripts/admin_common.js"></script>
</head>
<body>
<div id="site-container">
	<div id="header">
		<div id="header-userinfo">
			{if !empty($user_details)}
				Logged in as <b>{$user_details.fname} {$user_details.lname}</b> <span class="divider">|</span> <a href="/admin/logout/">Logout</a>
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
					<div class="header"><a href="#" tabindex="-1">Users</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/users/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add User</a></li>
							<li><a href="/admin/users/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Users</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Content Pages</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/content/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Page</a></li>
							<li><a href="/admin/content/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Pages</a></li>
						</ul>
					</div>
				</div>
				<script type="text/javascript">
				$(function(){ldelim}
					$('.menu').accordion({ldelim}
						collapsible: true,
						autoHeight: false,
						header: ".header",
						active: 
						{if $menu == 'users'}
							0
						{elseif $menu == 'content'}
							1
						{else}
							0
						{/if}
					{rdelim});
				{rdelim});
				</script>
			{/if}
		</div>
		<div id="body-container">
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
			<div id="content" class="portlet"{if $page_login == 1} style="width:400px;"{/if}>
				<div class="portlet-header">{$page_title}</div>
				<div class="body-content portlet-content">