

$("#ShopPlan").change(function(event) {
	calcValue();
});

$("#ShopNumberCommerces").change(function(event) {
	calcValue();
});

function calcValue(){
	var plan  = $("#ShopPlan").val();
	var plan1 = parseInt($("#ShopPlan").data("plan-one"));
	var plan2 = parseInt($("#ShopPlan").data("plan-two"));

	var cost_min1 = parseFloat($("#ShopPlan").data("cost-plan-one-min"));
	var cost_min2 = parseFloat($("#ShopPlan").data("cost-plan-two-min"));

	var cost_max1 = parseFloat($("#ShopPlan").data("cost-plan-one-max"));
	var cost_max2 = parseFloat($("#ShopPlan").data("cost-plan-two-max"));

	var qty   	  = parseInt($("#ShopNumberCommerces").val());

	if(plan == "1"){
		$("#ShopPaymentTotal").val(qty*plan1);
		$("#ShopCostMin").val(cost_min1);
		$("#ShopCostMax").val(cost_max1);
	}else{
		$("#ShopPaymentTotal").val(qty*plan2);		
		$("#ShopCostMin").val(cost_min2);
		$("#ShopCostMax").val(cost_max2);
	}
}

function initializeDepartment() {
	var input = document.getElementById('ShopDepartment');	
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initializeDepartment);

function initializeCity() {
	var input = document.getElementById('ShopCity');	
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initializeCity);