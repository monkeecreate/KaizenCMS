{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials" page_style="fullContent"}
{assign var=subMenu value="Testimonials"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[1, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Testimonials</h2>
		<a href="/admin/testimonials/add/" title="Add Testimonial" class="button">Add Testimonial &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "testimonials"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>
	
	<table class="dataTable">
		<thead>
			<tr>
				<th class="empty itemStatus">&nbsp;</th>
				<th>Name</th>
				<th>Sub-Name</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aTestimonials item=aTestimonial}
				<tr>
					<td>
						{if $aTestimonial.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aTestimonial.name}</td>
					<td>{$aTestimonial.sub_name}</td>
					<td class="center">
						<a href="/admin/testimonials/edit/{$aTestimonial.id}/">
							<img src="/images/admin/icons/pencil.png">
						</a>
						<a href="/admin/testimonials/delete/{$aTestimonial.id}/"
							onclick="return confirm_('Are you sure you would like to delete this testimonial?');">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Active</li>
		<li class="bullet-red">Inactive</li>
	</ul>
</section>
{include file="inc_footer.tpl"}