$("body").on("click", ".viewCustomerRequest", function (event) {
	event.preventDefault();
	BTB_SEARCH = $(this);
	var customer = $(this).data("customer");
	console.log(JSON.stringify(customer));
	var request = $(this).data("request");
	$.post(
		root + "customers/get_data_customers",
		{ customer, request },
		function (data, textStatus, xhr) {
			console.log(data);
			$("#requestBody").html(data);
			$("#request-modal").modal("show");
			$("#NotesCustomerGetDataCustomersForm").parsley();
			$("#DocumentGetDataCustomersForm").parsley();
		}
	);
	return false;
});
////esto es lo nuevo

$("body").on("click", ".fastsearchingData", function (event) {
	event.preventDefault();
	preloader(true);
	$("#bodyDeuda").html("");
	var identification = $(this).data("idt");
	$.ajax({
		url: varsJs.APP_URL + "payment_fast_search",
		type: "POST",
		data: { identification },
	})
		.done(function (response) {
			preloader(false);
			$("#bodyDeuda").html(response);
			$("#fastFormDataSelectCredit").parsley();
			$("#modalDeuda").modal("show");
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
	$("#bodyDeuda").html("");
	preloader(true);
	$.ajax({
		url: varsJs.APP_URL + "payment_fast_select",
		type: "POST",
		data: $(this).serialize(),
	})
		.done(function (response) {
			preloader(false);
			$("#bodyDeuda").html(response);
		})
		.fail(function () {
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});

$("body").on("click", ".paymentBtnFast", function (event) {
	alert('hola 2')

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

	//vista interna del cliente
	var credit = $(this).data("id");

	preloader(true);

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

			// var resp = JSON.parse(response);
			// console.log(resp);
			// var handler = ePayco.checkout.configure(resp.configuration);
			// console.log(handler);
			// handler.open(resp.datos);
			var resp = JSON.parse(response);
			var checkout = new WidgetCheckout(resp.datos);
			checkout.open(function (result) {
				var transaction = result.transaction;

				if (transaction.redirectUrl) {
					var url = transaction.redirectUrl + "?id=" + transaction.id;
					location.href = url;
				}

				console.log("Transaction ID: ", transaction.id);
				console.log("Transaction object: ", transaction);
			});

			console.log(response);
		})
		.fail(function () {
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});

$("body").on("click", "#solicitaNew", function (event) {
	event.preventDefault();
	restoreExtras();
	$("#shop_commerce_extra").select2({
		// theme: 'bootstrap4',
		placeholder: "Buscar comercio",
		dropdownParent: "#request_credit_extra",
	});
	$("#formCreditExtra").parsley();
	$("#request_credit_extra").modal("show");
});

$("body").on("click", "#transferBtn", function (event) {
	event.preventDefault();
	restoreTransfer();
	$("#shop_commerce_transfer").select2({
		// theme: 'bootstrap4',
		placeholder: "Buscar comercio",
		dropdownParent: "#transfer_credit",
	});
	$("#formTransfer").parsley();
	$("#transfer_credit").modal("show");
});

function restoreExtras() {
	$("#shop_commerce_extra").val("");
	$("#frecuency").val("1");
	$("#valueCredit").val("100000");

	$("#frecuency").trigger("change");
	$("#shop_commerce_extra").trigger("change");
	$("#valueCredit").trigger("change");
}

function restoreTransfer() {
	$("#shop_commerce_transfer").val("");
	$("#shop_commerce_transfer").trigger("change");

	$("#frecuencyValueTransfer").val("1");
	$("#frecuencyValueTransfer").trigger("change");

	$("#creditTransverValue").val($("#creditTransverValue").data("value"));

	$("#creditTransverValue").trigger("change");
	$("#codePhone").val("");
	$("#codeMail").val("");
	$("#codeMailRequest").val("");
	$("#codePhoneRequest").val("");

	$(".codigosCliente").hide();
	$("#applyConfirmBtn").hide();
	$("#initTransferData").show();

	$(".envioCodigos").hide();
}

// handler.open(data)

/*********************************************************************************/

$("#valueCredit,#numberCuote,#frecuency").change(function (event) {
	calculateExtraCredit();
});

initExtraCredit();

setTimeout(function () {
	calculateExtraCredit();
}, 100);

function calculateExtraCredit() {
	const frecuency = $("#frecuency").val();

	if (frecuency == "1") {
		$("#numberCuote > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	} else if (frecuency == "3" || frecuency == "4") {
		$("#numberCuote > option").each(function (index, el) {
			var valor = $(this).data("mes");
			if (valor != "1") {
				$(this).addClass("d-none").removeClass("d-block");
			}
			$(this).val("1");
			$(this).text("1");
		});
	} else {
		$("#numberCuote > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	const priceValue = $("#valueCredit").val();
	const couteValue = $("#numberCuote").val();

	localStorage.setItem("priceValue", parseInt(priceValue));
	localStorage.setItem("couteValue", parseInt(couteValue));
	localStorage.setItem("frecuency", parseInt(frecuency));

	$("#valueNumberPrice").val(priceValue);

	//frecuencia del pago
	if (frecuency == "1") tipoCredito = "Mensual";
	else if (frecuency == "3") tipoCredito = "45 días";
	else if (frecuency == "4") tipoCredito = "60 días";
	else tipoCredito = "Quincenal";

	$("#frecuenciaText").html(tipoCredito);

	//   $("#frecuenciaText").html(frecuency == "1" ? "Mensual" : "Quincenal");

	var idsLine = typeof isdLine != "undefined" ? isdLine : null;

	$.post(
		varsJs.APP_URL + "general/calculate",
		{ priceValue, couteValue, frecuency, idsLine },
		function (data, textStatus, xhr) {
			$("#valueData").html(data);
			var actual = parseFloat($("#textCredit").data("actual"));
			var nuevo = actual + parseFloat($("#valueCredit").val());
			$("#newValueData").html(new Intl.NumberFormat("es-CO").format(nuevo));
		}
	);
}

$("#totalSolicita2").change(function (event) {
	var valor = $("#totalSolicita2").val();
	if (parseInt(valor) > 7500000000000000000000000000000000000) {
		$("#totalSolicita2").val(7500000000000000000000000000000000000);
	}
	$("#valueCredit").val($("#totalSolicita2").val());
	$("#valueCredit").trigger("change");
	addColorData();
});

function initExtraCredit() {
	var frecuency = localStorage.getItem("frecuency");

	if (
		frecuency == null ||
		frecuency == "NaN" ||
		typeof frecuency === "undefined"
	) {
		frecuency = "1";
	}

	if (frecuency == "1") {
		$("#numberCuote > option").each(function (index, el) {
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	} else {
		$("#numberCuote > option").each(function (index, el) {
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	var priceValue = localStorage.getItem("priceValue");
	var couteValue = localStorage.getItem("couteValue");

	if (priceValue == null) {
		localStorage.setItem("priceValue", 300000);
		priceValue = 300000;
	}
	if (
		couteValue == null ||
		couteValue == "NaN" ||
		typeof couteValue === "undefined"
	) {
		couteValue = frecuency == "2" ? 2 : 1;
		localStorage.setItem("couteValue", couteValue);
	}
	if (
		frecuency == null ||
		frecuency == "NaN" ||
		typeof frecuency == "undefined"
	) {
		localStorage.setItem("frecuency", 1);
		frecuency = 1;
	}

	$("#valueCredit").val(priceValue);
	$("#numberCuote").val(couteValue);
	$("#frecuency").val(frecuency);

	console.log(priceValue);
	console.log(couteValue);

	if (typeof frecuency == "undefined" || frecuency == null) {
		$("#numberCuote").val(1);
	}
}

//////////////////////////////////////////////////Transfer////////////////////////////////////////////

$("#creditTransverValue,#cuotasTransferValue,#frecuencyValueTransfer").change(
	function (event) {
		calculateTransfer();
	}
);

$("body")
	.on("change", "#creditTransverValue", function (event) {
		var maxValue = parseInt($(this).attr("max"));
		setTimeout(function () {
			var actualVal = parseInt($("#creditTransverValue").val());
			if (actualVal > maxValue) {
				$("#creditTransverValue").val(maxValue);
			}
		}, 1000);
	})
	.on("keypress", "#creditTransverValue", function (event) {
		var maxValue = parseInt($(this).attr("max"));
		setTimeout(function () {
			var actualVal = parseInt($("#creditTransverValue").val());
			if (actualVal > maxValue) {
				$("#creditTransverValue").val(maxValue);
			}
		}, 1000);
	});

initTransfer();

setTimeout(function () {
	calculateTransfer();
}, 100);

function calculateTransfer() {
	const frecuency = $("#frecuencyValueTransfer").val();
	if (frecuency == "1") {
		$("#cuotasTransferValue > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	} else if (frecuency == "3" || frecuency == "4") {
		$("#cuotasTransferValue > option").each(function (index, el) {
			var valor = $(this).data("mes");
			if (valor != "1") {
				$(this).addClass("d-none").removeClass("d-block");
			}
			$(this).val("1");
			$(this).text("1");
		});
	} else {
		$("#cuotasTransferValue > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	const priceValue = $("#creditTransverValue").val();
	const couteValue = $("#cuotasTransferValue").val();

	localStorage.setItem("priceValue", parseInt(priceValue));
	localStorage.setItem("couteValue", parseInt(couteValue));
	localStorage.setItem("frecuency", parseInt(frecuency));

	$("#valueNumberPrice").val(priceValue);

	//frecuencia del pago
	if (frecuency == "1") tipoCredito = "Mensual";
	else if (frecuency == "3") tipoCredito = "45 días";
	else if (frecuency == "4") tipoCredito = "60 días";
	else tipoCredito = "Quincenal";

	$("#frecuenciaText").html(tipoCredito);
	//   $("#frecuenciaText").html(frecuency == "1" ? "Mensual" : "Quincenal");

	var idsLine = typeof isdLine != "undefined" ? isdLine : null;

	$.post(
		varsJs.APP_URL + "general/calculate",
		{ priceValue, couteValue, frecuency, idsLine },
		function (data, textStatus, xhr) {
			$("#valueDataTransfer").html(data);
			$("#valueDataTransfer").attr("data-valor", data);
		}
	);
}

$("#totalSolicita2").change(function (event) {
	var valor = $("#totalSolicita2").val();
	if (parseInt(valor) > 7500000000000000000000000000000000000) {
		$("#totalSolicita2").val(7500000000000000000000000000000000000);
	}
	$("#creditTransverValue").val($("#totalSolicita2").val());
	$("#creditTransverValue").trigger("change");
	addColorData();
});

function initTransfer() {
	var frecuency = localStorage.getItem("frecuency");

	if (
		frecuency == null ||
		frecuency == "NaN" ||
		typeof frecuency === "undefined"
	) {
		frecuency = "1";
	}

	if (frecuency == "1") {
		$("#cuotasTransferValue > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	} else if (frecuency == "3" || frecuency == "4") {
		$("#cuotasTransferValue > option").each(function (index, el) {
			var valor = $(this).data("mes");
			if (valor != "1") {
				$(this).addClass("d-none").removeClass("d-block");
			}
			$(this).val("1");
			$(this).text("1");
		});
	} else {
		$("#cuotasTransferValue > option").each(function (index, el) {
			$(this).addClass("d-block").removeClass("d-none");
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	var priceValue = localStorage.getItem("priceValue");
	var couteValue = localStorage.getItem("couteValue");

	if (priceValue == null) {
		localStorage.setItem("priceValue", 300000);
		priceValue = 300000;
	}
	if (
		couteValue == null ||
		couteValue == "NaN" ||
		typeof couteValue === "undefined"
	) {
		couteValue = frecuency == "2" ? 2 : 1;
		localStorage.setItem("couteValue", couteValue);
	}
	if (
		frecuency == null ||
		frecuency == "NaN" ||
		typeof frecuency == "undefined"
	) {
		localStorage.setItem("frecuency", 1);
		frecuency = 1;
	}

	$("#creditTransverValue").val(priceValue);
	$("#cuotasTransferValue").val(couteValue);
	$("#frecuencyValueTransfer").val(frecuency);

	console.log(priceValue);
	console.log(couteValue);

	if (typeof frecuency == "undefined" || frecuency == null) {
		$("#cuotasTransferValue").val(1);
	}
}

$("#initTransferData").on("click", function (event) {
	event.preventDefault();

	var commerce = $("#shop_commerce_transfer").val();
	var value = $("#creditTransverValue").val();
	var customer = $("#customer").val();
	var type = $("#type").val();

	var maxValue = parseInt($("#creditTransverValue").attr("max"));
	var actualVal = parseInt($("#creditTransverValue").val());

	if (actualVal > maxValue) {
		showMessage(
			"Error, intentas transferir más cupo del que tienes aprobado, intenta solicitando un aumento de cupo.",
			true
		);
		return false;
	}

	if (commerce == "") {
		showMessage("Error, es necesario seleccionar el comercio.", true);
	} else {
		Swal.fire({
			title: "Confirmar",
			text:
				"¿Está seguro crear esta solicitud por el valor pre-aprobado de: " +
				value +
				"?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Aceptar",
			cancelButtonText: "Cancelar",
		}).then((result) => {
			if (result.value) {
				$.post(
					root + "credits_requests/create_request_approved",
					{ value, customer, commerce, transfer: 1 },
					function (data, textStatus, xhr) {
						data = $.trim(data);
						if (data != "0") {
							var numberq = 4;
							var id = data;
							$("#id_request").val(id);

							if (type == "0") {
								$(".envioCodigos").hide();
								$("#applyConfirmBtn").show();
							} else {
								$("#initTransferData").hide();
								$("#applyConfirmBtn").hide();
								$(".envioCodigos").show();
							}
						}
					}
				);
			}
		});
	}
});

$("#enviarCodigosCliente,#reenviarCodigos").click(function (event) {
	$("#preloader").show();
	event.preventDefault();
	var request = $("#id_request").val();
	var valorCredito = $("#creditTransverValue").val();
	var cuotasCredito = $("#cuotasTransferValue").val();
	var cuotaCredito = $("#valueDataTransfer").attr("data-valor");
	$.post(
		root + "credits_requests/sendCodesCredit",
		{ request, valorCredito, cuotaCredito, cuotasCredito },
		function (data, textStatus, xhr) {
			data = $.parseJSON(data);
			$("#preloader").hide();
			$("#codeMailRequest").val(data.codeEmail);
			$("#codePhoneRequest").val(data.codePhone);
			$(".envioCodigos").hide();
			$(".codigosCliente").show();
		}
	);
});

$("#validarCodigos").click(function (event) {
	event.preventDefault();
	var request = $("#id_request").val();
	var codeMailRequest = $("#codeMailRequest").val();
	var codePhoneRequest = $("#codePhoneRequest").val();
	var codeMail = $("#codeMail").val();
	var codePhone = $("#codePhone").val();

	if (codePhone == "") {
		showMessage("Los códigos son requeridos.", true);
	} else {
		$.post(
			root + "credits_requests/validateCode",
			{ request, codeMailRequest, codePhoneRequest, codeMail, codePhone },
			function (data, textStatus, xhr) {
				if ($.trim(data) == "1") {
					showMessage(
						"Error, los códigos no coinciden con los enviados.",
						true
					);
				} else if ($.trim(data) == "2") {
					showMessage(
						"Error, uno o los dos códigos expiraron su vigencia, revisa los códigos que fueron enviados nuevamente.",
						true
					);
				} else {
					$(".codigosCliente").hide();
					$("#applyConfirmBtn").show();
					$("body").find("#creditTransverValue").attr("readonly", "readonly");
					$("#frecuency").attr("readonly", "readonly");
					$("#valueNumberQ").attr("readonly", "readonly");
					showMessage("Códigos validados.", false);
				}
			}
		);
	}
});

$("#applyConfirmBtn").click(function (event) {
	event.preventDefault();

	var valueCredit = $("#creditTransverValue").val();
	var valueNumberQ = $("#cuotasTransferValue").val();
	var id_request = $("#id_request").val();
	var frecuency = $("#frecuencyValueTransfer").val();
	var commerce = $("#shop_commerce_transfer").val();
	var fecha = $("#dates-retires").hasClass("d-none")
		? "0"
		: $("#dateRetired").val();

	$("#preloader").show();
	$.post(
		root + "credits_requests/applyCredit",
		{ valueCredit, valueNumberQ, id_request, frecuency, commerce, fecha },
		function (data, textStatus, xhr) {
			reloadData();
			window.open(
				root + "credits/plan_payemts_pdf/" + id_request + "/view",
				"_blank"
			);
		}
	);
});

function reloadData() {
	setTimeout(function () {
		location.reload();
	}, 10000);
}
