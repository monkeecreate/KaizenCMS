{$menu = "posts"}{$subMenu = "Posts"}
{include file="inc_header.tpl" sPageTitle="Posts"}

	<h1>Posts <a class="btn btn-primary pull-right" href="/admin/posts/add/" title="Create a New Post" rel="tooltip" data-placement="bottom"><i class="icon-plus icon-white"></i> Create Post</a></h1>
	{include file="inc_alerts.tpl"}

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Title</th>
				<th>Publish On</th>
				<th>Author</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aPosts item=aPost}
				<tr>
					<td class="data-table-status">
						{if $aPost.active == 1 && $aPost.publish_on < $smarty.now}
							<span class="hidden">active</span><img src="/images/icons/bullet_green.png" alt="active">
						{elseif $aPost.active == 0}
							<span class="hidden">inactive</span><img src="/images/icons/bullet_red.png" alt="inactive">
						{else}
							<span class="hidden">not published</span><img src="/images/icons/bullet_yellow.png" alt="not published">
						{/if}
					</td>
					<td><a href="{$aPost.url}" title="View {$aPost.title}" target="_blank">{$aPost.title}</a></td>
					<td class="center">{$aPost.publish_on|formatDateTime}</td>
					<td>{$aPost.author.fname} {$aPost.author.lname}</td>
					<td class="center">
						<a href="/admin/posts/edit/{$aPost.id}/" title="Edit Post" rel="tooltip"><i class="icon-pencil"></i></a>
						<a href="/admin/posts/delete/{$aPost.id}/" title="Delete Post" rel="tooltip" onclick="return confirm('Are you sure you would like to delete: {$aPost.title}?');"><i class="icon-trash"></i></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<ul class="data-table-legend">
		<li class="bullet-green">Active, published</li>
		<li class="bullet-yellow">Active, pending publish</li>
		<li class="bullet-red">Inactive, expired</li>
	</ul>

{footer}
<script>
$('.data-table').dataTable({
	/* DON'T CHANGE */
	"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
	"sPaginationType": "full_numbers",
	"bLengthChange": false,
	/* CAN CHANGE */
	"bStateSave": true,
	"aaSorting": [[1, "asc"]], //which column to sort by (0-X)
	"iDisplayLength": 10 //how many items to display per page
});
$('.dataTable-header').prepend('{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}');
</script>
{/footer}
{include file="inc_footer.tpl"}