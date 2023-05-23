$("body").on('click', '.addDebt', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$("#preloader").show();	

	$.get(root+"shops_debts/add/"+id, function(data) {
		$("#bodyDbt").html(data);
		$("#debts").modal("show");
		$("#preloader").hide();
	});

});

$("body").on('submit', '#ShopsDebtAddForm', function(event) {
	event.preventDefault();
	
	var id_btn = "#debt_id_"+$("#ShopsDebtShopId").val();
	$("#preloader").show();

	$.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
		$("#preloader").hide();
		if(data == "0"){
			showMessage("Todos los campos son requeridos",true);
		}else{
			$(id_btn).trigger('click');
			
		}
	});

});