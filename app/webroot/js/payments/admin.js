$(".viewPaymentDetail").click(function(event) {
	event.preventDefault();
	var url = $(this).attr("href");

	$.post(url, {}, function(data, textStatus, xhr) {
		$("#pagoBody").html(data);
		$("#pagoModal").modal("show");
	});

});


$("body").on("click",".selectAll",function(event) {
	if ($(this).prop("checked")) {
		$(".selectPaymentsCobre").each(function(index, el) {
			$(this).prop("checked",true);
		});
	}else{
		$(".selectPaymentsCobre").each(function(index, el) {
			$(this).prop("checked",false);
		});
	}
	validateChecksContinue();
});


function validateChecksContinue(){
    var total = 0;
    var totalChecks = 0;
    $("body").find('.selectPaymentsCobre').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
        }
        totalChecks++;
    });
    if(total > 0){
        $("body").find("#marcaFull").show();
    }else{
        $("body").find("#marcaFull").hide();
    }

    if(total != totalChecks){
        $("body").find('.selectAll').prop("checked",false);
    }else{
        $("body").find('.selectAll').prop("checked",true);
    }
}

$("body").on('click', '.selectPaymentsCobre', function(event) {
    validateChecksContinue();
});

validateChecksContinue();

$("body").on('click', '#marcaFull', function(event) {

	event.preventDefault();
	var url = $(this).attr("href");

	var ids = [];

	$("body").find('.selectPaymentsCobre').each(function(index, el) {
        if($(this).is(":checked")){
            ids.push($(this).data("id"));

        }
    });


	console.log(root+"payments/payment_actual");
    $("#preloader").show();



	console.log(JSON.stringify(ids));
	/*$.post('https://localhost'+url, {ids}, function(data, textStatus, xhr) {

          console.log(data);
	});*/


	$.post(root+"payments/payment_actual", {ids}, function(data, textStatus, xhr) {
		location.reload();
	});









});


$("#btnSearch").click(function(event) {
    event.preventDefault();
    $("#ccCustomer").val("");
    $("#dataCustomerDataPayment").html("");
    $("#searchCustomer").modal("show");
});


$("#btnCustomerSearch").click(function(event) {
    event.preventDefault();

    var cc = $("#ccCustomer").val();
    if($.trim(cc) == ""){
        showMessage("La cédula es requerida",true);
    }else{
        $.post(root+"credits_requests/search_customer", {cc}, function(data, textStatus, xhr) {
            $("#dataCustomerDataPayment").html(data);
        });
    }
});

$("#limpia").click(function(event) {
    $("#PaymentCcCustomer").val("");
    $("#busca").click();
});


$("body").on('click', '.returnPayments', function(event) {
    event.preventDefault();
    var url = $(this).attr("href");

    Swal.fire({
        title: "Confirmar",
        text: "¿Está seguro retornar el/los pagos relacionados a este recibo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        $.post(url, {}, function(data, textStatus, xhr) {
            location.reload();
        });
      }
    });

});
