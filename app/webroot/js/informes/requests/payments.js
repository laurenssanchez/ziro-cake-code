NUMS = [];
$(".cuotesData").change(function(event) {
	var number = parseInt($(this).data("num"))+1;
	var clase  = ".numQt_"+number;

	if($(this).prop("checked")){
		$(clase).removeAttr('disabled');
	}else{
		$(clase).prop('checked',false);
		$(clase).trigger("change");
		$(clase).attr('disabled','disabled');
	}
	calculateNumAndCheck();
});

function calculateNumAndCheck(){
	var totalCuotes = $(".cuotesData").length;
	var totalSelect = $(".cuotesData:checked").length

	$(".numberCuote").html(totalSelect);

	if(totalCuotes == totalSelect){
		$(".selectAll").prop("checked",true);
	}else{
		$(".selectAll").prop("checked",false);
	}
	$("#preloader").show();
	setTimeout(function() {
		getPaymentValue();
	}, 3000);
}

$(".selectAll").change(function(event) {
	if($(this).prop("checked")){
		$(".cuotesData").each(function(index, el) {
			$(this).prop("checked",true);
			$(this).trigger("change");
		});
	}else{
		$(".cuotesData").each(function(index, el) {
			$(this).prop("checked",false);
			$(this).trigger("change");
		});
	}
});

function getPaymentValue(){
	var idQuotes = [];
	NUMS = [];
	var creditId;
	$(".cuotesData").each(function(index, el) {
		if($(this).prop("checked")){
			idQuotes.push($(this).data("cuote"));
			NUMS.push($(this).data("num"));
			creditId = $(this).data("credit");
		}
	});
	$("#preloader").show();
	if(idQuotes.length > 0){
		$.post(root+"credits/quotes_value", {quotes: idQuotes,credit_id:creditId}, function(data, textStatus, xhr) {
			$(".pagoTotalData").html(
				new Intl.NumberFormat('es-CO', {
					style: 'currency',
					currency: 'COP',
					minimumFractionDigits: 2
				  }).format(Math.round(data))
			);
			var inputNombre = document.getElementById("pagototal");
			inputNombre.value = data;



			$("#paymentFinal").show();
			setTimeout(function() {
				$("#preloader").hide();
			}, 1500);
		});
	}else{
		$(".pagoTotalData").html("0");
		$("#paymentFinal").hide();
		setTimeout(function() {
			$("#preloader").hide();
		}, 1500);
	}

	console.log(NUMS)
}

var numberVar = 0;
getPaymentValue();

$(".cuotesData").each(function(index, el) {
	if($(this).prop("checked")){
		$(this).trigger("change");
		return false;
	}
});

$("#paymentFinal").click(function(event) {
	event.preventDefault();

	var typePayment = false;

	var totalPago = $("#pagototal").val();

	//console.log(totalPago)

    $("input[type=radio]").each(function() {
        if ($(this).prop("checked")) {
            typePayment = $(this).attr("value");
        }
    });



    if(typePayment == "1"){
    	if(NUMS.length > 0){
    		$("#preloader").show();

    		var idQuotes = [];
			var creditId;
			$(".cuotesData").each(function(index, el) {
				if($(this).prop("checked")){
					idQuotes.push($(this).data("cuote"));
					NUMS.push($(this).data("num"));
					creditId = $(this).data("credit");
				}
			});
			$("#preloader").show();
			$.post(root+"credits/payment_quotes",{type: typePayment,value: 0, ids:idQuotes, credit_id: creditId+','+totalPago  }, function(response, textStatus, xhr) {
				reloadData();
				window.open(root+"credits/payment_view/"+creditId+','+totalPago, '_self');
			});

    	}else{
    		showMessage("Debe seleccionar una o más cuotas para pagar.",true)
    	}
    }else{
    	if($("#otherValueNum").val() == "" || parseInt($("#otherValueNum").val()) < 10){
    		showMessage("Debe ingresar el valor a pagar y debe ser mayor o igual a 10",true)

    	}else{

    		var totalPago = parseInt($("#otherValueNum").val());
			$("#preloader").show();
			var idQuotes = [];
			var creditId;
			$(".cuotesData").each(function(index, el) {
				idQuotes.push($(this).data("cuote"));
				creditId = $(this).data("credit");
			});



			$.post(root+"credits/quotes_value", {quotes: idQuotes,credit_id:creditId}, function(data, textStatus, xhr) {
				$("#preloader").hide();

				if(parseInt(data) < totalPago){
					var number = new Intl.NumberFormat().format(data)

					showMessage("El valor máximo a pagar es: $"+number,true);
				}else{
					$("#preloader").show();
					//$.post(root+"credits/payment_quotes",{type: typePayment,value: 0, ids:idQuotes, credit_id: creditId+','+totalPago  }, function(response, textStatus, xhr) {

					$.post(root+"credits/payment_quotes", {type: typePayment,value:totalPago, ids:[],credit_id: creditId+','+totalPago }, function(response, textStatus, xhr) {
						reloadData();
						//window.open(root+"credits/payment_view/"+creditId+','+totalPago, '_self');
						window.open(root+"credits/payment_view/"+creditId+','+totalPago, '_self');

					    //window.open(root+"credits/payment_view/"+creditId+','+totalPago, '_self');
					});
				}
			});

    	}
    }

});

function reloadData(){
	setTimeout(function() {
		location.reload();
	}, 10000);
}
