/* @plugin: https://github.com/jamesallardice/Placeholders.js */
	var Placeholders=function(){function i(a){var b=a.getElementsByTagName("input"),c=a.getElementsByTagName("textarea"),d=b.length,e=d+c.length,f,g,h;for(h=0;h<e;h++){f=h<d?b[h]:c[h-d];g=f.getAttribute("placeholder");if(f.value===g){f.value=""}}}function h(a){if(a.value===""){a.className=a.className+" placeholderspolyfill";a.value=a.getAttribute("placeholder")}}function g(a){if(a.value===a.getAttribute("placeholder")){a.className=a.className.replace(/\bplaceholderspolyfill\b/,"");a.value=""}}function f(a){if(a.addEventListener){a.addEventListener("focus",function(){g(a)},false);a.addEventListener("blur",function(){h(a)},false)}else if(a.attachEvent){a.attachEvent("onfocus",function(){g(a)});a.attachEvent("onblur",function(){h(a)})}}function e(){var b=document.getElementsByTagName("input"),c=document.getElementsByTagName("textarea"),d=b.length,e=d+c.length,g,h,i,j;for(g=0;g<e;g++){h=g<d?b[g]:c[g-d];j=h.getAttribute("placeholder");if(a.indexOf(h.type)===-1){if(j){i=h.getAttribute("data-currentplaceholder");if(j!==i){if(h.value===i||h.value===j||!h.value){h.value=j;h.className=h.className+" placeholderspolyfill"}if(!i){f(h)}h.setAttribute("data-currentplaceholder",j)}}}}}function d(){var b=document.getElementsByTagName("input"),c=document.getElementsByTagName("textarea"),d=b.length,e=d+c.length,g,h,j,k;for(g=0;g<e;g++){h=g<d?b[g]:c[g-d];k=h.getAttribute("placeholder");if(a.indexOf(h.type)===-1){if(k){h.setAttribute("data-currentplaceholder",k);if(h.value===""||h.value===k){h.className=h.className+" placeholderspolyfill";h.value=k}if(h.form){j=h.form;if(!j.getAttribute("data-placeholdersubmit")){if(j.addEventListener){j.addEventListener("submit",function(){i(j)},false)}else if(j.attachEvent){j.attachEvent("onsubmit",function(){i(j)})}j.setAttribute("data-placeholdersubmit","true")}}f(h)}}}}function c(a){var c=document.createElement("input"),f,g,h,i;if(typeof c.placeholder==="undefined"){f=document.createElement("style");f.type="text/css";g=document.createTextNode(".placeholderspolyfill { color:#999 !important; }");if(f.styleSheet){f.styleSheet.cssText=g.nodeValue}else{f.appendChild(g)}document.getElementsByTagName("head")[0].appendChild(f);if(!Array.prototype.indexOf){Array.prototype.indexOf=function(a,b){for(h=b||0,i=this.length;h<i;h++){if(this[h]===a){return h}}return-1}}d();if(a){b=setInterval(e,100)}}return false}var a=["hidden","datetime","date","month","week","time","datetime-local","range","color","checkbox","radio","file","submit","image","reset","button"],b;return{init:c,refresh:e}}()

/* @plugin: https://github.com/cpatik/console.log-wrapper/ */
	// Tell IE9 to use its built-in console
	if (Function.prototype.bind && (typeof console === 'object' || typeof console === 'function') && typeof console.log === 'object') {
	  ['log','info','warn','error','assert','dir','clear','profile','profileEnd']
	    .forEach(function (method) {
	      console[method] = this.call(console[method], console);
	    }, Function.prototype.bind);
	}

	// log() -- The complete, cross-browser (we don't judge!) console.log wrapper for his or her logging pleasure
	if (!window.log) {
	  window.log = function () {
	    var args = arguments,
	        isReallyIE8 = false,
	        isReallyIE8Plus = false,
	        ua, winRegexp, script, i;

	    log.history = log.history || [];  // store logs to an array for reference
	    log.history.push(arguments);

	    // If the detailPrint plugin is loaded, check for IE10- pretending to be an older version,
	    //   otherwise it won't pass the "Browser with a console" condition below. IE8-10 can use
	    //   console.log normally, even though in IE7/8 modes it will claim the console is not defined.
	    // TODO: Does IE11+ still have a primitive console, too? If so, how do I check for IE11+ running in old IE mode?
	    // TODO: Can someone please test this on Windows Vista and Windows 8?
	    if (log.detailPrint && log.needDetailPrint) {
	      ua = navigator.userAgent;
	      winRegexp = /Windows\sNT\s(\d+\.\d+)/;
	      // Check for certain combinations of Windows and IE versions to test for IE running in an older mode
	      if (console && console.log && /MSIE\s(\d+)/.test(ua) && winRegexp.test(ua)) {
	        // Windows 7 or higher cannot possibly run IE7 or older
	        if (parseFloat(winRegexp.exec(ua)[1]) >= 6.1) {
	          isReallyIE8Plus = true;
	        }
	        // Cannot test for IE8+ running in IE7 mode on XP (Win 5.1) or Vista (Win 6.0)...
	      }
	    }

	    // Browser with a console
	    if (isReallyIE8Plus || (typeof console !== 'undefined' && typeof console.log === 'function')) {
	      // Get argument details for browsers with primitive consoles if this optional plugin is included
	      if (log.detailPrint && log.needDetailPrint && log.needDetailPrint()) {
	        console.log('-----------------'); // Separator
	        args = log.detailPrint(args);
	        i = 0;
	        while (i < args.length) {
	          console.log(args[i]);
	          i++;
	        }
	      }
	      // Single argument, which is a string
	      else if ((Array.prototype.slice.call(args)).length === 1 && typeof Array.prototype.slice.call(args)[0] === 'string') {
	        console.log((Array.prototype.slice.call(args)).toString());
	      }
	      else {
	        console.log((Array.prototype.slice.call(args)));
	      }
	    }

	    // IE8
	    else if (!Function.prototype.bind && typeof console !== 'undefined' && typeof console.log === 'object') {
	      alert('is actually ie8');
	      if (log.detailPrint) {
	        Function.prototype.call.call(console.log, console, Array.prototype.slice.call(['-----------------'])); // Separator
	        args = log.detailPrint(args);
	        i = 0;
	        while (i < args.length) {
	          Function.prototype.call.call(console.log, console, Array.prototype.slice.call([args[i]]));
	          i++;
	        }
	      }
	      else {
	        Function.prototype.call.call(console.log, console, Array.prototype.slice.call(args));
	      }
	    }

	    // IE7 and lower, and other old browsers
	    else {
	      // Inject Firebug lite
	      if (!document.getElementById('firebug-lite')) {
	        // Include the script
	        script = document.createElement('script');
	        script.type = 'text/javascript';
	        script.id = 'firebug-lite';
	        // If you run the script locally, change this to /path/to/firebug-lite/build/firebug-lite.js
	        script.src = 'https://getfirebug.com/firebug-lite.js';
	        // If you want to expand the console window by default, uncomment this line
	        //document.getElementsByTagName('HTML')[0].setAttribute('debug','true');
	        document.getElementsByTagName('HEAD')[0].appendChild(script);
	        setTimeout(function () { window.log.apply(window, args); }, 2000);
	      }
	      else {
	        // FBL was included but it hasn't finished loading yet, so try again momentarily
	        setTimeout(function () { window.log.apply(window, args); }, 500);
	      }
	    }
	  };
	}

	window.log = window.log || function () {};

	// Checks whether it's necessary to parse details for this browser
	window.log.needDetailPrint = function () {
	  var ua = window.navigator.userAgent,
	      uaCheck, uaVersion;

	  // Look for iOS <6 (thanks to Jörn Berkefeld)
	  if (/iPad|iPhone|iPod/.test(window.navigator.platform)) {
	    uaCheck = ua.match(/OS\s([0-9]{1})_([0-9]{1})/);
	    uaVersion = uaCheck ? parseInt(uaCheck[1], 10) : 0;
	    if (uaVersion >= 6) {
	      return true;
	    }
	  }
	  // Check for Opera version 11 or lower
	  else if (window.opera) {
	    uaCheck = /Version\/(\d+)\.\d+/;
	    if (uaCheck.test(ua)) {
	      if (parseInt(uaCheck.exec(ua)[1], 10) <= 11) {
	        return true;
	      }
	    }
	  }
	  // Check for Internet Explorer (needed up through version 10)
	  // TODO: When IE11+ are released, check if they still have primitive consoles.
	  //       If so, use /MSIE\s(\d+)\./ and check for versions N or lower to catch new IE running in an old IE mode
	  else if (/MSIE\s\d/.test(ua)) {
	    return true;
	  }
	  return false; // true;
	};

	// List arguments separately for easier deciphering in some browsers
	window.log.detailPrint = function (args) {
	  var getSpecificType, detailedArgs, i, j, thisArg, argType, str, beginStr;

	  // Checks for special JavaScript types that inherit from Object
	  getSpecificType = function(obj) {
	    var reportedType, found, types, n, str, beginStr;

	    // Look for special types that inherit from Object
	    reportedType = Object.prototype.toString.call(obj);
	    found = '';
	    types = 'Array,Date,RegExp,Null'.split(',');
	    n = types.length;
	    while (n--) {
	      if (reportedType === '[object ' + types[n] + ']') {
	        found = types[n].toLowerCase();
	        break;
	      }
	    }
	    if (found.length) {
	      return found;
	    }

	    // DOM element (DOM level 2 and level 1, respectively)
	    if ((typeof HTMLElement === 'object' && obj instanceof HTMLElement) || (typeof obj.nodeName === 'string' && obj.nodeType === 1)) {
	      found = 'element';
	    }
	    // DOM node (DOM level 2 and level 1, respectively)
	    else if ((typeof Node === 'object' && obj instanceof Node) || (typeof obj.nodeType === 'number' && typeof obj.nodeName === 'string')) {
	      found = 'node';
	    }
	    return found.length ? found : typeof obj;
	  };

	  // Loop through each argument and collect details for each one
	  detailedArgs = [];
	  i = 0;
	  while (i < args.length) {
	    thisArg = args[i];
	    // Get argument type
	    argType = typeof thisArg;
	    beginStr = 'Item ' + (i+1) + '/' + args.length + ' ';

	    // Be more specific about objects
	    if (argType === 'object') {
	      argType = getSpecificType(thisArg);

	      // Include array length and contents' types
	      if (argType === 'array') {
	        if (!thisArg.length) {
	          detailedArgs.push(beginStr + '(array, empty) ', thisArg);
	        }
	        else {
	          // Get the types of up to 3 items
	          j = thisArg.length > 3 ? 3 : thisArg.length;
	          str = '';
	          while (j--) {
	            str = getSpecificType(thisArg[j]) + ', ' + str;
	          }
	          if (thisArg.length > 3) {
	            str += '...';
	          }
	          else {
	            str = str.replace(/,\s$/, '');
	          }
	          detailedArgs.push(beginStr + '(array, length=' + thisArg.length + ', [' + str + ']) ', thisArg);
	        }
	      }
	      else if (argType === 'element') {
	        str = thisArg.nodeName.toLowerCase();
	        if (thisArg.id) {
	          str += '#' + thisArg.id;
	        }
	        if (thisArg.className) {
	          str += '.' + thisArg.className.replace(/\s+/g, '.');
	        }
	        detailedArgs.push(beginStr + '(element, ' + str + ') ', thisArg);
	      }
	      else if (argType === 'date') {
	        detailedArgs.push(beginStr + '(date) ', thisArg.toUTCString());
	      }
	      else {
	        detailedArgs.push(beginStr + '(' + argType + ')', thisArg);
	        if (argType === 'object') {
	          // Print properties for plain objects (first level only)
	          if (typeof thisArg.hasOwnProperty === 'function') {
	            for (j in thisArg) {
	              if (thisArg.hasOwnProperty(j)) {
	                detailedArgs.push('  --> "' + j + '" = (' + getSpecificType(thisArg[j]) + ') ', thisArg[j]);
	              }
	            }
	          }
	        }
	      }
	    }
	    // Print non-objects as-is
	    else {
	      detailedArgs.push(beginStr + '(' + typeof thisArg + ') ', thisArg);
	    }
	    i++;
	  }
	  return detailedArgs;
	};