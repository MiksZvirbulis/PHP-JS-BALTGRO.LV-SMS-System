jQuery.noConflict();

(function ($) {
	$.fn.stickyTabs = function() {
		context = this
		var showTabFromHash = function() {
			var hash = window.location.hash;
			var selector = hash ? 'a[href="' + hash + '"]' : "li:first-child a";
			jQuery(selector, context).tab("show");
		}
		showTabFromHash(context)
		window.addEventListener("hashchange", showTabFromHash, false);
		jQuery("a", context).on("click", function(e) {
			history.pushState(null, null, this.href);
		});
		return this;
	};
});

var delayTimer;

function loadPlugin(plugin){
	jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 0.2);
	jQuery("#baltsms-loader").show();
	clearTimeout(delayTimer);
	delayTimer = setTimeout(function() {
		var dataString = { plugin: plugin };
		jQuery.ajax({
			type: "POST",
			url: baltsms_url + "/system/plugin.php",
			data: dataString,
			cache: false,
			async: false
		}).done(function(returned){
			jQuery("#baltsms-loader").fadeOut("fast");
			jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 1);
			jQuery("div#" + plugin).html(returned);

			jQuery("form").on("submit", function(e){
				e.preventDefault();
				jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 0.2);
				jQuery("#baltsms-loader").show();
				clearTimeout(delayTimer);
				delayTimer = setTimeout(function() {
					var dataString = jQuery("form").serialize();
					jQuery.ajax({
						type: "POST",
						url: baltsms_url + "/plugins/" + jQuery("form").attr("id") + ".php",
						data: dataString,
						cache: false,
						async: false
					}).done(function(returned){
						jQuery("div#alerts").html(returned);
						jQuery("#baltsms-loader").fadeOut("fast");
						jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 1);
					});
				}, 1000);
			});
		});
	}, 1000);
}

function setInstructions(price, template){
	jQuery("div#instructions").html(template);
}

function returnPrice(price_code){
	price_code = price_code * 0.01;
	return (price_code / 0.702804).toFixed(2);
}

function changePrice(value){
	jQuery("div#instructions span#price").text(returnPrice(value));
	jQuery("div#instructions span#code").text(value);
}

function setLanguage(language){
	if(jQuery.cookie("baltsms_language") == language){
		return false
	}else{
		jQuery.cookie("baltsms_language", language, { expires: 31, path: "/" });
		location.reload();
	}
}