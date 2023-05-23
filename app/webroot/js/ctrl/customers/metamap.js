// variable global para verificar la posicion del step en el formulario CustomerRegisterStepUniqueForm
var positionStep = 0;
var accessTokenMeta = "";
var verificationIdMeta = "";
var verificationIdentifyMeta = "";
var registroMetamap = false;
var infoUsuarioMetamap = false;
var tipoDispositivoUsado = "";
var pasosFotos = 1;
var tamañoImagenWidth = "";
var tamañoImagenHeigth = "";
var verificacionFotoCCFrontal = false;
var verificacionFotoCCReversa = false;

$('input[type="checkbox"]').on("change", function (e) {
	if (e.target.checked) {
		$("#autorizacion-modal").modal();
	}
});

FORMULARIO_VALIDO = false;
$("#CustomerRegisterStepOneForm").submit(function (event) {
	event.preventDefault();
});

// $("#CustomerRegisterStepUniqueForm").submit(function (event) {
// 	event.preventDefault();
// });

if (!$("#CustomerCrediventasForm").length) {
	$(
		"#CustomerRegisterStepOneForm,#CustomerNormalRequestForm,#CustomerNormalRequestUniqueForm,#CustomerRegisterStepUniqueForm"
	)
		.parsley()
		.on("form:validate", function (formInstance) {
			console.log(formInstance.isValid());
			if (formInstance.isValid() != false) {
				FORMULARIO_VALIDO = true;
			} else {
				FORMULARIO_VALIDO = false;
			}
		});
}

$("#CustomerRegisterStepOneForm").submit(function (event) {
	event.preventDefault();

	if (!FORMULARIO_VALIDO) {
		return false;
	}
	var document_file_up = $("#CustomerDocumentFileUp2").val();
	var document_file_down = $("#CustomerDocumentFileDown2").val();
	var image_file = $("#CustomerImageFile2").val();

	var CustomerCode = $("#CustomerCode").val();
	var CustomerEmail = $("#CustomerEmail").val();
	var CustomerIdentification = $("#CustomerIdentification").val();

	if (
		(!VIDEO_DATA ||
			document_file_up == "" ||
			document_file_down == "" ||
			image_file == "") &&
		!$("#CustomerId").length
	) {
		// showMessage(
		// 	"Todos los campos son requeridos y se deben tomar las fotos",
		// 	true
		// );
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Todos los campos son requeridos y se deben tomar las fotos",
		});
	} else {
		var form = $("#CustomerRegisterStepOneForm")[0];
		var formData = new FormData(form);

		if (!$("#CustomerId").length) {
			formData.append(
				"data[Customer][document_file_up]",
				b64toBlob(document_file_up)
			);
			formData.append(
				"data[Customer][document_file_down]",
				b64toBlob(document_file_down)
			);
			formData.append("data[Customer][image_file]", b64toBlob(image_file));
		}

		$("#preloader").show();

		$.ajax({
			type: "POST",
			url: $("#CustomerRegisterStepOneForm").attr("action"),
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			success: function (response) {
				$("#preloader").hide();
				if ($.trim(response) != "register_step_two") {
					// showMessage(response, true);
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response,
					});
				} else {
					location.href = root + "pages/" + response;
				}
			},
			error: function (e) {
				console.log(e);
			},
		});
	}
});

$("#CustomerRegisterStepUniqueFormfdfdf").submit(function (event) {
	event.preventDefault();

	var image_file = $("#CustomerImageFile2").val();

	console.log(FORMULARIO_VALIDO);

	if (!FORMULARIO_VALIDO && positionStep < 6) {
		return false;
	} else {
		var form = $("#CustomerRegisterStepUniqueForm")[0];
		var formData = new FormData(form);
		if (!$("#CustomerId").length) {
			// formData.append(
			// 	"data[Customer][document_file_up]",
			// 	b64toBlob(document_file_up)
			// );
			// formData.append(
			// 	"data[Customer][document_file_down]",
			// 	b64toBlob(document_file_down)
			// );
			//formData.append("data[Customer][image_file]", b64toBlob(image_file));
		}

		$("#preloader").show();

		$.ajax({
			type: "POST",
			url: $("#CustomerRegisterStepUniqueForm").attr("action"),
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			success: function (response) {
				$("#preloader").hide();
				if ($.trim(response) != "dashboardcliente") {
					// showMessage(response, true);
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response,
					});
				} else {
					location.href = root + "pages/" + response;
				}
			},
			error: function (e) {
				console.log(e);
			},
		});
	}
});

function b64toBlobMetamap(b64Data, contentType, sliceSize) {
	contentType = contentType || "";
	sliceSize = sliceSize || 512;
	console.log(b64Data);
	var byteCharacters = atob(b64Data);
	var byteArrays = [];

	for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
		var slice = byteCharacters.slice(offset, offset + sliceSize);

		var byteNumbers = new Array(slice.length);
		for (var i = 0; i < slice.length; i++) {
			byteNumbers[i] = slice.charCodeAt(i);
		}

		var byteArray = new Uint8Array(byteNumbers);

		byteArrays.push(byteArray);
	}

	var blob = new Blob(byteArrays, { type: contentType });
	return blob;
}

/**
 * funcion siguiente en el step
 * @param String name
 * @return String
 */
function funcionSiguiente() {
	console.log("positionStep", positionStep);
	document_file_upresizeBase64Img = "";
	document_file_downResizeBase64Img = "";
	image_fileResizeBase64Img = "";

	const fotoFrontalFile = document.getElementById("fotoFrontal");
	fotoFrontalDocumento = fotoFrontalFile.files[0];

	const fotoInversaFile = document.getElementById("fotoReverso");
	fotoInversaDocumento = fotoInversaFile.files[0];

	if (positionStep === 5) {
		$(".jsSiguiente").hide();
		$("#guardarFormulario").show();
	}
	if (positionStep === 4) {
		paso = false;
		if ($("#priceValue").val() < 200000 || $("#priceValue").val() > 10000000) {
			// showMessage(`Debe solicitar un cupo entre 200.000 a 10.000.000`, true);
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "Debe solicitar un cupo entre 200.000 a 10.000.000",
			});
			paso = false;
		} else {
			paso = true;
		}
		if (
			paso &&
			$("#CustomerNit").parsley().validate() === true &&
			$("#CustomerBussName").parsley().validate() === true &&
			$("#CustomersAddressAddressStreet").parsley().validate() === true
		) {
			positionStep = 5;
			$("#step4").hide();
			$("#step5").show();
			$(".jsSiguiente").hide();
			$("#guardarFormulario").show();
		}
	}

	if (positionStep === 3) {
		if (
			$("#CustomersPhone1PhoneNumber").parsley().validate() === true &&
			$("#CustomerEmail").parsley().validate() === true &&
			$("#CustomerPassword").parsley().validate() === true &&
			$("#CustomersAddressAddress").parsley().validate() === true
		) {
			const email = $("#CustomerEmail").val();
			$("#preloader").show();
			$.ajax({
				type: "POST",
				url: "/pages/validar-correo-usuario",
				data: { email: email },
				success: function (response) {
					$("#preloader").hide();
					if (response == 0) {
						$("#preloader").show();
						$.ajax({
							type: "POST",
							url: "/pages/validar-correo-cliente",
							data: { email: email },
							success: function (response) {
								$("#preloader").hide();
								if (response == 0) {
									positionStep = 4;
									$("#step3").hide();
									$("#step4").show();
									$(".bl-tituloStep").html("DATOS DE TU NEGOCIO");
									$(".js-iconStep").html(
										'<i class="fa fa-briefcase" aria-hidden="true"></i>'
									);
								} else if (response == 2) {
									$("#CustomerEmail").val("");
									// showMessage(`Debe ingresar un correo válido`, true);
									Swal.fire({
										icon: "error",
										title: "Error",
										text: "Debe ingresar un correo válido",
									});
									return false;
								} else {
									Swal.fire({
										icon: "error",
										title: "Error",
										text: `el cliente con email ${email} ya se encuentra registrado.`,
									});
									// showMessage(
									// 	`el cliente con email ${email} ya se encuentra registrado.`,
									// 	true
									// );
									return false;
								}
							},
							error: function (e) {
								console.log(e);
							},
						});
					} else if (response == 2) {
						$("#CustomerEmail").val("");
						// showMessage(`Debe ingresar un correo válido`, true);
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `Debe ingresar un correo válido.`,
						});
						return false;
					} else {
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `el usuario con email ${email} ya se encuentra registrado.`,
						});
						// showMessage(
						// 	`el usuario con email ${email} ya se encuentra registrado.`,
						// 	true
						// );

						return false;
					}
				},
				error: function (e) {
					console.log(e);
				},
			});
		}
	}

	if (positionStep === 2) {
		const cedulaCliente = $("#CustomerIdentification").val();
		if (
			$("#CustomerName").parsley().validate() === true &&
			$("#CustomerLastName").parsley().validate() === true &&
			$("#CustomerIdentification").parsley().validate() === true
		) {
			$("#preloader").show();
			$.ajax({
				type: "POST",
				url: "/pages/validar-cedula-cliente",
				data: { identification: cedulaCliente },
				success: function (response) {
					$("#preloader").hide();
					if (response == 0) {
						positionStep = 3;
						$("#step2").hide();
						$("#step3").show();
						$(".bl-tituloStep").html("DATOS DE CONTACTO");
						$(".js-iconStep").html(
							'<i class="fa fa-address-book" aria-hidden="true"></i>'
						);
					} else if (response == 2) {
						$("#CustomerIdentification").val("");
						// showMessage(`Debe ingresar una cedula valida`, true);
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `Debe ingresar una cedula valida.`,
						});
						return false;
					} else {
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `La cedula ${cedulaCliente} ya se encuentra registrada.`,
						});
						// showMessage(
						// 	`La cedula ${cedulaCliente} ya se encuentra registrada.`,
						// 	true
						// );

						return false;
					}
				},
				error: function (e) {
					console.log(e);
				},
			});
		}
	}

	if (positionStep === 1) {
		customerCode = $("#CustomerCode").val();
		if ($("#CustomerCode").parsley().validate() === true) {
			$("#preloader").show();
			$.ajax({
				type: "POST",
				url: $("#rutaValidarCodigo").val(),
				data: { customerCode: customerCode },
				success: function (response) {
					if (response == 0) {
						$("#preloader").hide();
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `El codigo ${customerCode} no existe en nuestros registros.`,
						});
						// showMessage(
						// 	`El codigo ${customerCode} no existe en nuestros registros.`,
						// 	true
						// );
						return false;
					} else if (response == 2) {
						$("#preloader").hide();
						$("#CustomerCode").val("");
						// showMessage(`Debe ingresar un código valido`, true);
						Swal.fire({
							icon: "error",
							title: "Error",
							text: `Debe ingresar un código valido.`,
						});
						return false;
					} else {
						positionStep = 2;

						setTimeout(function () {
							$("#preloader").hide();
							$("#step1").hide();
							$("#step2").show();
							$(".jsVolverAtras").show();
							$(".bl-tituloStep").html("DATOS PERSONALES");
							$(".js-iconStep").html(
								'<i class="fa fa-user " aria-hidden="true"></i>'
							);
							// Valida que ya se ha llamado al servicio una vez y obtuvo todos los datos del usuario
							if (infoUsuarioMetamap === false) {
								registroMetamapObtenerDatosDocumento();
							}
						}, 3000);
					}
				},
				error: function (e) {
					console.log(e);
				},
			});
		}
	}
	if (positionStep === 0) {
		// valida si esta usando un dispositivo movil
		if (window.innerWidth < 1200) {
			tipoDispositivoUsado = "movil";
			console.log("Estás usando un dispositivo móvil!!");
			registroMetamapMovil();
		} else {
			// codigo para computador
			tipoDispositivoUsado = "escritorio";
			registroMetamapDesktop();
		}
	}
}

function procesarErroresMetamap($respuesta, tipoFoto) {
	switch ($respuesta) {
		case "documentPhoto.badText":
			return tipoFoto + ": La validación del campo del documento falló";
		case "documentPhoto.blurryText":
			return (
				tipoFoto +
				": La foto del documento es demasiado borrosa, toma nuevamente la foto"
			);
		case "documentPhoto.smallImageSize":
			return (
				tipoFoto +
				": La resolución de la foto del documento es demasiado baja, toma nuevamente la foto"
			);
		case "documentPhoto.unexpectedData":
			return (
				tipoFoto +
				": inesperado en la lectura del documento, toma nuevamente la foto"
			);
		case "documentPhoto.noText":
			return (
				tipoFoto +
				": La foto del documento no tiene texto, toma nuevamente la foto"
			);
		case "selfiePhoto.noFace":
			return tipoFoto + ": La foto no tiene rostro toma nuevamente la foto";
		case "documentPhoto.noFace":
			return (
				tipoFoto +
				": Toma nuevamente la foto y verifica que sea la parte frontal de tu documento"
			);
		case "documentPhoto.grayscaleImage":
			return (
				tipoFoto +
				": La foto del documento está en escala de grises, toma nuevamente la foto"
			);
		case "documentPhoto.screenPhoto":
			return (
				tipoFoto +
				": La foto del documento es una captura de pantalla. El usuario debe subir una foto diferente"
			);
		case "documentPhoto.noDocument":
			return (
				tipoFoto +
				": La foto del documento no coincide con una plantilla de documento conocida"
			);
		case "documentPhoto.missingFields":
			return (
				tipoFoto +
				": A la foto del documento le faltan algunos campos obligatorios"
			);
		case "documentPhoto.wrongFormat":
			return (
				tipoFoto +
				": Algunos campos obligatorios del documento utilizan un formato no válido"
			);
		case "documentPhoto.noMrz":
			return (
				tipoFoto +
				": La foto del documento no tiene una zona legible por máquina (MRZ, para los pasos de validación que la requieren)"
			);
		case "documentPhoto.badMrz":
			return (
				tipoFoto +
				": La foto del documento ha dañado la zona legible por máquina (MRZ, para pasos de validación que lo requieran)"
			);
		case "documentPhoto.noPdf417":
			return (
				tipoFoto +
				": La foto del documento no tiene código de barras PDF417 (para los pasos de validación que lo requieren)"
			);
		case "documentPhoto.badPdf417":
			return (
				tipoFoto +
				": La foto del documento tiene el código de barras PDF417 dañado (para los pasos de validación que lo requieren)"
			);
		case "documentPhoto.typeMismatch":
			return (
				tipoFoto +
				": El tipo de documento reclamado por el usuario y el tipo de documento detectado en la foto son diferentes"
			);
		case "documentPhoto.countryMismatch":
			return (
				tipoFoto +
				": El país del documento reclamado por el usuario y el país del documento detectado a partir de la foto del documento son diferentes"
			);
		default:
			return (
				tipoFoto +
				": ha ocurrido un error con la foto, por favor intente tomarla de nuevo"
			);
	}
}

/**
 * Funcion oauth
 */
function registroMetamapOauth() {
	$("#preloader").show();
	const settings = {
		async: true,
		crossDomain: true,
		url: "https://api.getmati.com/oauth",
		method: "POST",
		headers: {
			accept: "application/json",
			"Content-Type": "application/x-www-form-urlencoded",
			authorization:
				"Basic NjMwZmM0ZDQ3NzM0ZjYwMDFjOTY5NzdiOkxWVUNPRDY3UUNESFVNNEFQVlU3QVE1Nk1PWklYQ1hM",
		},
		data: {
			grant_type: "client_credentials",
		},
	};

	$.ajax(settings).done(function (response) {
		console.log(response);
		console.log(response.payload.user._id);

		accessTokenMeta = response.access_token;
		registroMetamapVerificacion();
	});
}

/**
 * Proceso verificacion de cuenta
 */

function registroMetamapVerificacion() {
	const settings = {
		async: true,
		crossDomain: true,
		url: "https://api.getmati.com/v2/verifications/",
		method: "POST",
		headers: {
			accept: "application/json",
			"content-type": "application/json",
			authorization: "Bearer " + accessTokenMeta,
		},
		processData: false,
		data: '{"metadata":{"user-defined-1":"ziro","user-defined-2":"12345"},"flowId":"6376ae47b2c008001c4277d7"}',
	};

	$.ajax(settings).done(function (responseVerificacion) {
		console.log(responseVerificacion);
		console.log(responseVerificacion.identity);

		verificationIdMeta = responseVerificacion.id;
		verificationIdentifyMeta = responseVerificacion.identity;
		if (tipoDispositivoUsado === "escritorio") {
			validarFotoCedulaFrontalDesktopMetamap();
		} else if (tipoDispositivoUsado === "movil") {
			validarFotoCedulaFrontalMovilMetamap();
		}
	});
}

/**
 * Proceso de verificacion Foto cedula frontal modo escritorio
 */
const validarFotoCedulaFrontalDesktopMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);
	// validacion usada cuando esta en escritorio
	if (tipoDispositivoUsado === "escritorio") {
		await redimensionarFotoParaEscritorio();

		console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);

		var document_file_up = $("#CustomerDocumentFileUp2").val();

		resizedataURL(document_file_up, tamañoImagenWidth, tamañoImagenHeigth).then(
			(resized) => {
				// document.body.append("After: ");
				// let img = document.createElement("img");
				// img.src = resized;
				// console.log(resized.length);
				// console.log(resized);
				$("#CustomerDocumentFileUp2").val(resized);
				// document.body.appendChild(img);
				// document_file_upResizeBase64Img = resized;
			}
		);

		var newDocument_file_up = $("#CustomerDocumentFileUp2").val();
		document_file_upBase64toFile = dataBase64URLtoFile(
			newDocument_file_up,
			"fotoRegistro.png"
		);

		imagenNameFront = document_file_upBase64toFile["name"];

		const formDocuments = new FormData();

		formDocuments.append(
			"inputs",
			'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"front",\n           "filename": "' +
				imagenNameFront +
				'"\n           }\n        }   ]'
		);
		formDocuments.append("document", document_file_upBase64toFile);

		const options = {
			method: "POST",
			headers: myHeaders,
		};

		options.body = formDocuments;

		registroMetamapSendInput(options);
	}
};

/**
 * Proceso de verificacion Foto cedula Reverso modo escritorio
 */
const validarFotoCedulaReversoDesktopMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);
	// validacion usada cuando esta en escritorio
	if (tipoDispositivoUsado === "escritorio") {
		await redimensionarFotoParaEscritorio();

		console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);

		var document_file_down = $("#CustomerDocumentFileDown2").val();
		resizedataURL(
			document_file_down,
			tamañoImagenWidth,
			tamañoImagenHeigth
		).then((resized) => {
			// document.body.append("After: ");
			// let img = document.createElement("img");
			// img.src = resized;
			// console.log(resized.length);
			// console.log(resized);
			$("#CustomerDocumentFileDown2").val(resized);
			// document.body.appendChild(img);
			// document_file_upResizeBase64Img = resized;
		});

		var newDocument_file_down = $("#CustomerDocumentFileDown2").val();

		document_file_downBase64toFile = dataBase64URLtoFile(
			newDocument_file_down,
			"fotoRegistro.png"
		);

		imagenNameBack = document_file_downBase64toFile["name"];

		const formDocuments = new FormData();

		formDocuments.append(
			"inputs",
			'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"back",\n           "filename": "' +
				imagenNameBack +
				'"\n           }\n        }   ]'
		);
		formDocuments.append("document", document_file_downBase64toFile);

		const options = {
			method: "POST",
			headers: myHeaders,
		};

		options.body = formDocuments;

		registroMetamapSendInput(options);
	}
};

/**
 * Proceso de verificacion Foto cedula Reverso modo escritorio
 */
const validarFotoSelfieDesktopMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);
	// validacion usada cuando esta en escritorio
	if (tipoDispositivoUsado === "escritorio") {
		await redimensionarFotoParaEscritorio();

		console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);

		var image_file = $("#CustomerImageFile2").val();

		resizedataURL(image_file, tamañoImagenWidth, tamañoImagenHeigth).then(
			(resized) => {
				// document.body.append("After: ");
				// let img = document.createElement("img");
				// img.src = resized;
				// console.log(resized.length);
				// console.log(resized);
				$("#CustomerImageFile2").val(resized);
				// document.body.appendChild(img);
				// document_file_upResizeBase64Img = resized;
			}
		);
		var newImage_file = $("#CustomerImageFile2").val();

		image_fileBase64toFile = dataBase64URLtoFile(
			newImage_file,
			"fotoRegistro.png"
		);

		imagenNameSelfie = image_fileBase64toFile["name"];

		const formDocuments = new FormData();

		formDocuments.append(
			"inputs",
			'[ \n    {"inputType":"selfie-photo",\n    "data":{\n "type": "selfie-photo",\n "filename": "' +
				imagenNameSelfie +
				'"\n           }\n        }   ]'
		);
		formDocuments.append("selfie", image_fileBase64toFile);

		const options = {
			method: "POST",
			headers: myHeaders,
		};

		options.body = formDocuments;

		registroMetamapSendInput(options);
	}
};

const redimensionarFotoParaEscritorio = async () => {
	// obtiene el ancho y alto de la foto
	tamañoImagenWidth = $("#canvasFotoUp").width();
	tamañoImagenHeigth = $("#canvasFotoUp").height();

	// se valida solo con uno de los tamaños de la imagen para que sea proporcional la escala de la imagen
	console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);
	if (tamañoImagenHeigth < 150) {
		tamañoImagenHeigth = tamañoImagenHeigth * 5;
		tamañoImagenWidth = tamañoImagenWidth * 5;
	} else if (tamañoImagenHeigth >= 150 && tamañoImagenHeigth < 180) {
		tamañoImagenHeigth = tamañoImagenHeigth * 4;
		tamañoImagenWidth = tamañoImagenWidth * 4;
	} else if (tamañoImagenHeigth >= 180 && tamañoImagenHeigth < 200) {
		tamañoImagenHeigth = tamañoImagenHeigth * 3.4;
		tamañoImagenWidth = tamañoImagenWidth * 3.4;
	} else if (tamañoImagenHeigth >= 200 && tamañoImagenHeigth < 250) {
		tamañoImagenHeigth = tamañoImagenHeigth * 3;
		tamañoImagenWidth = tamañoImagenWidth * 3;
	} else if (tamañoImagenHeigth >= 250 && tamañoImagenHeigth < 350) {
		tamañoImagenHeigth = tamañoImagenHeigth * 2.5;
		tamañoImagenWidth = tamañoImagenWidth * 2.5;
	} else if (tamañoImagenHeigth >= 350 && tamañoImagenHeigth < 450) {
		tamañoImagenHeigth = tamañoImagenHeigth * 2;
		tamañoImagenWidth = tamañoImagenWidth * 2;
	} else if (tamañoImagenHeigth >= 450 && tamañoImagenHeigth < 600) {
		tamañoImagenHeigth = tamañoImagenHeigth * 1.5;
		tamañoImagenWidth = tamañoImagenWidth * 1.5;
	}
};

/**
 * Proceso de verificacion Foto cedula frontal modo MOVIL
 */
const validarFotoCedulaFrontalMovilMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);

	//
	const fileInput = document.getElementById("fotoFrontal");
	const newFileFrontal = new File(
		[fileInput.files[0]],
		"frontal" + fileInput.files[0]["name"],
		{ type: fileInput.files[0]["type"] }
	);

	fotoFrontal = newFileFrontal;
	fotoFrontalName = newFileFrontal.name;

	const formDocuments = new FormData();

	formDocuments.append(
		"inputs",
		'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"front",\n           "filename": "' +
			fotoFrontalName +
			'"\n           }\n        }   ]'
	);
	formDocuments.append("document", newFileFrontal);

	const options = {
		method: "POST",
		headers: myHeaders,
	};

	options.body = formDocuments;

	registroMetamapSendInput(options);
};

/**
 * Proceso de verificacion Foto cedula Reverso modo MOVIL
 */
const validarFotoCedulaReversoMovilMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);

	const fileInputReverso = document.getElementById("fotoReverso");
	const newFileFotoReverso = new File(
		[fileInputReverso.files[0]],
		"reverso" + fileInputReverso.files[0]["name"],
		{ type: fileInputReverso.files[0]["type"] }
	);
	fotoReverso = newFileFotoReverso;
	fotoReversoName = newFileFotoReverso.name;

	const formDocuments = new FormData();
	formDocuments.append(
		"inputs",
		'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"back",\n           "filename": "' +
			fotoReversoName +
			'"\n           }\n        }   ]'
	);
	formDocuments.append("document", newFileFotoReverso);

	const options = {
		method: "POST",
		headers: myHeaders,
	};

	options.body = formDocuments;

	registroMetamapSendInput(options);
};

/**
 * Proceso de verificacion Foto Selfie modo MOVIL
 */
const validarFotoSelfieMovilMetamap = async () => {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);

	const fileInputFotoSelfie = document.getElementById("fotoSelfie");
	const newFileFotoSelfie = new File(
		[fileInputFotoSelfie.files[0]],
		"selfie" + fileInputFotoSelfie.files[0]["name"],
		{ type: fileInputFotoSelfie.files[0]["type"] }
	);
	fotoSelfieFile = newFileFotoSelfie;
	fotoSelfieFileName = newFileFotoSelfie.name;

	const formDocuments = new FormData();

	formDocuments.append(
		"inputs",
		'[ \n    {"inputType":"selfie-photo",\n    "data":{\n "type": "selfie-photo",\n "filename": "' +
			fotoSelfieFileName +
			'"\n           }\n        }   ]'
	);
	formDocuments.append("selfie", newFileFotoSelfie);

	const options = {
		method: "POST",
		headers: myHeaders,
	};

	options.body = formDocuments;

	registroMetamapSendInput(options);
};

/**
 * Proceso de verificacion de con las 3 fotos al tiempo
 */
function validarFotosParaMetamap() {
	var myHeaders = new Headers();
	myHeaders.append("accept", "application/json");
	myHeaders.append("Authorization", "Bearer " + accessTokenMeta);

	// validacion usada cuando esta en escritorio
	if (tipoDispositivoUsado === "escritorio") {
		tamañoImagenWidth = $("#canvasFotoUp").width();
		tamañoImagenHeigth = $("#canvasFotoUp").height();

		// alert(tamañoImagenHeigth);
		// alert(tamañoImagenWidth);

		// se valida solo con uno de los tamaños de la imagen para que sea proporcional la escala de la imagen
		console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);
		if (tamañoImagenHeigth < 150) {
			tamañoImagenHeigth = tamañoImagenHeigth * 5;
			tamañoImagenWidth = tamañoImagenWidth * 5;
		} else if (tamañoImagenHeigth >= 150 && tamañoImagenHeigth < 180) {
			tamañoImagenHeigth = tamañoImagenHeigth * 4;
			tamañoImagenWidth = tamañoImagenWidth * 4;
		} else if (tamañoImagenHeigth >= 180 && tamañoImagenHeigth < 200) {
			tamañoImagenHeigth = tamañoImagenHeigth * 3.4;
			tamañoImagenWidth = tamañoImagenWidth * 3.4;
		} else if (tamañoImagenHeigth >= 200 && tamañoImagenHeigth < 250) {
			tamañoImagenHeigth = tamañoImagenHeigth * 3;
			tamañoImagenWidth = tamañoImagenWidth * 3;
		} else if (tamañoImagenHeigth >= 250 && tamañoImagenHeigth < 350) {
			tamañoImagenHeigth = tamañoImagenHeigth * 2.5;
			tamañoImagenWidth = tamañoImagenWidth * 2.5;
		} else if (tamañoImagenHeigth >= 350 && tamañoImagenHeigth < 450) {
			tamañoImagenHeigth = tamañoImagenHeigth * 2;
			tamañoImagenWidth = tamañoImagenWidth * 2;
		} else if (tamañoImagenHeigth >= 450 && tamañoImagenHeigth < 600) {
			tamañoImagenHeigth = tamañoImagenHeigth * 1.5;
			tamañoImagenWidth = tamañoImagenWidth * 1.5;
		}

		console.log(tamañoImagenWidth, " - ", tamañoImagenHeigth);

		var document_file_up = $("#CustomerDocumentFileUp2").val();
		var document_file_down = $("#CustomerDocumentFileDown2").val();
		var image_file = $("#CustomerImageFile2").val();

		resizedataURL(document_file_up, tamañoImagenWidth, tamañoImagenHeigth).then(
			(resized) => {
				// document.body.append("After: ");
				// let img = document.createElement("img");
				// img.src = resized;
				// console.log(resized.length);
				// console.log(resized);
				$("#CustomerDocumentFileUp2").val(resized);
				// document.body.appendChild(img);
				// document_file_upResizeBase64Img = resized;
			}
		);
		resizedataURL(
			document_file_down,
			tamañoImagenWidth,
			tamañoImagenHeigth
		).then((resized) => {
			// document.body.append("After: ");
			// let img = document.createElement("img");
			// img.src = resized;
			// console.log(resized.length);
			// console.log(resized);
			$("#CustomerDocumentFileDown2").val(resized);
			// document.body.appendChild(img);
			// document_file_upResizeBase64Img = resized;
		});
		resizedataURL(image_file, tamañoImagenWidth, tamañoImagenHeigth).then(
			(resized) => {
				// document.body.append("After: ");
				// let img = document.createElement("img");
				// img.src = resized;
				// console.log(resized.length);
				// console.log(resized);
				$("#CustomerImageFile2").val(resized);
				// document.body.appendChild(img);
				// document_file_upResizeBase64Img = resized;
			}
		);
		var newDocument_file_up = $("#CustomerDocumentFileUp2").val();
		var newDocument_file_down = $("#CustomerDocumentFileDown2").val();
		var newImage_file = $("#CustomerImageFile2").val();

		document_file_upBase64toFile = dataBase64URLtoFile(
			newDocument_file_up,
			"fotoRegistro.png"
		);
		document_file_downBase64toFile = dataBase64URLtoFile(
			newDocument_file_down,
			"fotoRegistro.png"
		);
		image_fileBase64toFile = dataBase64URLtoFile(
			newImage_file,
			"fotoRegistro.png"
		);

		imagenNameFront = document_file_upBase64toFile["name"];
		imagenNameBack = document_file_downBase64toFile["name"];
		imagenNameSelfie = image_fileBase64toFile["name"];

		console.log(document_file_upBase64toFile["name"]);

		const formDocuments = new FormData();

		formDocuments.append(
			"inputs",
			'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"front",\n           "filename": "' +
				imagenNameFront +
				'"\n           }\n        }, \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"back",\n           "filename":"' +
				imagenNameBack +
				'"\n           }\n        } ,\n   {\n    "inputType": "selfie-photo",\n    "data": {\n      "type": "selfie-photo",\n      "filename": "' +
				imagenNameSelfie +
				'"\n    }\n  }\n    ]'
		);
		formDocuments.append("document", document_file_upBase64toFile);
		formDocuments.append("document", document_file_downBase64toFile);
		formDocuments.append("selfie", image_fileBase64toFile);

		const options = {
			method: "POST",
			headers: myHeaders,
		};

		options.body = formDocuments;

		registroMetamapSendInput(options);
		// fin si es escritorio
	} else {
		var myHeaders = new Headers();
		myHeaders.append("accept", "application/json");
		myHeaders.append("Authorization", "Bearer " + accessTokenMeta);

		//
		const fileInput = document.getElementById("fotoFrontal");
		const newFileFrontal = new File(
			[fileInput.files[0]],
			"frontal" + fileInput.files[0]["name"],
			{ type: fileInput.files[0]["type"] }
		);

		fotoFrontal = newFileFrontal;
		fotoFrontalName = newFileFrontal.name;

		const fileInputReverso = document.getElementById("fotoReverso");
		const newFileFotoReverso = new File(
			[fileInputReverso.files[0]],
			"reverso" + fileInputReverso.files[0]["name"],
			{ type: fileInputReverso.files[0]["type"] }
		);
		fotoReverso = newFileFotoReverso;
		fotoReversoName = newFileFotoReverso.name;
		//

		const fileInputFotoSelfie = document.getElementById("fotoSelfie");
		const newFileFotoSelfie = new File(
			[fileInputFotoSelfie.files[0]],
			"selfie" + fileInputFotoSelfie.files[0]["name"],
			{ type: fileInputFotoSelfie.files[0]["type"] }
		);
		fotoSelfieFile = newFileFotoSelfie;
		fotoSelfieFileName = newFileFotoSelfie.name;

		const formDocuments = new FormData();

		formDocuments.append(
			"inputs",
			'[ \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"front",\n           "filename": "' +
				fotoFrontalName +
				'"\n           }\n        }, \n    {"inputType":"document-photo","group":0, \n        "data":{\n           "type":"national-id",\n           "country":"CO",\n           "region":"",\n           "page":"back",\n           "filename":"' +
				fotoReversoName +
				'"\n           }\n        } ,\n   {\n    "inputType": "selfie-photo",\n    "data": {\n      "type": "selfie-photo",\n      "filename": "' +
				fotoSelfieFileName +
				'"\n    }\n  }\n    ]'
		);
		formDocuments.append("document", newFileFrontal);
		formDocuments.append("document", newFileFotoReverso);
		formDocuments.append("selfie", newFileFotoSelfie);

		const options = {
			method: "POST",
			headers: myHeaders,
		};

		options.body = formDocuments;
		registroMetamapSendInput(options);
	}
}


/**
 * Proceso verificacion send input envio de fotos movil y escritorio
 */
function registroMetamapSendInput(options) {
	fetch(
		"https://api.getmati.com/v2/identities/" +
			verificationIdentifyMeta +
			"/send-input",
		options
	)
		.then((responseInputs) => responseInputs.json())
		.then((responseInputs) => {
			$("#preloader").hide();
			console.log(responseInputs);
			console.log(responseInputs.length);
			//alert(JSON.stringify(responseInputs));
			arrayError = [];
			for (let index = 0; index < responseInputs.length; index++) {
				console.log(index);
				if (responseInputs[index].error) {
					var tipoFoto =
						pasosFotos === 1
							? "Foto cédula frontal"
							: pasosFotos === 2
							? "Foto cédula reverso"
							: "Foto selfie";
					console.log("tipoFoto", tipoFoto);
					const obtenerErrorMetamap = procesarErroresMetamap(
						responseInputs[index].error.code,
						tipoFoto
					);
					arrayError.push(`<li>${obtenerErrorMetamap}</li>`);
					//$(".js-response3").append(JSON.stringify(responseInputs[index]));
				}
			}

			if (arrayError.length > 0) {
				// showMessage(arrayError, true);
				Swal.fire({
					html: arrayError,
					icon: "error",
					// title: 'Error',
				});
			}

			console.log(responseInputs[0].result);
			console.log("<<<<<<<<paso vigente fotos>>>>>>>>", pasosFotos);
			if (pasosFotos === 1 || pasosFotos === 2) {
				if (responseInputs[0].result === true) {
					$(`#step0Paso${pasosFotos}`).hide();
					verificacionFotoCCFrontal = true;
					if (pasosFotos === 2) {
						verificacionFotoCCReversa = true;
					}
					pasosFotos = pasosFotos + 1;
					$(`#step0Paso${pasosFotos}`).show();
					$(".jsVolverAtras").show();
					console.log("<<<<<<<<paso vigente fotos>>>>>>>>", pasosFotos);
				}
			} else if (pasosFotos === 3) {
				if (responseInputs[0].result === true) {
					$("#step0Paso3").hide();
					$("#step1").show();
					$(".jsVolverAtras").show();
					positionStep = 1;
					registroMetamap = true;
				}
			}
		})
		.catch((response) => {
			$("#preloader").hide();
			console.log(response.status, response.statusText);
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "Ha ocurrido un error al procesar las fotos, por favor intenta de nuevo pulsando el boton siguiente y si esto no funciona recarga la pagina",
			});
			// showMessage(
			// 	"Ha ocurrido un error al procesar las fotos, por favor intenta de nuevo o recarga la página",
			// 	true
			// );
		});
}
// obtiene toda la informacion del documento
function registroMetamapObtenerDatosDocumento() {
	var settings = {
		url: "https://api.getmati.com/v2/verifications/" + verificationIdMeta + "",
		method: "GET",
		timeout: 0,
		headers: {
			Authorization: "Bearer " + accessTokenMeta,
		},
	};

	$.ajax(settings).done(function (responsehookVerification) {
		$("#preloader").hide();
		console.log(responsehookVerification);
		var dataHookVerification = responsehookVerification.documents[0].steps;
		// Valida si ya tiene el objeto data que contiene la informacion del score para poder continuar
		if (Object.entries(dataHookVerification[9]).length > 2) {
			// valida que el score del usuario sea mayor a 40 el score es la vericidad del documento y la foto
			if (dataHookVerification[9].data.score > 65) {
				// si ya tiene cargada la informacion en el objeto de la posicion 11 continue sino reintente
				if (
					dataHookVerification[11].data.firstName.value !== null &&
					dataHookVerification[11].data.surname.value !== null &&
					dataHookVerification[11].data.documentNumber.value !== null
				) {
					$("#CustomerName").val(dataHookVerification[11].data.firstName.value);
					$("#CustomerLastName").val(
						dataHookVerification[11].data.surname.value
					);
					limpiarNumeroDocumento =
						dataHookVerification[11].data.documentNumber.value.replace(
							/\./g,
							""
						);
					$("#CustomerIdentification").val(limpiarNumeroDocumento);

					if (
						$("#CustomerName").val() !== "" &&
						$("#CustomerLastName").val() !== "" &&
						$("#CustomerIdentification").val() !== ""
					) {
						document.getElementById("CustomerName").readOnly = true;
						document.getElementById("CustomerLastName").readOnly = true;
						document.getElementById("CustomerIdentification").readOnly = true;

						// usuario con todos los datos de metamap
						infoUsuarioMetamap = true;

						//guardar el usuario en la tabla de verificados
						var formData = new FormData();
						formData.append(
							"identification",
							$("#CustomerIdentification").val()
						);
						formData.append(
							"name",
							dataHookVerification[11].data.firstName.value
						);
						formData.append(
							"last_name",
							dataHookVerification[11].data.surname.value
						);
						formData.append("score", dataHookVerification[9].data.score);

						$.ajax({
							type: "POST",
							url: $("#CustomerNormalRequestForm").attr("action"),
							url: "/pages/customersVerified",
							data: formData,
							processData: false,
							contentType: false,
							cache: false,
							success: function (response) {
								$("#preloader").hide();
								if ($.trim(response) == "2") {
									Swal.fire({
										icon: "error",
										title: "Error",
										text: "Error al guardar el registro de verificación",
									});
									location.reload();
								}
							},
							error: function (e) {
								console.log(e);
							},
						});
					} else {
						registroMetamapObtenerDatosDocumento();
					}
				} else {
					$("#preloader").show();
					registroMetamapObtenerDatosDocumento();
				}
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Verificación fallida, los documentos no son compatibles con la foto",
				});
				setTimeout(function () {
					location.reload();
				}, 5000);
				// showMessage(
				// 	"Verificación fallida, los documentos no son compatibles con la foto",
				// 	true
				// );
			}
		} else {
			$("#preloader").show();
			registroMetamapObtenerDatosDocumento();
		}
	});
}

// funcion de registro para escritorio para resolucion mayor a 1200
function registroMetamapDesktop() {
	var document_file_up = $("#CustomerDocumentFileUp2").val();
	var document_file_down = $("#CustomerDocumentFileDown2").val();
	var image_file = $("#CustomerImageFile2").val();

	// validar en que paso de la foto se encuentra
	if (pasosFotos === 1) {
		if (document_file_up === "" && !$("#CustomerId").length) {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto de la cédula frontal es requerida.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (registroMetamap === false) {
				registroMetamapOauth();
			} else {
				$(`#step0Paso${pasosFotos}`).hide();
				$(`#step0Paso${pasosFotos + 1}`).show();
				$(".jsVolverAtras").show();
				pasosFotos = 2;
			}
		}
	} else if (pasosFotos === 2) {
		if (document_file_down === "" && !$("#CustomerId").length) {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto del reverso de la cedula es requerida.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (registroMetamap === false) {
				validarFotoCedulaReversoDesktopMetamap();
			} else {
				$(`#step0Paso${pasosFotos}`).hide();
				$(`#step0Paso${pasosFotos + 1}`).show();
				$(".jsVolverAtras").show();
				pasosFotos = 3;
			}
		}
	} else if (pasosFotos === 3) {
		if (image_file === "" && !$("#CustomerId").length) {
			// showMessage("Las fotos son requeridas", true);
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto selfie es requerida.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (registroMetamap === false) {
				validarFotoSelfieDesktopMetamap();
			} else {
				$(`#step0Paso${pasosFotos}`).hide();
				$("#step1").show();
				positionStep = 1;
				$(".jsVolverAtras").show();
			}
		}
	}
}

// funcion de registro para escritorio para resolucion menor a 1200
function registroMetamapMovil() {
	const fotoFrontalFile = document.getElementById("fotoFrontal");
	fotoFrontalDocumento = fotoFrontalFile.files[0];

	const fotoInversaFile = document.getElementById("fotoReverso");
	fotoInversaDocumento = fotoInversaFile.files[0];

	const fotoSelfieFile = document.getElementById("fotoSelfie");
	fotoSelfieFileCamara = fotoSelfieFile.files[0];

	console.log(pasosFotos);

	if (pasosFotos === 1) {
		if (fotoFrontalDocumento === undefined && !$("#CustomerId").length) {
			// showMessage("Las fotos son requeridas", true);
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto de la cédula frontal es requerida.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (verificacionFotoCCFrontal === false) {
				registroMetamapOauth();
			} else {
				console.log("pasosFotos", pasosFotos);
				$(`#step0Paso${pasosFotos}`).hide();
				$(`#step0Paso${pasosFotos + 1}`).show();
				pasosFotos = 2;
				$(".jsVolverAtras").show();
			}
		}
	} else if (pasosFotos === 2) {
		if (fotoInversaDocumento === undefined && !$("#CustomerId").length) {
			// showMessage("Las fotos son requeridas", true);
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto del reverso de la cédula es requerido.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (verificacionFotoCCReversa === false) {
				$("#preloader").show();
				validarFotoCedulaReversoMovilMetamap();
			} else {
				$(`#step0Paso${pasosFotos}`).hide();
				$(`#step0Paso${pasosFotos + 1}`).show();
				pasosFotos = 3;
				$(".jsVolverAtras").show();
			}
		}
	} else if (pasosFotos === 3) {
		if (fotoSelfieFileCamara === undefined && !$("#CustomerId").length) {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: `La foto de la selfie es requerida.`,
			});
		} else {
			// si no se ha realizado el registro con metamap
			if (registroMetamap === false) {
				$("#preloader").show();
				validarFotoSelfieMovilMetamap();
			} else {
				$(`#step0Paso${pasosFotos}`).hide();
				$("#step1").show();
				positionStep = 1;
				$(".jsVolverAtras").show();
			}
		}
	}
}
/**
 *
 * @param {*} base64
 * @param {*} newWidth
 * @param {*} newHeight
 * @returns devuelve la nueva imagen con nuevas dimensiones
 */
function resizeBase64Img(base64, newWidth, newHeight) {
	return new Promise(async (resolve, reject) => {
		var canvas = document.createElement("canvas");
		console.log(canvas);
		canvas.style.width = newWidth.toString() + "px";
		canvas.style.height = newHeight.toString() + "100%";
		let context = canvas.getContext("2d");
		let img = document.createElement("img");
		img.src = base64;
		img.onload = function () {
			context.scale(newWidth / img.width, newHeight / img.height);
			context.drawImage(img, 0, 0);
			resolve(canvas.toDataURL());
		};
	});
}

function resizedataURL(datas, wantedWidth, wantedHeight) {
	return new Promise(async function (resolve, reject) {
		// We create an image to receive the Data URI
		var img = document.createElement("img");

		// When the event "onload" is triggered we can resize the image.
		img.onload = function () {
			// We create a canvas and get its context.
			var canvas = document.createElement("canvas");
			var ctx = canvas.getContext("2d");

			// We set the dimensions at the wanted size.
			canvas.width = wantedWidth;
			canvas.height = wantedHeight;

			// We resize the image with the canvas method drawImage();
			ctx.drawImage(this, 0, 0, wantedWidth, wantedHeight);
			var dataURI = canvas.toDataURL();

			// This is the return of the Promise
			resolve(dataURI);
		};

		// We put the Data URI in the image's src attribute
		img.src = datas;
	});
}

/**
 * funcion volver atras en el step
 * @param String name
 * @return String
 */
function funcionVolverAtras() {
	if (positionStep === 0) {
		if (pasosFotos === 1) {
		}
		if (pasosFotos === 2) {
			$(`#step0Paso${pasosFotos}`).hide();
			$(`#step0Paso${pasosFotos - 1}`).show();
			pasosFotos = 1;
			$(".jsVolverAtras").hide();
		} else if (pasosFotos === 3) {
			$(`#step0Paso${pasosFotos}`).hide();
			$(`#step0Paso${pasosFotos - 1}`).show();
			pasosFotos = 2;
		}
	}
	if (positionStep === 1) {
		$(".jsVolverAtras").show();
		$("#step1").hide();
		$("#step0Paso3").show();
		$(".bl-tituloStep").html("Datos para Registro");
		$(".js-iconStep").html(
			'<i class="fa fa-building-o" aria-hidden="true"></i>'
		);
	}
	if (positionStep === 2) {
		$("#step2").hide();
		$("#step1").show();
		$(".bl-tituloStep").html("Datos para Registro");
		$(".js-iconStep").html(
			'<i class="fa fa-building-o" aria-hidden="true"></i>'
		);
	}
	if (positionStep === 3) {
		$("#step3").hide();
		$("#step2").show();
		$(".bl-tituloStep").html("DATOS PERSONALES");
		$(".js-iconStep").html('<i class="fa fa-user " aria-hidden="true"></i>');
	}

	if (positionStep === 4) {
		$("#step4").hide();
		$("#step3").show();
		$(".bl-tituloStep").html("DATOS DE CONTACTO");
		$(".js-iconStep").html(
			'<i class="fa fa-address-book" aria-hidden="true"></i>'
		);
	}

	if (positionStep === 5) {
		$("#step5").hide();
		$("#step4").show();
		$(".bl-tituloStep").html("DATOS DE TU NEGOCIO");
		$(".js-iconStep").html(
			'<i class="fa fa-briefcase" aria-hidden="true"></i>'
		);
	}

	positionStep = positionStep > 0 ? parseInt(positionStep) - 1 : positionStep;

	//comienza de cero a 5
	if (positionStep < 6) {
		$(".jsSiguiente").show();
		$("#guardarFormulario").hide();
	}
}

// $("#CustomerOnline").change(function (event) {
// 	validateOnline();
// });

// function validateOnline() {
// 	if ($("#CustomerOnline").is(":checked")) {
// 		$("#CustomerCode").val(COMMERCE_CODE);
// 		$("#formCodeData").hide();
// 	} else {
// 		$("#CustomerCode").val("");
// 		$("#formCodeData").show();
// 	}
// }

// validateOnline();

$("#CustomerNormalRequestForm").submit(function (event) {
	event.preventDefault();

	if (!FORMULARIO_VALIDO) {
		return false;
	}
	var document_file_up = $("#CustomerDocumentFileUp2").val();
	var document_file_down = $("#CustomerDocumentFileDown2").val();
	var image_file = $("#CustomerImageFile2").val();

	var CustomerCode = $("#CustomerCode").val();
	var CustomerEmail = $("#CustomerEmail").val();
	var CustomerIdentification = $("#CustomerIdentification").val();

	if (
		(!VIDEO_DATA ||
			document_file_up == "" ||
			document_file_down == "" ||
			image_file == "") &&
		!$("#CustomerId").length
	) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Todos los campos son requeridos y se deben tomar las fotos",
		});
		// showMessage(
		// 	"Todos los campos son requeridos y se deben tomar las fotos",
		// 	true
		// );
	} else {
		var form = $("#CustomerNormalRequestForm")[0];
		var formData = new FormData(form);

		if (!$("#CustomerId").length) {
			formData.append(
				"data[Customer][document_file_up]",
				b64toBlob(document_file_up)
			);
			formData.append(
				"data[Customer][document_file_down]",
				b64toBlob(document_file_down)
			);
			formData.append("data[Customer][image_file]", b64toBlob(image_file));
		}

		$("#preloader").show();

		$.ajax({
			type: "POST",
			url: $("#CustomerNormalRequestForm").attr("action"),
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			success: function (response) {
				$("#preloader").hide();
				if ($.trim(response) == "1") {
					location.reload();
				} else {
					// showMessage(response, true);
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response,
					});
				}
			},
			error: function (e) {
				console.log(e);
			},
		});
	}
});

$("#CustomerNormalRequestUniqueForm").submit(function (event) {
	event.preventDefault();

	if (!FORMULARIO_VALIDO) {
		return false;
	}
	var document_file_up = $("#CustomerDocumentFileUp2").val();
	var document_file_down = $("#CustomerDocumentFileDown2").val();
	var image_file = $("#CustomerImageFile2").val();

	var CustomerCode = $("#CustomerCode").val();
	var CustomerEmail = $("#CustomerEmail").val();
	var CustomerIdentification = $("#CustomerIdentification").val();

	if (
		(!VIDEO_DATA ||
			document_file_up == "" ||
			document_file_down == "" ||
			image_file == "") &&
		!$("#CustomerId").length
	) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Todos los campos son requeridos y se deben tomar las foto",
		});
		// showMessage(
		// 	"Todos los campos son requeridos y se deben tomar las fotos",
		// 	true
		// );
	} else {
		var form = $("#CustomerNormalRequestUniqueForm")[0];
		var formData = new FormData(form);

		if (!$("#CustomerId").length) {
			// formData.append(
			// 	"data[Customer][document_file_up]",
			// 	b64toBlob(document_file_up)
			// );
			// formData.append(
			// 	"data[Customer][document_file_down]",
			// 	b64toBlob(document_file_down)
			// );
			formData.append("data[Customer][image_file]", b64toBlob(image_file));
		}
		e.preventDefault();
		grecaptcha.ready(function () {
			grecaptcha
				.execute("reCAPTCHA_site_key", { action: "submit" })
				.then(function (token) {
					// Add your logic to submit to your backend server here.
					console.log(token);
					$("#preloader").show();

					$.ajax({
						type: "POST",
						url: $("#CustomerNormalRequestUniqueForm").attr("action"),
						data: formData,
						processData: false,
						contentType: false,
						cache: false,
						success: function (response) {
							$("#preloader").hide();
							if ($.trim(response) == "1") {
								location.reload();
							} else {
								Swal.fire({
									icon: "error",
									title: "Error",
									text: response,
								});
								// showMessage(response, true);
							}
						},
						error: function (e) {
							console.log(e);
						},
					});
				});
		});
	}
});
