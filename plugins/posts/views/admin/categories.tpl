{$menu = "posts"}{$subMenu = "Categories"}
{include file="inc_header.tpl" sPageTitle="Posts &raquo; Categories"}
	
	<h1>Posts &raquo; Categories</h1>
	{include file="inc_alerts.tpl"}
	
		<div class="row-fluid">
			<div class="span8">
				<table class="data-table table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							{if $sSort == "manual"}<th>Order</th>{/if}
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$aCategories item=aCategory}
							<tr>
								<td>{$aCategory.name}</td>
								{if $sSort == "manual"}
									<td class="small center">
										<span class="hidden">{$aCategory.sort_order}</span>
										{if $aCategory.sort_order != $minSort}
											<a href="/admin/posts/categories/sort/{$aCategory.id}/up/" title="Move Up One"><img src="/images/icons/bullet_arrow_up.png" style="width:16px;height:16px;"></a>
										{else}
											<img src="/images/blank.gif" style="width:16px;height:16px;">
										{/if}
										{if $aCategory.sort_order != $maxSort && count($aCategories) > 1}
											<a href="/admin/posts/categories/sort/{$aCategory.id}/down/" title="Move Down One"><img src="/images/icons/bullet_arrow_down.png" style="width:16px;height:16px;"></a>
										{else}
											<img src="/images/blank.gif" style="width:16px;height:16px;">
										{/if}
									</td>
								{/if}
								<td class="center">
									<a href="/admin/posts/categories/?category={$aCategory.id}" title="Edit Category"><i class="icon-pencil"></i></a>
									<a href="/admin/posts/categories/delete/{$aCategory.id}/"
									 onclick="return confirm('Are you sure you would like to delete: {$aCategory.name}?');" title="Delete Category"><i class="icon-trash"></i></a>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			
			<div class="span4 aside">
				{if !empty($aCategoryEdit)}
					<div class="accordion-group">
						<div class="accordion-heading">
							<span class="accordion-toggle">Edit Category</span>
						</div>
						<div class="accordion-body in collapse">
							<div class="accordion-inner">
								<form id="edit-form" method="post" action="/admin/posts/categories/edit/s/">
									<div class="control-group">
										<label class="control-label" for="form-name">Name</label>
										<div class="controls">
											<input type="text" name="name" id="form-name" value="{$aCategoryEdit.name}" class="span12 validate[required]"><br />
											<input type="submit" value="Save Changes" class="btn btn-primary">
											<input type="hidden" name="id" value="{$aCategoryEdit.id}">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				{else}
					<div class="accordion-group">
						<div class="accordion-heading">
							<span class="accordion-toggle">Create Category</span>
						</div>
						<div class="accordion-body in collapse">
							<div class="accordion-inner">
								<form id="add-form" method="post" action="/admin/posts/categories/add/s/">
									<div class="control-group">
										<label class="control-label" for="form-name">Name</label>
										<div class="controls">
											<input type="text" name="name" id="form-name" value="" class="span12 validate[required]"><br />
											<input type="submit" value="Create Category" class="btn btn-primary">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				{/if}
			</div>
		</div>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });
	jQuery('#edit-form').validationEngine({ promptPosition: "bottomLeft" });

	$('.data-table').dataTable({
		/* DON'T CHANGE */
		"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
		"sPaginationType": "full_numbers",
		"bLengthChange": false,
		/* CAN CHANGE */
		"bStateSave": true,
		"iDisplayLength": 10, //how many items to display per page
		{if $sSort == "manual"}
			"aaSorting": [[1, "asc"]], //which column to sort by (0-X)
			"aoColumns": [
				null,
				{ "sType": "num-html" },
				null
			]
		{else}
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		{/if}
	});
	$('.dataTable-header').prepend('{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}');
});
</script>
{/footer}
{include file="inc_footer.tpl"}