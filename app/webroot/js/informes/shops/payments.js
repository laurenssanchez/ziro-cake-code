if ( document.getElementById("disbursments1_pago") ) {
	document.getElementById("disbursments1_pago").disabled = true;
}
if (document.getElementById("disbursments2_pago")) {
	document.getElementById("disbursments2_pago").disabled = true;
}

$("body").on('click', '.requestBtn', function(event) {
	event.preventDefault();
	var type = $(this).data("payment");
	var disbursmentpago ;
	var porcentual ;
	var iddisbursments;
	if (type == 1) {
		disbursmentpago =  parseInt(document.getElementById("disbursments1_pagotx").value);
		porcentual =  document.getElementById("costmax").value;
		iddisbursments =  document.getElementById("iddisbursments1").value;
	} else {
		disbursmentpago =  parseInt(document.getElementById("disbursments2_pagotx").value);
		porcentual =  document.getElementById("costmin").value;
		iddisbursments =  document.getElementById("iddisbursments2").value;
	}

	$("#preloader").show();
	$.post(actual_url, {type: type, disbursmentpago: disbursmentpago, porcentual: porcentual, iddisbursments: iddisbursments}, function(data, textStatus, xhr) {
		location.reload();
	});
});


$(".selectAll1").change(function(event) {
	var quote = 0 ;
	var iva = 0;
	var debtsTotal = parseInt(document.getElementById("totalOtrosConceptos").value);
	var costmin =  document.getElementById("costmax").value;
	var id ;
	const formatterPeso = new Intl.NumberFormat('es-CO', {
		style: 'currency',
		currency: 'COP',
		minimumFractionDigits: 0
	  });
	var count = 0;
	if($(this).prop("checked")){
		var resume_table = document.getElementById("table1");
		for (var i = 0, row; row = resume_table.rows[i]; i++) {
			for (var j = 0, col; col = row.cells[j]; j++) {
				if (col.children[0].checked) {
					quote = quote + parseInt($(col.children[0]).data("quote"));
					if (count == 0) {
						id = $(col.children[0]).data("iddisbursement");
					}else{
						id = id + ","+ $(col.children[0]).data("iddisbursement");
					}
					count ++;
				}
			}
		}
	}else{
		var resume_table = document.getElementById("table1");
		for (var i = 0, row; row = resume_table.rows[i]; i++) {
			for (var j = 0, col; col = row.cells[j]; j++) {
				
				if (col.children[0].checked) {
					quote = quote + parseInt($(col.children[0]).data("quote"));
					if (count == 0) {
						id = $(col.children[0]).data("iddisbursement");
					}else{
						id = id + ","+ $(col.children[0]).data("iddisbursement");
					}
					count ++;
				}
			}
		}
	}

	var pago = (quote * (costmin/100));
	iva  = (pago * 0.19) + (debtsTotal * 0.19);
	var totalcomision = pago + iva + debtsTotal;
	var totalpago = quote - totalcomision ;

	document.getElementById("iddisbursments1").value =  id;
	document.getElementById("disbursments1_Subtotal").innerHTML = 	formatterPeso.format(quote);
	document.getElementById("disbursments1_descontar").innerHTML = 	formatterPeso.format(totalcomision);
	document.getElementById("disbursments1_iva").innerHTML = "Pago: <span class='pago1Iva pago1'>"+ formatterPeso.format(iva) +"</span>";
	document.getElementById("disbursments1_comision").innerHTML = "Pago: <span class='pago1Iva pago1'>"+ formatterPeso.format(pago) +"</span>";
	document.getElementById("disbursments1_pago").innerHTML = 	formatterPeso.format(totalpago);
	document.getElementById("disbursments1_pago").disabled = (totalpago == 0) ? true : false;
	document.getElementById("disbursments1_pagotx").value = totalpago;
	//window.alert(quote.toString() + " - " + pago.toString()+ " - " + iva.toString());
});

$(".selectAll2").change(function(event) {
	var quote = 0 ;
	var quote = 0 ;
	var iva = 0;
	var debtsTotal = parseInt(document.getElementById("totalOtrosConceptos").value);
	var costmin =  document.getElementById("costmin").value;
	var id ;
	const formatterPeso = new Intl.NumberFormat('es-CO', {
		style: 'currency',
		currency: 'COP',
		minimumFractionDigits: 0
	  });
	var count = 0;
	if($(this).prop("checked")){
		var resume_table = document.getElementById("table2");
		for (var i = 0, row; row = resume_table.rows[i]; i++) {
			for (var j = 0, col; col = row.cells[j]; j++) {
				if (col.children[0].checked) {
					quote = quote + parseInt($(col.children[0]).data("quote"));
					if (count == 0) {
						id = $(col.children[0]).data("iddisbursement");
					}else{
						id = id + ","+ $(col.children[0]).data("iddisbursement");
					}
					count ++;
				}
			}
		}
	}else{
		var resume_table = document.getElementById("table2");
		for (var i = 0, row; row = resume_table.rows[i]; i++) {
			for (var j = 0, col; col = row.cells[j]; j++) {
				
				if (col.children[0].checked) {
					quote = quote + parseInt($(col.children[0]).data("quote"));
					if (count == 0) {
						id = $(col.children[0]).data("iddisbursement");
					}else{
						id = id + ","+ $(col.children[0]).data("iddisbursement");
					}
					count ++;
				}
			}
		}
	}

	var pago = (quote * (costmin/100));
	iva  = (pago * 0.19) + (debtsTotal * 0.19) ;
	var totalcomision = pago + iva + debtsTotal;
	var totalpago = quote - totalcomision;

	document.getElementById("iddisbursments2").value =  id;
	document.getElementById("disbursments2_Subtotal").innerHTML = 	formatterPeso.format(quote);
	document.getElementById("disbursments2_descontar").innerHTML = 	formatterPeso.format(totalcomision);
	document.getElementById("disbursments2_iva").innerHTML = "Pago: <span class='pago2Iva pago2'>"+ formatterPeso.format(iva) +"</span>";
	document.getElementById("disbursments2_comision").innerHTML = "Pago: <span class='pago2Iva pago2'>"+ formatterPeso.format(pago) +"</span>";
	document.getElementById("disbursments2_pago").innerHTML = 	formatterPeso.format(totalpago);
	document.getElementById("disbursments2_pago").disabled = (totalpago == 0) ? true : false;
	document.getElementById("disbursments2_pagotx").value = totalpago;
});