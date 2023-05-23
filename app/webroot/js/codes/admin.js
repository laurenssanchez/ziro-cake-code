$("body").on('click', '.resendCustomerCode,.resendUserCode', function(event) {
	event.preventDefault();
	$.post($(this).attr("href"), {}, function(data, textStatus, xhr) {
		location.reload();
	});
});