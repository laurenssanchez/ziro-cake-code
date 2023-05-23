BTB_SEARCH = null;
$("body").on('click', '.viewCustomerRequest', function(event) {
	event.preventDefault();
	BTB_SEARCH = $(this);
	var customer = $(this).data("customer");
	var request = $(this).data("request");
	$.post(root+"customers/get_data_customers", {customer,request}, function(data, textStatus, xhr) {
		$("#requestBody").html(data);
		$("#request-modal").modal("show");
	});
	return false;
});

$("body").on('click', '.asignUser', function(event) {
	event.preventDefault();
	var id = $(this).data("request");

	$("#requestId").val(id);
	$("#assignValue").modal("show");
});

$("body").on('submit', '#formAsign', function(event) {
	event.preventDefault();

	var request = $("#requestId").val();
	var user_id = $("#userSelect").val();

	if(user_id == ""){
		showMessage("Seleccione un usuario",true)
	}else{
		$.post(root+controller+"/assing_user", {request,user_id}, function(data, textStatus, xhr) {
			location.reload();
		});
	}
});

$("body").on('click', '#editarCliente', function(event) {
	event.preventDefault();
	console.log("hola")
	$(".camposFormulario").each(function(index, el) {
		$(this).removeAttr('disabled')
	});
	$("#formDataCustomers").parsley();
	$("#NotesCustomerGetDataCustomersForm").parsley();
});

$("body").on('click', '.passTome', function(event) {
	event.preventDefault();

	var request = $(this).data("request");
	var user_id = $(this).data("user");

	let url = root+controller+"/assing_user";
	Swal.fire({
	    title: "Confirmar",
	    text: "¿Está seguro asignarse este credito?",
	    type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
	}).then((result) => {
	  if (result.value) {
	  	$.post(root+controller+"/assing_user", {request,user_id}, function(data, textStatus, xhr) {
			location.reload();
		});
	  }
	})
});

$("body").on('click', '.photoUp', function(event) {
	event.preventDefault();
	var url = $(this).data("url");
	$("#imgPhotoUp").attr("src",url);
});

$("body").on('click', '.photoDown', function(event) {
	event.preventDefault();
	var url = $(this).data("url");
	$("#imgPhotoDown").attr("src",url);
});

$("body").on('click', '.photoUser', function(event) {
	event.preventDefault();
	var url = $(this).data("url");
	$("#imgSelfie").attr("src",url);
});

$("body").on('click', '.viewComments', function(event) {
	event.preventDefault();
	var request = $(this).data("request");
	callModalRequst(request);
});

function callModalRequst(request){
	$("#commentsBody").html()
	$.post(root+"credits_requests_comments/view_comment", {request}, function(data, textStatus, xhr) {
		$("#commentsBody").html(data);
		$("#comments-modal").modal("show");
	});
}

$("body").on('submit', '#CreditsRequestsCommentViewCommentForm', function(event) {
	event.preventDefault();
	$.post(root+"credits_requests_comments/save_comment", $(this).serialize(), function(data, textStatus, xhr) {
		if($.trim(data) === "1"){
			location.reload();
		}else{
			showMessage("Comentario guardado correctamente",false)
			callModalRequst(data);
		}
	});
});

$("body").on('click', '.adminCreditFinal', function(event) {
	event.preventDefault();
	var request = $(this).data("request");
	$.post(root+controller+"/simulate", {request}, function(data, textStatus, xhr) {
		$("#bodyDescision").html(data);
		$("#decision-modal").modal("show");
		setTimeout(function() {
			if($("body").find("#viewCentral").length){
				$("body").find("#viewCentral").trigger("click");
			}
		}, 500);
	});
});


$("body").on('click', '.rejectRequest', function(event) {
	event.preventDefault();
	var Rdnb 	= $(this).data("id");

	$.get(root+controller+"/reject/"+Rdnb, function(data) {
		$("#rejectBody").html(data);
		$("#request-reject").modal("show");
	});
});

$("body").on('submit', '#rejectRequestForm', function(event) {
	event.preventDefault();
	$.post(root+controller+"/reject/",$(this).serialize(), function(data,status,error) {
		location.reload();
	});
});


$("body").on('click', '.approveRequest', function(event) {
	event.preventDefault();
	var Rdnb 	= $("#Rdnb").val();
	$("#preloader").show();
	$.get(root+controller+"/approve/"+Rdnb, function(data) {
		$("#bodyApprove").html(data);
		$("#request-approved").modal("show");
		$("#preloader").hide();
		changeValues();
	});
});

function changeValues(){
	var frecuency = $("#CreditsRequestTypeRequest").val();
	var actualNum = $("#CreditsRequestNumberApprove").val();

	var idDataRes = $('.approveRequest').data("id");

	var maxNumber = $("#CreditsRequestNumberApprove").attr("max");

	var numReferenceOne = idDataRes == idDataRol ? 8 : 4;
	if(frecuency == "1"){
		if(maxNumber > numReferenceOne){
			if(actualNum % 2 == 0){
				$("#CreditsRequestNumberApprove").val(actualNum / 2);
			}
		}
		$("#CreditsRequestNumberApprove").attr("max",numReferenceOne);
		$("#CreditsRequestNumberApprove").attr("step",1);
		$("#CreditsRequestNumberApprove").attr("min",1);
	}
	console.log(maxNumber);
	if(frecuency == "2"){
		var numReferenceTwo = idDataRes == idDataRol ? 16 : 8;
		if(parseInt(maxNumber) <= numReferenceOne){
			$("#CreditsRequestNumberApprove").val(actualNum * 2);
		}
		$("#CreditsRequestNumberApprove").attr("max",numReferenceTwo);
		$("#CreditsRequestNumberApprove").attr("step",2);
		$("#CreditsRequestNumberApprove").attr("min",2);
	}
}

$("body").on('keyup','#CreditsRequestNumberApprove', function(event) {
	setTimeout(function() {
		var idDataRes = $('#CreditsRequestNumberApprove').data("id");
		var numReferenceOne = idDataRes == idDataRol ? 8 : 4;
		var numReferenceTwo = idDataRes == idDataRol ? 16 : 8;

		var frecuency = $("#CreditsRequestTypeRequest").val();
		if ( ($("#CreditsRequestNumberApprove").val() == "" || parseInt($("#CreditsRequestNumberApprove").val()) > numReferenceOne  )  && frecuency == "1") {
			$("#CreditsRequestNumberApprove").val("1");
		}
		if ( ($("#CreditsRequestNumberApprove").val() == "" || parseInt($("#CreditsRequestNumberApprove").val()) > numReferenceTwo)  && frecuency == "2"  ) {
			$("#CreditsRequestNumberApprove").val("2");
		}
	}, 200);

});

$("body").on('change', '#CreditsRequestTypeRequest', function(event) {
	changeValues();
});

$("body").on('submit', '#approveRequestForm', function(event) {
	event.preventDefault();
	$("#preloader").show();
	$.post(root+controller+"/approve/"+$("#Rdnb").val(),$(this).serialize(), function(data,status,error) {
		location.reload();
	});
});


$("body").on('click', '.applyCredit', function(event) {
	event.preventDefault();
	var value 	= $(this).data("value");
	var numberq = $(this).data("numberq");
	var id 		= $(this).data("id");
	var frecuency = $(this).data("frecuency");
	var type 	  = $(this).data("type");
	var returnD   = $(this).data("return");

	$("#valueCredit").attr("max",value);
	$("#valueCredit").val(value);
	$("#frecuency").val(frecuency);
	$("#id_request").val(id);

	if(returnD == "1"){
		$("#dates-retires").removeClass('d-none');
	}else{
		$("#dates-retires").addClass('d-none')
	}

	if(type == "0"){
		$(".envioCodigos").hide();
		$("#applyConfirmBtn").show();
	}else{
		$("#applyConfirmBtn").hide();
		$(".envioCodigos").show();
	}

	setTimeout(function() {
		calculateFinal();
		$("#valueNumberQ").val(numberq);
		getValueCuota();
	}, 500);
});

$("body").on('change', '#valueCredit,#valueNumberQ,#frecuency', function(event) {
	setTimeout(function() {
		getValueCuota();
	}, 1000);
});

function getValueCuota(){
	$.post(root+"pages/calculate", {couteValue:$("#valueNumberQ").val(), priceValue: $("#valueCredit").val(), frecuency: $("#frecuency").val() }, function(data, textStatus, xhr) {
		data = $.trim(data);
		$(".textCuote").html("Valor cuota: $"+data);
	});
}

$("body").on('change', '#frecuency', function(event) {
	calculateFinal();
});

$("#applyConfirmBtn").click(function(event) {
	event.preventDefault();

	var valueCredit 	= 	$("#valueCredit").val();
	var valueNumberQ 	=	$("#valueNumberQ").val();
	var id_request 		= 	$("#id_request").val();
	var frecuency 		= 	$("#frecuency").val();
	var commerce 		= 	$("#shop_commerce_data").val();
	var fecha 			=   $("#dates-retires").hasClass('d-none') ? "0" : $("#dateRetired").val();


	$("#preloader").show();
	$.post(root+controller+"/applyCredit", {valueCredit,valueNumberQ,id_request,frecuency,commerce,fecha}, function(data, textStatus, xhr) {
		reloadData();
		window.open(root+"credits/plan_payemts_pdf/"+id_request+"/view", '_blank');
	});

});

function reloadData(){
	setTimeout(function() {
		location.reload();
	}, 10000);
}

$(".viewVoucher").click(function(event) {
	event.preventDefault();
	var id = $(this).data("request");
	$("#preloader").show();
	$.post(root+controller+"/voucher/"+id, {}, function(data, textStatus, xhr) {
		$("#bodyRequestVoucher").html(data);
		$("#voucher").modal("show")
		$("#preloader").hide();
	});
});

$(".viewReciboPago").click(function(event) {
	event.preventDefault();
	var id = $(this).data("request");
	$.post(root+controller+"/receipt_payment/"+id, {}, function(data, textStatus, xhr) {
		$("#bodyReciboPago").html(data);
		$("#receipt_payment").modal("show")
	});
});

$("body").on('click', '.detailCredit', function(event) {
	event.preventDefault();
	var id = $(this).data("request");
	$("#preloader").show();
	$.post(root+controller+"/credit_detail/"+id, {}, function(data, textStatus, xhr) {
		$("#creditDetailBody").html(data);
		$("#credit_detail-modal").modal("show")
		$("#preloader").hide();
	});
});

$("body").on('click', '#searchCentral', function(event) {

	var request = $(this).data("request");
	$(".result-consult").html('');
	$("#preloader").show();

	$.post(root+controller+"/consult_central/", {request:request}, function(data, textStatus, xhr) {
		$(".result-consult").html(data);
		$("#preloader,#searchCentral").hide();
	});

});

$("body").on('click', '#viewCentral', function(event) {

	var request = $(this).data("request");
	$(".result-consult").html('');
	$("#preloader").show();

	$.post(root+controller+"/view_central/", {request:request}, function(data, textStatus, xhr) {
		$(".result-consult").html(data);
		$("#preloader,#searchCentral").hide();
	});

});



$("body").on('click', '.rejectPrev', function(event) {
	event.preventDefault();
	var id = $(this).data("request");
	Swal.fire({
	  title: 'Por favor ingrese el motivo de devolución',
	  input: 'text',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
	  inputValidator: (reason) => {
	  	$("#preloader").hide();
	    if (!reason) {
	      return 'El motivo de devolución es necesario.'
	    }else{
			$("#preloader").show();
	    	$.post(root+"credits_requests_comments/return_request", {id,reason}, function(data, textStatus, xhr) {
	    		location.reload();
			});
	    }
	  }
	})
});

$("body").on('click', '.returnRequestData', async function(event) {
	event.preventDefault();
	var id = $(this).data("request");

	const { value: formValues } = await Swal.fire({
	  title: 'Devolución de crédito',
	  html:
	    '<form>'+
	    '<label htmlFor="motivoDev"> <b>Razón de devolución</b> </label>'+
	    '<input id="motivoDev" class="swal2-input" required></form>',
	  focusConfirm: false,
	  confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
	  preConfirm: () => {
	  	var motivo = document.getElementById('motivoDev').value;
	  	if (motivo == ""){
	  		return false;
	  	}
	    return [motivo]
	  }
	})
	if (formValues[0]) {
		$("#preloader").show();
	}else{
		showMessage("Error, La razón es requerida.",true);
		return false;
	}

  	$.post(root+"credits_requests_comments/return_to_approved", {id,reason: formValues[0]}, function(data, textStatus, xhr) {
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
		$.post(root+controller+"/search_customer", {cc}, function(data, textStatus, xhr) {
			$("#dataCustomerDataPayment").html(data);
		});
	}
});

$("body").on('submit', '#formDataCustomers', function(event) {
	event.preventDefault();
	preloader(true);
	$.post(root+"customers/edit_info", $(this).serialize(), function(data, textStatus, xhr) {
		preloader(false);
		BTB_SEARCH.trigger("click");
	});
});

$("body").on('submit', '#NotesCustomerGetDataCustomersForm', function(event) {
	event.preventDefault();
	preloader(true);
	$.post(root+"customers/add_note", $(this).serialize(), function(data, textStatus, xhr) {
		preloader(false);
		BTB_SEARCH.trigger("click");
	});
});


$("#valueCredit").change(function(event) {
	if($(this).val() > 750000){
		$(this).val(750000)
	}
});

$("#valueCredit").change(function(event) {
	if(parseInt($(this).val()) > parseInt($(this).attr("max") )){
		$(this).val($(this).attr("max"))
	}
});



$("#enviarCodigosCliente,#reenviarCodigos").click(function(event) {
	$("#preloader").show();
	event.preventDefault();
	var request = $("#id_request").val();
	$.post(root+controller+"/sendCodesCredit", {request}, function(data, textStatus, xhr) {
		data = $.parseJSON(data);
		$("#preloader").hide();
		$("#codeMailRequest").val(data.codeEmail);
		$("#codePhoneRequest").val(data.codePhone);
		$(".envioCodigos").hide();
		$(".codigosCliente").show();
	});
});

$("#validarCodigos").click(function(event) {
	event.preventDefault();
	var request 			= $("#id_request").val();
	var codeMailRequest 	= $("#codeMailRequest").val();
	var codePhoneRequest 	= $("#codePhoneRequest").val();
	var codeMail 			= $("#codeMail").val();
	var codePhone 			= $("#codePhone").val();

	if(codeMail == "" || codePhone == ""){
		showMessage("Los códigos son requeridos.",true);
	}else{
		$.post(root+controller+"/validateCode", {request,codeMailRequest,codePhoneRequest,codeMail,codePhone}, function(data, textStatus, xhr) {
			if($.trim(data) == "1"){
				showMessage("Error, los códigos no coinciden con los enviados.",true);
			}else if ($.trim(data) == "2") {
				showMessage("Error, uno o los dos códigos expiraron su vigencia, revisa los códigos que fueron enviados nuevamente.",true);
			}else{
				$(".codigosCliente").hide();
				$("#applyConfirmBtn").show();
				$("body").find("#valueCredit").attr("readonly","readonly")
				$("#frecuency").attr("readonly","readonly")
				$("#valueNumberQ").attr("readonly","readonly")
				showMessage("Códigos validados.",false);
			}
		});
	}

});

$("body").on('click', '.applyCreditNew', function(event) {
	event.preventDefault();
	var value 	 = $(this).data("value");
	var customer = $(this).data("customer");
	var type 	 = $(this).data("type");
	var commerce = $("#shop_commerce_id").val();
	var valueShow = new Intl.NumberFormat().format(value)
	Swal.fire({
	    title: "Confirmar",
	    text: "¿Está seguro crear esta solicitud por el valor pre-aprobado de: "+valueShow+"?",
	    type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
	}).then((result) => {
	  if (result.value) {
	  	$.post(root+controller+"/create_request_approved", {value,customer,commerce}, function(data, textStatus, xhr) {
			if(data != "0"){
				var numberq = 4;
				var id 		= data;
				$("#valueCredit").attr("max",value);
				$("#valueCredit").val(value);
				$("#valueNumberQ").val(numberq);
				$("#id_request").val(id);
				$("#shop_commerce_data").val(commerce)
				$("#searchCustomer").modal("hide");
				$("#credit_applied").modal("show");
				calculateFinal();

				if(type == "0"){
					$(".envioCodigos").hide();
					$("#applyConfirmBtn").show();
				}else{
					$("#applyConfirmBtn").hide();
					$(".envioCodigos").show();
				}

			}
		});
	  }
	})

});

function calculateFinal(){
	const frecuency  = $("#frecuency").val();

	if(frecuency == "1"){
		$("#valueNumberQ > option").each(function(index, el) {
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	}else{
		$("#valueNumberQ > option").each(function(index, el) {
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}
}


$("#limpia").click(function(event) {
	$("#CreditsRequestCcCustomer").val("");
	$("#busca").click();
});
