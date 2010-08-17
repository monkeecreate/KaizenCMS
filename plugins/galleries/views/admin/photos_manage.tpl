{include file="inc_header.tpl" page_title="Gallery :: Photos :: Manage Photos" menu="galleries" page_style="fullContent"}
{assign var=subMenu value="Galleries"}

<section id="content" class="content">
	<header>
		<h2>Manage Galleries &raquo; Edit Gallery</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "galleries"}
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
		<h3>{$aGallery.name}</h3>
		<form method="post" action="/admin/galleries/{$aGallery.id}/photos/manage/s/" enctype="multipart/form-data" style="width:685px;position:relative;">
			{foreach from=$aPhotos item=aPhoto}
				<div style="margin: 0 40px 30px 0;padding: 0 0 15px;border-bottom: 1px solid #aaa;overflow:hidden;">
					<img width="300px" src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=300&height=300" class="image" style="float:left;margin-right:15px;">
					<label>Title:</label><br />
					<input type="text" name="photo[{$aPhoto.id}][title]" maxlength="100" value="{$aPhoto.title}" style="width:300px;"><br />
					<label>Description:</label><br />
					<textarea name="photo[{$aPhoto.id}][description]" style="width:300px;height:100px;">{$aPhoto.description}</textarea>
				</div>
			{/foreach}
			<div class="clear">&nbsp;</div>
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/galleries/{$aGallery.id}/photos/" title="Cancel">Cancel</a>
		</form>
	</section>
</section>
{include file="inc_footer.tpl"}
