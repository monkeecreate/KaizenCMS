<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{if !empty($page_title)}{$page_title} - {/if}Site Admin</title>
	<link rel="stylesheet" href="/css/admin/generic.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	<link type="text/css" href="/scripts/jquery-ui/themes/crane-west/crane-west.css" rel="stylesheet" />
	<script type="text/javascript" src="/scripts/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.core.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.accordion.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.dialog.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.draggable.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.resizable.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/ui.slider.js"></script>
	<script type="text/javascript" src="/scripts/jquery-ui/effects.core.js"></script>	
	<script type="text/javascript" src="/scripts/jquery-ui/effects.shake.js"></script>
	<script type="text/javascript" src="/scripts/jquery/qtip/jquery.qtip-1.0.0-rc3.js"></script>
	<script type="text/javascript" src="/scripts/admin_common.js"></script>
</head>
<body>
<div id="site-container">
	<div id="header">
		<div id="header-userinfo">
			{if !empty($user_details)}
				Logged in as <b>{$user_details.fname} {$user_details.lname}</b> <span class="divider">|</span> <a href="/admin/logout/" title="Logout">Logout</a>
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
					<div class="header"><a href="#" tabindex="-1">Content Pages</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/content/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Page</a></li>
							<li><a href="/admin/content/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Pages</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">News Articles</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/news/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add News Article</a></li>
							<li><a href="/admin/news/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage News Articles</a></li>
							<li><a href="/admin/news/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/news/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Events</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/events/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Event</a></li>
							<li><a href="/admin/events/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Events</a></li>
							<li><a href="/admin/events/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/events/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Calendar</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/calendar/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Calendar Event</a></li>
							<li><a href="/admin/calendar/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Calendar Events</a></li>
							<li><a href="/admin/calendar/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/calendar/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					
					<div class="header"><a href="#" tabindex="-1">FAQ</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/faq/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Question &amp; Answer</a></li>
							<li><a href="/admin/faq/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Question</a></li>
							<li><a href="/admin/faq/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/faq/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Links</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/links/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Link</a></li>
							<li><a href="/admin/links/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Links</a></li>
							<li><a href="/admin/links/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/links/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Documents</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/documents/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Document</a></li>
							<li><a href="/admin/documents/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Documents</a></li>
							<li><a href="/admin/documents/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/documents/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Testimonials</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/testimonials/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Testimonial</a></li>
							<li><a href="/admin/testimonials/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Testimonials</a></li>
							<li><a href="/admin/testimonials/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/testimonials/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Photo Galleries</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/galleries/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Gallery</a></li>
							<li><a href="/admin/galleries/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Galleries</a></li>
							<li><a href="/admin/galleries/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/galleries/categories/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Promos</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/promos/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Promo</a></li>
							<li><a href="/admin/promos/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Promos</a></li>
							{if $user_details.id == 1}
							<li><a href="/admin/promos/positions/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Position</a></li>
							<li><a href="/admin/promos/positions/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Positions</a></li>
							{/if}
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Directory</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/directory/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Directory</a></li>
							<li><a href="/admin/directory/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Directories</a></li>
							<li><a href="/admin/directory/categories/?addcategory=1"><div class="icon ui-icon ui-icon-circle-plus"></div> Add Category</a></li>
							<li><a href="/admin/directory/categories"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Categories</a></li>
						</ul>
					</div>
					<div class="header"><a href="#" tabindex="-1">Users</a></div>
					<div class="info">
						<ul>
							<li><a href="/admin/users/add/"><div class="icon ui-icon ui-icon-circle-plus"></div> Add User</a></li>
							<li><a href="/admin/users/"><div class="icon ui-icon ui-icon-circle-triangle-e"></div> Manage Users</a></li>
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
						{if $menu == 'content'}
							0
						{elseif $menu == 'news'}
							1
						{elseif $menu == 'events'}
							2
						{elseif $menu == 'calendar'}
							3
						{elseif $menu == 'faq'}
							4
						{elseif $menu == 'links'}
							5
						{elseif $menu == 'documents'}
							6
						{elseif $menu == 'testimonials'}
							7
						{elseif $menu == 'galleries'}
							8
						{elseif $menu == 'promos'}
							9
						{elseif $menu == 'directory'}
							10
						{elseif $menu == 'users'}
							11
						{else}
							0
						{/if}
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