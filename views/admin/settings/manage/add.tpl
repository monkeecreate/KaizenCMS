{$menu = "settings"}{$subMenu = "Manage Settings"}
{include file="inc_header.tpl" sPageTitle="Manage Settings &raquo; Create Setting"}
	
	<h1>Manage Settings &raquo; Create Setting</h1>
	{include file="inc_alerts.tpl"}
	
	<form id="add-form" class="form-horizontal" method="post" action="/admin/settings/manage/add/s/">
		<div class="row-fluid">
			<div class="span8">	
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Setting Info</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-group">Group</label>
								<div class="controls">
									<select name="group" id="form-group">
										{foreach from=$aSettingGroups item=aGroup}
											<option value="{$aGroup.id}"{if $aSetting.group == $aGroup.id} selected="selected"{/if}>{$aGroup.name|clean_html}</option>
										{/foreach}
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-title">Name</label>
								<div class="controls">
									<input type="text" name="title" id="form-title" value="{$aSetting.title|clean_html}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-tag">Tag</label>
								<div class="controls">
									<input type="text" name="tag" id="form-tag" value="{$aSetting.tag|clean_html}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-type">Field Type</label>
								<div class="controls">
									<select name="type" id="form-type">
										<option value="text"{if $aSetting.type == "text"} selected="selected"{/if}>Text Field</option>
										<option value="textarea"{if $aSetting.type == "textarea"} selected="selected"{/if}>Textarea</option>
										<option value="bool"{if $aSetting.type == "bool"} selected="selected"{/if}>Checkbox</option>
										<option value="editor"{if $aSetting.type == "editor"} selected="selected"{/if}>WYSIWYG Editor</option>
										<option value="file"{if $aSetting.type == "file"} selected="selected"{/if}>File Upload</option>
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-value">Default Value</label>
								<div class="controls">
									<input type="text" name="value" id="form-value" value="{$aSetting.value|clean_html}" class="span12">
									<p class="help-block">This will be the starting value for the setting. If type is a checkbox then use 0 for unchecked and 1 for checked.</p>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-text">Help Text</label>
								<div class="controls">
									<input type="text" name="text" id="form-text" value="{$aSetting.text|clean_html}" class="span12">
									<p class="help-block">This text will show up just below the setting to provide the user with a little extra information or requirements for the field. It looks just like this.</p>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-validation">Validation</label>
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="validation[]" value="required"{if in_array("required", $aSetting.validation)} checked="checked"{/if}> Make this field required.</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="email"{if in_array("email", $aSetting.validation)} checked="checked"{/if}> Valid email address. Ex: hello@johndoe.com</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="url"{if in_array("url", $aSetting.validation)} checked="checked"{/if}> Valid website URL. Ex: http://google.com</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="number"{if in_array("number", $aSetting.validation)} checked="checked"{/if}> Numeric value only. Ex: -143.22 or .77 or 234,230</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="onlyNumberSp"{if in_array("onlyNumberSp", $aSetting.validation)} checked="checked"{/if}> Only numbers and spaces.</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="onlyLetterSp"{if in_array("onlyLetterSp", $aSetting.validation)} checked="checked"{/if}> Only letters and spaces.</label>
									<label class="checkbox"><input type="checkbox" name="validation[]" value="onlyLetterNumber"{if in_array("onlyLetterNumber", $aSetting.validation)} checked="checked"{/if}> Only letters and numbers, no spaces.</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<input type="submit" value="Create Setting" class="btn btn-primary">
				<a href="/admin/settings/manage/" title="Cancel" class="btn">Cancel</a>
			</div>
			
			<div class="span4 aside">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Setting Options</span>
					</div>
					<div id="pageoptions" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-sortorder">Sort Order</label>
								<div class="controls">
									<input type="text" name="sortorder" id="form-sortorder" value="{$aSetting.sortorder}" class="span12">
								</div>
							</div>
							
							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="active" id="form-active" value="1"{if $aSetting.active == 1} checked="checked"{/if}>Active</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });
});
</script>
{/footer}
{include file="inc_footer.tpl"}