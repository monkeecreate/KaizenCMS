// Avoid `console` errors in browsers that lack a console.
(function() {
	var method;
	var noop = function noop() {};
	var methods = [
		'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
		'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
		'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
		'timeStamp', 'trace', 'warn'
	];
	var length = methods.length;
	var console = (window.console = window.console || {});

	while (length--) {
		method = methods[length];

		// Only stub undefined methods.
		if (!console[method]) {
			console[method] = noop;
		}
	}
}());

/* https://github.com/jamesallardice/Placeholders.js */
var Placeholders=function(){function i(a){var b=a.getElementsByTagName("input"),c=a.getElementsByTagName("textarea"),d=b.length,e=d+c.length,f,g,h;for(h=0;h<e;h++){f=h<d?b[h]:c[h-d];g=f.getAttribute("placeholder");if(f.value===g){f.value=""}}}function h(a){if(a.value===""){a.className=a.className+" placeholderspolyfill";a.value=a.getAttribute("placeholder")}}function g(a){if(a.value===a.getAttribute("placeholder")){a.className=a.className.replace(/\bplaceholderspolyfill\b/,"");a.value=""}}function f(a){if(a.addEventListener){a.addEventListener("focus",function(){g(a)},false);a.addEventListener("blur",function(){h(a)},false)}else if(a.attachEvent){a.attachEvent("onfocus",function(){g(a)});a.attachEvent("onblur",function(){h(a)})}}function e(){var b=document.getElementsByTagName("input"),c=document.getElementsByTagName("textarea"),d=b.length,e=d+c.length,g,h,i,j;for(g=0;g<e;g++){h=g<d?b[g]:c[g-d];j=h.getAttribute("placeholder");if(a.indexOf(h.type)===-1){if(j){i=h.getAttribute("data-currentplaceholder");if(j!==i){if(h.value===i||h.value===j||!h.value){h.value=j;h.className=h.className+" placeholderspolyfill"}if(!i){f(h)}h.setAttribute("data-currentplaceholder",j)}}}}}function d(){var b=document.getElementsByTagName("input"),c=document.getElementsByTagName("textarea"),d=b.length,e=d+c.length,g,h,j,k;for(g=0;g<e;g++){h=g<d?b[g]:c[g-d];k=h.getAttribute("placeholder");if(a.indexOf(h.type)===-1){if(k){h.setAttribute("data-currentplaceholder",k);if(h.value===""||h.value===k){h.className=h.className+" placeholderspolyfill";h.value=k}if(h.form){j=h.form;if(!j.getAttribute("data-placeholdersubmit")){if(j.addEventListener){j.addEventListener("submit",function(){i(j)},false)}else if(j.attachEvent){j.attachEvent("onsubmit",function(){i(j)})}j.setAttribute("data-placeholdersubmit","true")}}f(h)}}}}function c(a){var c=document.createElement("input"),f,g,h,i;if(typeof c.placeholder==="undefined"){f=document.createElement("style");f.type="text/css";g=document.createTextNode(".placeholderspolyfill { color:#999 !important; }");if(f.styleSheet){f.styleSheet.cssText=g.nodeValue}else{f.appendChild(g)}document.getElementsByTagName("head")[0].appendChild(f);if(!Array.prototype.indexOf){Array.prototype.indexOf=function(a,b){for(h=b||0,i=this.length;h<i;h++){if(this[h]===a){return h}}return-1}}d();if(a){b=setInterval(e,100)}}return false}var a=["hidden","datetime","date","month","week","time","datetime-local","range","color","checkbox","radio","file","submit","image","reset","button"],b;return{init:c,refresh:e}}()

/* http://formalize.me/ */
var FORMALIZE=function(e,t,n,r){function i(e){var t=n.createElement("b");return t.innerHTML="<!--[if IE "+e+"]><br><![endif]-->",!!t.getElementsByTagName("br").length}var s="placeholder"in n.createElement("input"),o="autofocus"in n.createElement("input"),u=i(6),a=i(7);return{go:function(){var e,t=this.init;for(e in t)t.hasOwnProperty(e)&&t[e]()},init:{disable_link_button:function(){e(n.documentElement).on("click","a.button_disabled",function(){return!1})},full_input_size:function(){if(!a||!e("textarea, input.input_full").length)return;e("textarea, input.input_full").wrap('<span class="input_full_wrap"></span>')},ie6_skin_inputs:function(){if(!u||!e("input, select, textarea").length)return;var t=/button|submit|reset/,n=/date|datetime|datetime-local|email|month|number|password|range|search|tel|text|time|url|week/;e("input").each(function(){var r=e(this);this.getAttribute("type").match(t)?(r.addClass("ie6_button"),this.disabled&&r.addClass("ie6_button_disabled")):this.getAttribute("type").match(n)&&(r.addClass("ie6_input"),this.disabled&&r.addClass("ie6_input_disabled"))}),e("textarea, select").each(function(){this.disabled&&e(this).addClass("ie6_input_disabled")})},autofocus:function(){if(o||!e(":input[autofocus]").length)return;var t=e("[autofocus]")[0];t.disabled||t.focus()},placeholder:function(){if(s||!e(":input[placeholder]").length)return;FORMALIZE.misc.add_placeholder(),e(":input[placeholder]").each(function(){if(this.type==="password")return;var t=e(this),n=t.attr("placeholder");t.focus(function(){t.val()===n&&t.val("").removeClass("placeholder_text")}).blur(function(){FORMALIZE.misc.add_placeholder()}),t.closest("form").submit(function(){t.val()===n&&t.val("").removeClass("placeholder_text")}).on("reset",function(){setTimeout(FORMALIZE.misc.add_placeholder,50)})})}},misc:{add_placeholder:function(){if(s||!e(":input[placeholder]").length)return;e(":input[placeholder]").each(function(){if(this.type==="password")return;var t=e(this),n=t.attr("placeholder");(!t.val()||t.val()===n)&&t.val(n).addClass("placeholder_text")})}}}}(jQuery,this,this.document);jQuery(document).ready(function(){FORMALIZE.go()})