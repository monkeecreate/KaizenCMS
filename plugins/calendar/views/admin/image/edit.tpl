{include file="inc_header.tpl" page_title="Calendar :: Crop Image" menu="calendar" page_style="fullContent"}
{assign var=subMenu value="Calendar Events"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" minw=$minWidth minh=$minHeight rx=$minWidth ry=$minHeight values=$aEvent}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Calendar &raquo; Crop Image</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "calendar"}
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

	<section class="inner-content">
		<h3>{$aEvent.title|clean_html}</h3>

		<form name="crop" action="/admin/calendar/image/edit/s/" method="post">
			<input type="submit" value="Save Changes">
			<input type="button" value="Upload new photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/upload/';" />
			<input type="button" value="Remove Photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/delete/';" />
			
			<img src="{$sFolder}{$aEvent.id}.jpg?{$randnum}" id="cropimage" />
			{image_crop load="form"}
			
			<h4>Image Preview</h4>
			<div style="width:300px;height:225px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
				<img src="{$sFolder}{$aEvent.id}.jpg?{$randnum}" id="preview" />
			</div>
			
			<input type="hidden" name="id" value="{$aEvent.id}" />
			<input type="submit" value="Save Changes">
			<input type="button" value="Upload new photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/upload/';" />
			<input type="button" value="Remove Photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/delete/';" />
			
			<!-- <table border="0">
				<tr>
					<td>
						
					</td>
				</tr>
				<tr>
					<td>
						
						<br />
						<b>Preview:</b>
						<div style="width:300px;height:225px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
							<img src="{$sFolder}{$aEvent.id}.jpg?{$randnum}" id="preview" />
						</div>
						
					</td>
				</tr>
			</table> -->
		</form>
	</section>
</section>
{include file="inc_footer.tpl"}