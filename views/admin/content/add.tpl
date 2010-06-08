{if $sSuperAdmin == true}
	{include file="inc_header.tpl" page_title="Content Pages : Add Page" menu="content" page_style="halfContent"}
{else}
	{include file="inc_header.tpl" page_title="Content Pages : Add Page" menu="content" page_style="fullContent"}
{/if}
{assign var=subMenu value="Content Pages"}

<form method="post" action="/admin/content/add/s/">
	<section id="content" class="content">
		<header>
			<h2>Content Pages</h2>

			{foreach from=$aAdminMenu item=aMenu key=k}
				{if $k == "content"}
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
			<h3>Add New Page</h3>
				<label>* Page Title:</label><br />
				<input type="text" name="title" maxlength="100" value="{$aPage.title|clean_html}" class="required"><br />

				<label>Content:</label><br />
				{html_editor content=$aPage.content name="content"}
				
				<input type="submit" value="Add Page">
				<a class="cancel" href="/admin/content" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->
	
	{if $sSuperAdmin == true}
		<section id="sidebar" class="sidebar">
			<header>
				<h2>Page Options</h2>
			</header>

			<section>
				<label>Tag:</label><br />
				<input type="text" name="tag" maxlength="100" value="{$aPage.tag|clean_html}"><br />

				<label>Permanent:</label>
				<input type="checkbox" name="perminate" value="1"{if $aPage.perminate == 1} checked="checked"{/if}><br />

				<label>Module:</label>
				<input type="checkbox" name="module" value="1"{if $aPage.module == 1} checked="checked"{/if}><br />

				<label>Template:</label>
				<select name="template">
					<option value="">Default</option>
					{foreach from=$aTemplates item=template}
						<option value="{$template}"{if $aPage.template == $template} selected="selected"{/if}>{$template}</option>
					{/foreach}
				</select><br />
			</section>
		</section>
	{/if}
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		errorHTML = "";
	
		if($(this).find('input[name=title]').val() == '')
		{
			errorHTML = errorHTML+"<li>Please fill in page title</li>";
			error++;
		}
	
		if(error != 0) {
			$(".ui-state-error").remove();
			$("#wrapper-inner").prepend('<div class="ui-state-error ui-corner-all notice"><span class="icon ui-icon ui-icon-alert"></span><ul>'+errorHTML+'</ul></div>');
			return false;
		} else {
			return true;
		}
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}