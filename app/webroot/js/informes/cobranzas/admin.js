BTN_CLICK = null;


$("body").on('click', '.adminQuote', function(event) {
    event.preventDefault();
    BTN_CLICK = $(this);
    var quote  = $(this).data("quote");
    var credit  = $(this).data("credit");
    var tab  = $(this).data("tab");
    preloader();
    $.post(root+"credits_plans/admin", {quote,credit,tab}, function(data, textStatus, xhr) {
        $("#cuerpoCob").html(data);
        $("#detail_payment").modal("show");
        preloader(false);
    });
});

$("body").on('click', '.viewCustomerRequest', function(event) {
	event.preventDefault();
	var customer = $(this).data("customer");
	var request = $(this).data("request");
	preloader()
	$.post(root+"customers/get_data_customers", {customer,request}, function(data, textStatus, xhr) {
		$("#requestBody").html(data);
		$("#request-modal").modal("show");
		preloader(false)
	});
});

$("body").on('click', '.dataCallBtn', function(event) {
	event.preventDefault();
	var quote  = $(this).data("quote");
	var number  = $(this).data("number");
	preloader();
	$.post(root+"credits_plans/save_note_call", {quote,number}, function(data, textStatus, xhr) {
		BTN_CLICK.trigger("click");
		preloader(false)
		showMessage("Llamada realizada");
	});
});

$("body").on('click', '#createCompromiso', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	preloader()
	$.post(root+"credits_plans/form_data", {id}, function(data, textStatus, xhr) {
		$("#cuerpoNewComm").html(data);
		$("#new_commitment").modal("show");
		$("#detail_payment").modal("hide");
		preloader(false)
	});
});

$("body").on('submit', '#CommitmentFormDataForm', function(event) {
	event.preventDefault();
	$.post(root+"credits_plans/add_commitment", $(this).serialize(), function(data, textStatus, xhr) {
		$("#new_commitment").modal("hide");
		$("body").find("#new_commitment").modal("hide");
		setTimeout(function() {
			$("#new_commitment").modal("hide");
			$("body").find("#new_commitment").modal("hide");
		}, 2000);
		preloader(false)
		BTN_CLICK.trigger("click");
	});
});

$('#new_commitment').on('hidden.bs.modal', function (e) {
  $("#detail_payment").modal("show");
})

$("body").on('click', '.tabSelect', function(event) {
	var tab = $(this).data("tab");
	BTN_CLICK.data("tab",tab);
});

$("body").on('submit', '#NoteAdminForm', function(event) {
	event.preventDefault();
	$.post(root+"credits_plans/add_note", $(this).serialize(), function(data, textStatus, xhr) {
		preloader(false)
		BTN_CLICK.trigger("click");
	});
});

$("body").on('change', '.stateCommitment', function(event) {
	var id = $(this).data("id")
	var state = $(this).val();

	if($(this).val() != "" ){
		Swal.fire({
		    title: "Confirmar",
		    text: "¿Está seguro de cambiar el estado del compromiso?",
		    type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: "Aceptar",
	        cancelButtonText: "Cancelar",
		}).then((result) => {
		  if (result.value) {
		  	preloader(true)

		  	$.post(root+"credits_plans/change_state", {id,state}, function(data, textStatus, xhr) {

		  		preloader(false)
				BTN_CLICK.trigger("click");
			});
		  }
		})
	}
});


$("body").on('click', '.sendIndividualMensaje', function(event) {
	event.preventDefault();
	var id 		= $(this).data("id");
	var number  = $(this).data("number");
	$("#detail_payment").modal("hide");
	Swal.fire({
	 	title: 'Por favor escribe el mensaje a enviar',
	  	input: 'textarea',
	  	showCancelButton: true,
	  	confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
	    cancelButtonText: "Cancelar",
	    onClose: function(){
	    	BTN_CLICK.trigger("click");
	    },
	  	inputValidator: (message) => {
	  		$("#preloader").hide();
		    if (!message) {
		      return 'El mensaje es necesario.'
		    }else{
				preloader(true)
		    	$.post(root+"credits_plans/send_mesage_one", {id,number,message}, function(data, textStatus, xhr) {
		    		preloader(false)
					BTN_CLICK.trigger("click");
				});
		    }
	  	}
	})
});

$("body").on('click', '#sendAll', function(event) {
	event.preventDefault();
	Swal.fire({
	 	title: 'Por favor escribe el mensaje a enviar',
	  	input: 'textarea',
	  	showCancelButton: true,
	  	confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
	    cancelButtonText: "Cancelar",
	  	inputValidator: (message) => {
	  		$("#preloader").hide();
		    if (!message) {
		      return 'El mensaje es necesario.'
		    }else{
				preloader(true)
		    	$.post(root+"credits_plans/send_mesage_all", {dataPhone,message}, function(data, textStatus, xhr) {
		    		preloader(false)
				});
		    }
	  	}
	})
});

$("#changeUserData").change(function(event) {

	var urlValue = $(this).val();
	if(urlValue == ""){
		location.href = urlTabOne;
	}else{
		location.href = urlValue;
	}
});

if ($("#filtroDias").length ){
	$("#filtroDias").ionRangeSlider({
		type:"double",
		min:1,
		max:120,
		from:iniDay,to:endDay
	})
}


$("body").on('click', '.revertJuridico', function(event) {
	event.preventDefault();
	var id 		= $(this).data("quote");
    var customer_id = $(this).data("credit");
	preloader(true);
	$.post(root+controller+"/revertjuridico", {id,customer_id}, function(data, textStatus, xhr) {
		preloader(false);

		location.reload();
		return false;
		// BTN_CLICK.trigger("click");
	});
	//preloader(false);
});



$("body").on('click', '.changeJuridico', function(event) {
	event.preventDefault();
	var id 		= $(this).data("quote");
	var number  = $(this).data("number");
	$("#detail_payment").modal("hide");
	Swal.fire({
	 	title: 'Por favor escribe la razón del cambio',
	  	input: 'textarea',
	  	showCancelButton: true,
	  	confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
	    cancelButtonText: "Cancelar",
	    // onClose: function(){
	    // 	BTN_CLICK.trigger("click");
	    // },
	  	inputValidator: (message) => {
	  		$("#preloader").hide();
		    if (!message) {
		      return 'La razón es necesaría.'
		    }else{
				preloader(true);
		    	$.post(root+"credits_plans/changeJuridico", {id,message}, function(data, textStatus, xhr) {
		    		preloader(false);
		    		location.reload();
		    		return false;
					// BTN_CLICK.trigger("click");
				});
		    }
	  	}
	})
});


$("body").on('submit', '#formPaymentTotal', function(event) {
	event.preventDefault();
	preloader(true);
	  // alert(JSON.stringify(this));
	  	$.post(root+"credits/payment_quotes", $(this).serialize(), function(data, textStatus, xhr) {
    		preloader(false)
    		location.reload();

		});

});



