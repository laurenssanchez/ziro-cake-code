$("#exportar").click(function(event) {

	event.preventDefault();
	var URL = $(this).attr("href");
	var urlQuery = URLToArray(URL,URL);

	if($(".deleteWar").length){
		if($("#CreditCommerce").length && $("#CreditCommerce").val() != ""){
			urlQuery["commerce"] = $("#CreditCommerce").val();
		}
		if($("#input_date_inicio").length && $("#input_date_inicio").val() != ""){
			urlQuery["ini"] = $("#input_date_inicio").val();
		}
		if($("#input_date_fin").length && $("#input_date_fin").val() != ""){
			urlQuery["end"] = $("#input_date_fin").val();
		}	
		if($("#CreditTypeDate").length && $("#CreditTypeDate").val() != ""){
			urlQuery["type_date"] = $("#CreditTypeDate").val();
		}	
	}

	if($("#CreditTab").length && $("#CreditTab").val() != ""){
		urlQuery["tab"] = $("#CreditTab").val();
	}
	if($("#CreditState").length && $("#CreditState").val() != ""){
		urlQuery["state"] = $("#CreditState").val();
	}
	if($("#filtroCosto").length){
		var slider = $("#filtroCosto").data("ionRangeSlider");
		var from = slider.result.from;
		var to = slider.result.to;
		urlQuery["range"] = from+";"+to;
	}


	var d=new Date().getTime();

	var urlEnvio = URL+"/"+d+"/"+"?"+$.param(urlQuery);
	console.log(urlEnvio)
	preloader(true)
	$.post(urlEnvio, {param1: 'value1'}, function(data, textStatus, xhr) {
		if (data != "" && data != null) {
			window.open(data);
			preloader(false)
		}
	});

});

function URLToArray(url,actual_urlData = null) {
    var request = {};

    var actual_urlVar = actual_urlData === null ? actual_url : actual_urlData;
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
        if(!pairs[i])
            continue;
        var pair = pairs[i].split('=');
        if(actual_urlVar != decodeURIComponent(pair[0])+"?" && actual_urlVar != decodeURIComponent(pair[0]) && typeof pair[1] != "undefined"){
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);         
        }
    }
    return request;
}