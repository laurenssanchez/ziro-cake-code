$("body").on('click', '.requestBtn', function(event) {
	event.preventDefault();
	var type = $(this).data("payment");
	$("#preloader").show();
	$.post(actual_url, {type: type}, function(data, textStatus, xhr) {
		location.reload();
	});
});