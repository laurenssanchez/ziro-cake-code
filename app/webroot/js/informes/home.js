


$("#otherValueNum").keyup(function () {
    if ($(this).val().length > 1 ){
        $("#othervalueRadio").prop("checked", true);
	}
});

$("#moneyRange,.coutas-number,#valueNumberPrice,#frecuency").change(function(event) {
	calculate();
});

function addColorData(){
	document.querySelectorAll(".__range").forEach(function(el) {
	  el.oninput =function(){
	  var valPercent = (el.valueAsNumber  - parseInt(el.min)) /
	                      (parseInt(el.max) - parseInt(el.min));
	    var style = 'background-image: -webkit-gradient(linear, 0% 0%, 100% 0%, color-stop('+ valPercent+', #d20a11), color-stop('+ valPercent+', #f3f3f3));';
	    el.style = style;
	  };
	  el.oninput();
	});
}

document.querySelectorAll(".__range").forEach(function(el) {
  el.oninput =function(){
  var valPercent = (el.valueAsNumber  - parseInt(el.min)) /
                      (parseInt(el.max) - parseInt(el.min));
    var style = 'background-image: -webkit-gradient(linear, 0% 0%, 100% 0%, color-stop('+ valPercent+', #d20a11), color-stop('+ valPercent+', #f3f3f3));';
    el.style = style;
  };
  el.oninput();
});
init();

setTimeout(function() {
	calculate();
}, 100);

$("#btnPlaPayment").click(function(event) {
	event.preventDefault();
	const priceValue =  $("#valueNumberPrice").val();
	const couteValue = $(".coutas-number").val();
	const frecuency = $("#frecuency").val();
	$.post(varsJs.APP_URL+"general/plan_payments", {priceValue,couteValue,frecuency}, function(data, textStatus, xhr) {
		$("#planPaymentBody").html(data);
		$("#panelPayments").modal("show");
	});
});


function calculate(){
	addColorData()
	const frecuency  = $("#frecuency").val();

	if(frecuency == "1"){
		$(".coutas-number > option").each(function(index, el) {
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	}else{
		$(".coutas-number > option").each(function(index, el) {
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	const priceValue = $("#moneyRange").val();
	const couteValue = $(".coutas-number").val();

	localStorage.setItem("priceValue", parseInt(priceValue));
	localStorage.setItem("couteValue", parseInt(couteValue));
	localStorage.setItem("frecuency", parseInt(frecuency));

	$("#valueNumberPrice").val(priceValue);
	$("#totalSolicita").html(new Intl.NumberFormat("es-CO").format(priceValue))
	$("#totalSolicita2").val(priceValue)

	//frecuencia del pago
	if (frecuency == "1")
		tipoCredito= "Mensual";
	else if(frecuency == "3")
		tipoCredito= "45 días";
	else if(frecuency == "4")
		tipoCredito= "60 días";
	else
		tipoCredito= "Quincenal";

	$("#frecuenciaText").html(tipoCredito);
	// $("#frecuenciaText").html(frecuency == "1" ? "Mensual" : "Quincenal");

	$.post(varsJs.APP_URL+"general/calculate", {priceValue,couteValue,frecuency}, function(data, textStatus, xhr) {
		$("#valueCalculated").html(data);
	});
}

$("#totalSolicita2").change(function(event) {
	var valor  = $("#totalSolicita2").val();
	if(parseInt(valor) > 750000){
		$("#totalSolicita2").val(750000);
	}
	$("#moneyRange").val($("#totalSolicita2").val());
	$("#moneyRange").trigger('change');
	addColorData()
});

function init(){
	var frecuency  = localStorage.getItem('frecuency');

	if(frecuency == null || frecuency == 'NaN' || typeof frecuency === "undefined"){
		frecuency = "1";
	}

	if(frecuency == "1"){
		$(".coutas-number > option").each(function(index, el) {
			var valor = $(this).data("mes");
			$(this).val(valor);
			$(this).text(valor);
		});
	}else{
		$(".coutas-number > option").each(function(index, el) {
			var valor = $(this).data("quince");
			$(this).val(valor);
			$(this).text(valor);
		});
	}

	var priceValue = localStorage.getItem('priceValue');
	var couteValue = localStorage.getItem('couteValue');

	if(priceValue == null){
		localStorage.setItem("priceValue", 300000);
		priceValue = 300000;
	}
	if(couteValue == null || couteValue == 'NaN' || typeof couteValue === "undefined"){
		couteValue = frecuency == "2" ? 2 : 1;
		localStorage.setItem("couteValue", couteValue);
	}
	if(frecuency == null || frecuency == 'NaN' || typeof frecuency == "undefined"){
		localStorage.setItem("frecuency", 1);
		frecuency = 1;
	}

	$("#moneyRange").val(priceValue);
	$(".coutas-number").val(couteValue);
	$("#frecuency").val(frecuency);

	console.log(priceValue);
	console.log(couteValue);

	if(typeof frecuency == "undefined" || frecuency == null){
		$(".coutas-number").val(1);
	}
	addColorData()



}

$(".requestCredit").click(function(event) {
	event.preventDefault();
	const priceValue =  $("#valueNumberPrice").val();
	const couteValue = $(".coutas-number").val();
	const frecuency = $("#frecuency").val();
	$("#preloader").show();
	$.post(varsJs.APP_URL+"general/plan_payments", {priceValue,couteValue,frecuency, final:1}, function(data, textStatus, xhr) {
		$("#planPaymentBody").html(data);
		$("#panelPayments").modal("show");
		$("#preloader").hide();
	});
});

$("body").on('click', '.requestFinal', function(event) {
	event.preventDefault();
	$("#preloader").show();
	const priceValue =  $("#valueNumberPrice").val();
	const couteValue = $(".coutas-number").val();
	const frecuency = $("#frecuency").val();
	setTimeout(function() {
		$.post(varsJs.APP_URL+"credits_requests/created", {priceValue,couteValue,frecuency}, function(data, textStatus, xhr) {
			location.href = root+"credits_requests/index";
		});
		$("#preloader").hide();
	}, 1500);
});


$(".deatil_payment").click(function(event) {
	event.preventDefault();
	const priceValue =  $(this).data("total")
	const couteValue =  $(this).data("number");
	const frecuency  = $(this).data("frecuency");
	$("#preloader").show();
	$.post(varsJs.APP_URL+"general/plan_payments", {priceValue,couteValue,frecuency}, function(data, textStatus, xhr) {
		$("#planPaymentBody").html(data);
		$("#panelPayments").modal("show");
		$("#preloader").hide();
	});
});



$('#CustomerCrediventasForm').parsley().on('form:validate', function (formInstance) {
    if(formInstance.isValid() != false){
        FORMULARIO_VALIDO = true;
    }else{
        FORMULARIO_VALIDO = false;
    }
});

$("#CustomerCrediventasForm").submit(function(event) {
    event.preventDefault();

    if(!FORMULARIO_VALIDO){
        return false;
    }

    if (!CODES_VALID) {
    	showMessage("No se han validado los códigos requeridos para la solicitud",true);
    	return false;
    }

    var form     = $('#CustomerCrediventasForm')[0];
    var formData = new FormData(form);

    $("#preloader").show();

    $.ajax({
        type: "POST",
        url: $('#CustomerCrediventasForm').attr("action"),
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            $("#preloader").hide();
            if($.trim(response) == "1"){
            location.reload()
            }else{
                showMessage(response,true);
            }
        },
        error: function (e) {
            console.log(e)
        }
    });

});

$(".validateCodesData,#reenvioCrediventas").click(function(event) {
	event.preventDefault();
	sendCodes();
	return false;
});

function sendCodes(){
	$(".validateCodesData").hide();
	$(".codesSend").hide();
	var phone = $("#CustomersPhone1PhoneNumber").val();
	var email = $("#CustomerEmail").val();

	$("#preloader").show();

	$.post(varsJs.APP_URL+"general/generate_codes/", {phone:phone,email:email}, function(response, textStatus, xhr) {
		$("#preloader").hide();
		if($.trim(response) == "2"){
            showMessage("El campo correo eléctronico y celular son requeridos",true);
        }else{
        	showMessage("Códigos enviados correctamente");
        	$(".codesSend").show();
        }
	});

}


$("#validarCodigosCrediventas").click(function(event) {
	event.preventDefault();
	var codeEmail =  $("#CustomerCodeEmail").val();
	var codePhone =  $("#CustomerCodePhone").val();

	if (codeEmail == "" || codePhone == "") {
		showMessage("Ambos códigos son requeridos para la validación.",true);
		return false;
	}else{
		$("#preloader").show();
		$.post(varsJs.APP_URL+"general/validate_codes_crediventas/", {phone:codePhone,email:codeEmail}, function(response, textStatus, xhr) {
			$("#preloader").hide();
			if($.trim(response) == "1"){
	            showMessage("Códigos validados correctamente");
	        	$(".codesSend").hide();
	        	$("#guadarCrediventas").show();
	        	$("#CustomersPhone1PhoneNumber").attr('readonly', true);
	        	$("#CustomerEmail").attr('readonly', true);
	        	CODES_VALID = true;
	        }else{
	        	showMessage(response,true);
	        	CODES_VALID = false;
	        }
		});
	}


});
