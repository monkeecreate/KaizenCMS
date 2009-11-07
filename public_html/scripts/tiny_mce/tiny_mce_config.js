tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	file_browser_callback : "tinyBrowser",
	editor_selector : "wysiwyg",
	plugins : "paste,preview,advimage,tabfocus,table,fullscreen,media",
	theme_advanced_buttons1 : "fontselect,fontsizeselect,bold,italic,underline,separator,justifyleft,justifycenter,justifyright, justifyfull,separator,link,unlink",
	theme_advanced_buttons1_add : "separator,forecolor,bullist,numlist,|,outdent,indent,blockquote",
	theme_advanced_buttons2 : "tablecontrols,separator,hr,removeformat,visualaid,separator,image,media,|,pastetext,pasteword,separator,code,preview,fullscreen",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	content_css: "/css/tinymce.css",
	relative_urls : false, 
	inline_styles : true,
	force_p_newlines: true,
	force_br_newlines : true,
	tab_focus : ':prev,:next'
});

tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "paste,table,preview,fullscreen",
		editor_selector : "rte",
		theme_advanced_buttons1 : "pastetext,pasteword,separator,bold,italic,bullist,separator,tablecontrols,code,preview,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : false,
});

tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "paste",
		editor_selector : "notepad",
		theme_advanced_buttons1 : "pastetext,pasteword",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : false,
});