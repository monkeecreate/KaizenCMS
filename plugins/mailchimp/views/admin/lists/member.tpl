{include file="inc_header.tpl" page_title="Newsletter - List Members" menu="mailchimp" page_style="halfContent"}
{assign var=subMenu value="Lists"}

<form method="post" action="/admin/mailchimp/lists/{$aListId}/members/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage List Member</h2>
		</header>

		<section class="inner-content">
			{foreach from=$aListFields item=aListField}
				<label>{if $aListField.req == 1}*{/if}{$aListField.name} <span style="font-size: 0.8em;">{if $aListField.field_type == "imageurl"}(URL to Image){/if}{if $aListField.field_type == "url"}(http://){/if}{if $aListField.field_type == "phone"}(###-###-####){/if}</span></label><br />
				{if $aListField.field_type == "text" || $aListField.field_type == "email" || $aListField.field_type == "url" || $aListField.field_type == "phone" || $aListField.field_type == "address" || $aListField.field_type == "number" || $aListField.field_type == "imageurl"}
					<input type="text" name="{$aListField.tag}" value="{if !empty($aMember.merges.{$aListField.tag})}{$aMember.merges.{$aListField.tag}}{else}{$aListField.default}{/if}"><br />
				{elseif $aListField.field_type == "radio"}
					{foreach from=$aListField.choices item=aChoice}
						<input type="radio" name="{$aListField.tag}" value="{$aChoice}"> {$aChoice}<br />
					{/foreach}
				{elseif $aListField.field_type == "dropdown"}
					<select name="{$aListField.tag}">
					{foreach from=$aListField.choices item=aChoice}
						<option value="{$aChoice}">{$aChoice}</option>
					{/foreach}
					</select><br />
				{elseif $aListField.field_type == "date"}
					<input type="input" name="{$aListField.tag}" class="xsmall datepicker" value="{if !empty($aMember.merges.{$aListField.tag})}{$aMember.merges.{$aListField.tag}}{else}{$aListField.default}{/if}" style="width:80px;"><br />
				{/if}
			{/foreach}
			
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/mailchimp/lists/{$aListId}/members/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Member Info</h2>
		</header>
		
		<section>
			<fieldset>
				<legend>Member</legend>
				
				<ul>
					<li><strong>Current Status</strong>: {$aMember.status}</li>
					<li><strong>Subscribed On</strong>: {$aMember.timestamp}</li>
					<li><strong>Region</strong>: {$aMember.geo.region}</li>
					<li><strong>IP Address</strong>: {$aMember.ip_opt}</li>
					<li><strong>Rating</strong>: {if $aMember.member_rating == 0}No Rating{else}<img src="/images/admin/icons/stars-{$aMember.member_rating}.png" alt="{$aMember.member_rating} Stars">{/if}</li>
				</ul>
			</fieldset>
			
			<fieldset>
				<legend>Lists</legend>
				
				<ul>
				{foreach from=$aMember.lists item=aList}
					<li><a href="/admin/mailchimp/lists/{$aList.id}/" title={$aList.name}>{$aList.name}</a></li>
				{/foreach}
				</ul>
			</fieldset>
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){ldelim}	
	$("form").validateForm([
		"required,email,Member email is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}