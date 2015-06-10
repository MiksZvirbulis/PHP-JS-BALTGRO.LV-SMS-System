jQuery.noConflict();

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
			async: true
		}).done(function(returned){
			if(returned.indexOf("[plugin-not-found]") >= 0 || returned.indexOf("[plugin not defined]") >= 0){
				jQuery("li.dropdown").removeClass("active").find("li").removeClass("active");
				jQuery("#baltsms-loader").fadeOut("fast");
				jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 1);
				jQuery("div.tab-pane").removeClass("active");
				jQuery("div#error").html(returned).addClass("active");
			}else{
				jQuery("#baltsms-loader").fadeOut("fast");
				jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 1);
				jQuery("div.tab-pane").html("");
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
							async: true
						}).done(function(returned){
							jQuery("div#alerts").html(returned);
							jQuery("#baltsms-loader").fadeOut("fast");
							jQuery("div#baltsms-page div#baltsms-content").fadeTo("fast", 1);
						});
					}, 1000);
				});
			}
		});
}, 1000);
}

function returnPrice(price_code){
	price_code = price_code * 0.01;
	return price_code.toFixed(2);
}

function changePrice(element){
	length = jQuery(element).find(":selected").data("length")
	if(jQuery(element).find(":selected").attr("data-price")){
		jQuery("div#instructions span#price").text(returnPrice(jQuery(element).find(":selected").data("price")));
		jQuery("div#instructions span#code").text(jQuery(element).find(":selected").data("price"));
	}else{
		jQuery("div#instructions span#price").text(returnPrice(element.value));
		jQuery("div#instructions span#code").text(element.value);
	}
	jQuery("div#instructions span#length").text(length);
	if(jQuery("div#instructions").css("display") == "none"){
		jQuery("div#instructions").fadeIn("slow");
	}
}

function setLanguage(language){
	if(jQuery.cookie("baltsms_language") == language){
		return false
	}else{
		jQuery.cookie("baltsms_language", language, { expires: 31, path: "/" });
		location.reload();
	}
}

function listGroups(server){
	jQuery("select.groups").hide().prop("disabled", true);
	jQuery("select#groups").replaceWith(jQuery("select#" + server + "-groups"));
	jQuery("select#" + server + "-groups").show().prop("disabled", false);
	jQuery("select#" + server + "-groups").val(jQuery("select#" + server + "-groups option:first").val());
	jQuery("select#prices").show();
	jQuery("select.prices").hide().prop("disabled", true);
	jQuery("div#instructions").fadeOut("slow");
}

function listPrices(group, server){
	jQuery("select.prices").hide().prop("disabled", true);
	jQuery("select#prices").hide().insertAfter(jQuery("select#" + group + "-" + server + "-prices"));
	jQuery("select#" + group + "-" + server + "-prices").show().prop("disabled", false);
	jQuery("select#" + group + "-" + server + "-prices").val(jQuery("select#" + group + "-" + server + "-prices option:first").val());
	jQuery("div#instructions").fadeOut("slow");
}