if ($("#filtroCosto").length ){
	$("#filtroCosto").ionRangeSlider({
		type:"double",
		min:0,
		max:100000000,
		from:minValue,to:maxValue,
		step: 1000
	})
}

