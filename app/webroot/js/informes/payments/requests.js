$("body").on('click', '.pendingRequest', function(event) {
	event.preventDefault();
	var id = $(this).data("request");
	var state = 2;
	var url = $(this).attr("href");
	Swal.fire({
	  title: 'Por favor ingrese la nota para el cambio.',
	  input: 'text',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
	  inputValidator: (reason) => {
	  	$("#preloader").hide();
	    if (!reason) {
	      return 'Nota para el cambio es necesarío.'
	    }else{
			$("#preloader").show();
	    	$.post(url, {reason}, function(data, textStatus, xhr) {
	    		location.reload();
			});
	    }
	  }
	})
});