$("body").on("submit", "#commerceFormData", function (event) {
	event.preventDefault();
	preloader(true);
	$.ajax({
		url: varsJs.APP_URL + "payment_commerce_search",
		type: "POST",
		data: $(this).serialize(),
	})
		.done(function (response) {
			preloader(false);
			if (parseInt(response) == 10) {
				showMessage("Error, el código no existe", true);
				return false;
			}
			var resp = JSON.parse(response);
			var handler = ePayco.checkout.configure(resp.configuration);
			handler.open(resp.datos);
		})
		.fail(function () {
			preloader(false);
			showMessage(
				"Error al realizar la consulta por favor intenta más tarde",
				true
			);
		});
});
