var statedebt = $(".valuedebt span").html();
if (statedebt == "$0") {
	$(".valuedebt").html(
		"<span class='text-success'><b>$0 CRÉDITO PAGADO</b></span>"
	);
}
window.Parsley.addValidator(
	"fileextension",
	function (value, requirement) {
		var fileExtension = value.split(".").pop();
		var extenciones_archivos = ["pdf"];
		console.log(extenciones_archivos.indexOf(fileExtension));
		return extenciones_archivos.indexOf(fileExtension) == -1 ? false : true;
	},
	32
).addMessage("es", "fileextension", "La extención del archivo debe ser PDF");

window.Parsley.addValidator(
	"imageextension",
	function (value, requirement) {
		var fileExtension = value.split(".").pop();
		var extenciones_archivos = ["png", "jpg", "jpeg"];
		return extenciones_archivos.indexOf(fileExtension) == -1 ? false : true;
	},
	32
).addMessage(
	"es",
	"imageextension",
	"La extención del archivo debe ser PNG o JPG"
);

$("body").on("click", ".changeState", function (event) {
	event.preventDefault();
	let url = $(this).attr("href");
	Swal.fire({
		title: "Confirmar",
		text: "¿Está seguro de realizar esta acción?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Aceptar",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.value) {
			location.href = url;
		}
	});
});

$("body").on("click", ".photoid1", function (event) {
	var imgt1 = $(".photoAdmin").attr("src");
	$(".imagepreview").attr("src", imgt1);
	$("#imagemodal").modal("show");
});

$("body").on("click", ".photoid2", function (event) {
	var imgt2 = $(".photoCedDel").attr("src");
	$(".imagepreview").attr("src", imgt2);
	$("#imagemodal").modal("show");
});

$("body").on("click", ".photoid3", function (event) {
	var imgt3 = $(".photoCedTras").attr("src");
	$(".imagepreview").attr("src", imgt3);
	$("#imagemodal").modal("show");
});

$("body").on("click", "#creditspayments", function (event) {
	$(".removeelement").remove();
	$(".tablecredits").removeClass("d-none");
});

function showMessage(message, error = false) {
	var type = error ? "danger" : "success";
	var title = error ? "Error" : "Bien";

	$.notify(
		{
			// options
			title: title,
			message: message,
		},
		{
			// settings
			type: type,
			placement: {
				from: "bottom",
				align: "right",
			},
		}
	);
}
$(window).load(function () {
	$("#preloader").delay(100).fadeOut("slow");
});

setTimeout(function () {
	$(".alert").remove();
}, 5000);

function preloader(show = true) {
	if (show) {
		$("#preloader").show();
	} else {
		$("#preloader").hide();
	}
}

var finicialdeclare = new Date();

var year = finicialdeclare.getFullYear();
var month = (finicialdeclare.getMonth() + 1).toString().padStart(2, "0");
var day = finicialdeclare.getDate().toString().padStart(2, "0");
var diasantes = day - 5;
if (diasantes == 0) {
	var diasantes = diasantes + 1;
}
var dateIni = year + "-" + month + "-" + diasantes.toString().padStart(2, "0");
var dateEnd = year + "-" + month + "-" + day;

$("body").on("submit", "#UserLoginForm", function (event) {
	event.preventDefault();
	$("#preloader").show();
	$.post(
		$(this).attr("action"),
		$(this).serialize(),
		function (data, textStatus, xhr) {
			$("#preloader").hide();
			data = $.trim(data);
			console.log(data);
			alert(data);
			// if (data == "6") {
			// 	showMessage(
			// 		"Actualmente tu usuario se encuentra deshabilitado ya que, te encuentras en cobro jurídico. Comunicate con soporte",
			// 		true
			// 	);
			// } else if (data == "4") {
			// 	$("#codeGen,#validateBtn,#reenvioBtn").show();
			// 	$("#sendBtn,.usersDataLog").hide();
			// } else {
			// 	if (data == "0" || data == "1" || data == "5") {
			// 		location.reload();
			// 	} else {
			// 		if (data == "2") {
			// 			location.href = varsJs.APP_URL + "payments";
			// 		} else {
			// 			location.href = varsJs.APP_URL + "pages/newRequest";
			// 		}
			// 	}
			// }
		}
	);
	return false;
});

$("body").on("click", "#validateBtn", function (event) {
	event.preventDefault();
	$("#preloader").show();

	var codigo = $("#UserCode").val();

	if ($.trim(codigo) == "") {
		showMessage("El código es necesario", true);
	} else {
		$.post(
			varsJs.APP_URL + "users/validate_code",
			{ codigo },
			function (data, textStatus, xhr) {
				$("#preloader").hide();
				if ($.trim(data) == "0") {
					showMessage("El código expiro, se envío un nuevo código.", true);
				} else if ($.trim(data) == "2") {
					showMessage("El código no coincide.", true);
				} else if ($.trim(data) == "3") {
					location.href = varsJs.APP_URL + "pages/dashboard";
				} else {
					location.href = varsJs.APP_URL + "payments";
				}
			}
		);
	}
});

$("body").on("click", "#reenvioBtn", function (event) {
	event.preventDefault();
	$("#preloader").show();
	$.post(
		varsJs.APP_URL + "users/send_code",
		{},
		function (data, textStatus, xhr) {
			$("#preloader").hide();
			showMessage("Código enviado correctamente.");
		}
	);
});

if ($("#chart1_dashboard").length) {
	// grafico dashboard izquierda
	var labelsarr = Fmonths;
	var values = Fvalues;

	var maxvaluearr = Math.max.apply(null, values);
	var moremax = 4000000;
	var maxvalue = parseInt(maxvaluearr) + parseInt(moremax);
	var minvalue = 100000;
	var ctx = document.getElementById("chart1_dashboard").getContext("2d");
	var chart = new Chart(ctx, {
		type: "bar",
		data: {
			labels: labelsarr,
			datasets: [
				{
					label: "Valor créditos",
					data: values,
					backgroundColor: Fcolors,
				},
			],
		},
		options: {
			legend: { display: false },
			tooltips: {
				callbacks: {
					label: function (t, d) {
						var xLabel = d.datasets[t.datasetIndex].label;
						var yLabel =
							t.yLabel >= 1000
								? "$" +
								  t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
								: "$" + t.yLabel;
						return xLabel + ": " + yLabel;
					},
				},
			},
			scales: {
				yAxes: [
					{
						ticks: {
							max: maxvalue,
							min: minvalue,
							callback: function (value, index, values) {
								if (parseInt(value) >= 1000) {
									return (
										"$" + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
									);
								} else {
									return "$" + value;
								}
							},
						},
					},
				],
			},
		},
	});
}
