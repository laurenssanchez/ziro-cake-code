$(".viewPaymentDetail").click(function(event) {
	event.preventDefault();
	var url = $(this).attr("href");

	$.post(url, {}, function(data, textStatus, xhr) {
		$("#pagoBody").html(data);
		$("#pagoModal").modal("show");
	});

});

$("body").on('click', '.pendingState', async function(event) {
	event.preventDefault();
	var url = $(this).attr("href");

	const { value: formValues } = await Swal.fire({
	  title: 'Marcar solicitud de pago como pendiente',
	  html:
	    '<form>'+
	    '<label htmlFor="motivoDev"> <b>Razón del cambio</b> </label>'+
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

  	$.post(url, {note: formValues[0]}, function(data, textStatus, xhr) {
		location.reload();
	});
});


$("body").on('click', '.paymentState', async function(event) {
	event.preventDefault();
	var url = $(this).attr("href");

	const { value: formValues } = await Swal.fire({
	  title: 'Marcar solicitud como pagado',
	  html:
	    '<form>'+
	    '<label htmlFor="motivoDev"> <b>Nota del pago</b> </label>'+
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
		showMessage("Error, La nota es requerida.",true);
		return false;
	}

  	$.post(url, {note: formValues[0]}, function(data, textStatus, xhr) {
		location.reload();
	});
});