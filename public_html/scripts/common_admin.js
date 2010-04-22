$(document).ready(function() {
	/**
	 * Load GET into variable
	 *
	 * Loads get variables to easily access
	 */
	var qsParm = new Array();
	var query = window.location.search.substring(1);
	var parms = query.split('&');
	for (var i=0; i<parms.length; i++) {
		var pos = parms[i].indexOf('=');
		if (pos > 0) {
			var key = parms[i].substring(0,pos);
			var val = parms[i].substring(pos+1);
			qsParm[key] = val;
		}
	}
	
	/**
	 * Tool Tip
	 *
	 * Attach tooltip to any anchor with a
	 * title, or has class of .helpTip.
	 * Uses title attritube to fill tip.
	 */
	$('a[title],.helpTip').each(function(){
		title = $(this).attr("title");
		$(this).attr("title", "");
		
		$(this).qtip({
			style: {
				width: {
					min: 120,
					max: 800
				},
	   			background: '#f4f4f4',
			   	color: '#333',
			   	textAlign: 'center',
			   	border: {
			    	width: 2,
			      	radius: 3,
			      	color: '#c1c1c1'
			   	},
			   	tip: 'bottomLeft',
			   	name: 'light'
			},
	   		content: title,
			position: {
				corner: { target: 'bottomMiddle', tooltip: 'topMiddle' },
				adjust: { screen: true }
			},
	   		show: 'mouseover',
	   		hide: 'mouseout'
		});
	});
	
	/**
	 * Add Category Dialog
	 *
	 * Change add category form into dialog.
	 * Unify ok and submit events.
	 * Auto open dialog of GET variable
	 * 'addcategory' = 1
	 */
	function addCategory(){
		$.post(
			$("#add-category form").attr("action"),
			$("#add-category form").serialize(),
			function(data){
				window.location.replace(data);
			}
		);
	}
	
	var $addCategoryDialog = $('#add-category')
		.dialog({
			autoOpen: false,
			bgiframe: true,
			modal: true,
			buttons: {
				'Create Category': function() {				
					if($(this).find('input[name=name]').val() == '') {
						alert("Please fill in category name.");
						return false;
					} else {
						addCategory();
					}
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			}
		});
	$('#add-category form').submit(function(){
		addCategory();
		return false;
	});
	$('#add-category-btn').click(function() {
		$addCategoryDialog.dialog('open');
	});
	if(qsParm["addcategory"] == 1) {
		$addCategoryDialog.dialog('open');
	}
	
	/**
	 * Edit Category Dialog
	 *
	 * Change all edit category forms into dialog.
	 * Unify ok and submit events.
	 */
	function editCategory(item){
		$.post(
			$(item).find('form').attr("action"),
			$(item).find('form').serialize(),
			function(data){
				window.location.replace(data);
			}
		);
	}
	var editCategoryDialog = new Array();
	$("a[id^='dialog_edit_']").each(function(){
		id = $(this).attr('id');
		
		editCategoryDialog[id] = $('#'+id+'_form')
			.dialog({
				autoOpen: false,
				bgiframe: true,
				modal: true,
				buttons: {
					'Save Changes': function() {				
						if($(this).find('input[name=name]').val() == '') {
							alert("Please fill in category name.");
							return false;
						} else {
							editCategory(this);
						}
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
		$('#'+id+'_form').each(function(){
			var item = this;
			
			$(this).find('form').submit(function(){
				editCategory(item);
				return false;
			});
		});
		$(this).click(function(){
			id = $(this).attr('id');
			editCategoryDialog[id].dialog('open');
			
			return false;
		});
	});
	
	/**
	 * Initiate Accordion
	 *
	 * Create accordion on any element with
	 * class of .accordion
	 */
	$(".accordion").accordion({
		collapsible: true,
		autoHeight: false,
		header: ".header"
	});
	
	/**
	 * Initiate Date Picker
	 *
	 * Apply datepicker to any element with
	 * class of .datepicker
	 */
	$('.datepicker').datepicker({
	});
	
	/**
	 * Default Styling
	 *
	 * Apply default styles to elements
	 */
	$('form input:submit').addClass('btn ui-button ui-corner-all ui-state-default');
	$('form input:button').addClass('btn ui-button ui-corner-all ui-state-default');
	$('form input:text').addClass('text ui-widget-content ui-corner-all');
	$('form input:password').addClass('text ui-widget-content ui-corner-all');
	$('form input:checkbox').addClass('checkbox ui-widget-content ui-corner-all');
	$('form input:radio').addClass('radio');
	$('form textarea').addClass('textarea ui-widget-content ui-corner-all');
	$('form select').addClass('select ui-widget-content ui-corner-all');
	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.end()
		.find(".portlet-content");
	$(".column").disableSelection();
	$(".checkAll").click(function(){
		$("fieldset#fieldset_categories INPUT[type='checkbox']").attr('checked', true);
	});
	$(".uncheckAll").click(function(){
		$("fieldset#fieldset_categories INPUT[type='checkbox']").attr('checked', false);
	});
	$(".btn").hover(
		function(){
			$(this).removeClass('ui-state-default');
			$(this).addClass('ui-state-hover');
		},
		function(){
			$(this).removeClass('ui-state-hover');
			$(this).addClass('ui-state-default');
		}
	);
});

/**
 * Force 1 checkbox
 *
 * Traverse given element for atleast 1
 * checkbox that is checked.
 */
function check_fieldset(fieldset)
{
	var checked = 0;
	
	checked = $(fieldset).find('input::checkbox[checked]').length;
	
	if(checked > 0)
		return true;
	else
		return false;
}

/**
 * Shorten confirm
 *
 * Shortens script of returning confirm
 * result.
 */
function confirm_(message)
{
	return confirm(message);
}

/**
 * Toggle Elements
 *
 * Toggle two elements in opposite directions
 */
function addItem(showElement, hideElement)
{
	$(hideElement).hide();
	$(showElement).fadeIn("slow");		
}