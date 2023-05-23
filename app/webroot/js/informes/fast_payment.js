$("body").on("submit", "#fastFormData", function (event) {
	event.preventDefault();
	preloader(true);
	$("#resultPayments").html("");
	$("#contentInitial").hide();
	$.ajax({
		url: varsJs.APP_URL + "payment_fast_search",
		type: "POST",
		data: $(this).serialize(),
	})
		.done(function (response) {
			preloader(false);
			$("#identification").val("");
			$("#resultPayments").html(response);
			$("#fastFormDataSelectCredit").parsley();
		})
		.fail(function () {
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});

$("body")
	.on("change", "#otherValue", function (event) {
		$(".radioCheck").prop("checked", false);
		$("#checkDataSelect").prop("checked", true);
	})
	.on("keypress", "#otherValue", function (event) {
		$(".radioCheck").prop("checked", false);
		$("#checkDataSelect").prop("checked", true);
	});

$("body").on("submit", "#fastFormDataSelectCredit", function (event) {
	event.preventDefault();
	$("#resultPayments").html("");
	preloader(true);
	$.ajax({
		url: varsJs.APP_URL + "payment_fast_select",
		type: "POST",
		data: $(this).serialize(),
	})
		.done(function (response) {
			preloader(false);
			$("#resultPayments").html(response);
		})
		.fail(function () {
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});

$("body").on("click", ".paymentBtnFast", function (event) {
	event.preventDefault();
	var typePayment = $(
		"input[name=typePayment]:checked",
		"#creditPaymentForm"
	).val();

	if (typeof typePayment == "undefined") {
		showMessage("Seleccione un tipo de pago");
		return false;
	}

	if (typePayment == "3") {
		var pagoValor = $("#otherValue").val();
		if (parseInt(pagoValor) < 5000) {
			showMessage("El valor mínimo es 5.000");
			return false;
		}
	} else if (typePayment == "2") {
		var pagoValor = $("#PaymentMin").data("value");
	} else {
		var pagoValor = $("#paymentTotal").data("value");
	}

	var credit = $(this).data("id");

	preloader(true);
	console.log(varsJs.APP_URL + "get_data_payment");

	$.ajax({
		url: varsJs.APP_URL + "get_data_payment",
		type: "POST",
		data: {
			value: pagoValor,
			credit: credit,
		},
	})
		.done(function (response) {
			preloader(false);
			var resp = JSON.parse(response);
			console.log(resp);
			var handler = ePayco.checkout.configure(resp.configuration);
			console.log(handler);
			handler.open(resp.datos);
		})
		.fail(function () {
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});

// handler.open(data)
