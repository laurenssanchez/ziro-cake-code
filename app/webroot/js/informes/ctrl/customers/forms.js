$("input").each(function(index, el) {
	$(this).attr("autocomplete","off")
});

function initialize() {

	if($("#CustomerIdentificationPlace").length){
		var input = document.getElementById('CustomerIdentificationPlace');
	}else if($("#CustomerCityBirth").length){
		var input = document.getElementById('CustomerCityBirth');
	}
	
	var autocomplete = new google.maps.places.Autocomplete(input);
}
if (!$("#CustomerCrediventasForm").length) {
	google.maps.event.addDomListener(window, 'load', initialize);
}

$("body").on('submit', '#CustomerRegisterStepTwoForm,#CustomerRegisterStepThreeForm', function(event) {
	// preloader();
});