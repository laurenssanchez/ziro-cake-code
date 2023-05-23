VIDEO_DATA = false;
let currentStream;
const select = document.getElementById("select");

function stopMediaTracks(stream) {
	stream.getTracks().forEach((track) => {
		track.stop();
	});
}

function gotDevices(mediaDevices) {
	if ($("#CustomerCrediventasForm").length) {
		return false;
	}
	select.innerHTML = "";
	select.appendChild(document.createElement("option"));
	let count = 1;
	var cammeraId = null;
	mediaDevices.forEach((mediaDevice) => {
		if (mediaDevice.kind === "videoinput") {
			const option = document.createElement("option");
			option.value = mediaDevice.deviceId;
			const label = mediaDevice.label || `Camera ${count++}`;
			const textNode = document.createTextNode(label);
			option.appendChild(textNode);
			select.appendChild(option);
			if (count == 1) {
				cammeraId = mediaDevice.deviceId;
			}
			if (
				mediaDevice.label.toLowerCase().indexOf("back") != -1 ||
				mediaDevice.label.toLowerCase().indexOf("trasera") != -1
			) {
				cammeraId = mediaDevice.deviceId;
				select.value = mediaDevice.deviceId;
			}
		}
	});
}

function tieneSoporteUserMedia() {
	return !!(
		navigator.getUserMedia ||
		navigator.mozGetUserMedia ||
		navigator.mediaDevices.getUserMedia ||
		navigator.webkitGetUserMedia ||
		navigator.msGetUserMedia
	);
}

function _getUserMedia() {
	return (
		navigator.getUserMedia ||
		navigator.mozGetUserMedia ||
		navigator.mediaDevices.getUserMedia ||
		navigator.webkitGetUserMedia ||
		navigator.msGetUserMedia
	).apply(navigator, arguments);
}

var $video = document.getElementById("video");

$video.pause();

function InitSetPhoto(canvas, input, img, idBtn) {
	if (typeof currentStream !== "undefined") {
		stopMediaTracks(currentStream);
	}
	const videoConstraints = {};
	if (select.value === "") {
		videoConstraints.facingMode = "environment";
	} else {
		videoConstraints.deviceId = { exact: select.value };
	}
	const constraints = {
		video: videoConstraints,
		audio: false,
	};
	navigator.mediaDevices
		.getUserMedia(constraints)
		.then((stream) => {
			currentStream = stream;
			video.srcObject = stream;

			VIDEO_DATA = true;
			$video.srcObject = stream;
			$("#tomaFoto").data("canvas", canvas);
			$("#tomaFoto").data("input", input);
			$("#tomaFoto").data("img", img);
			$("#tomaFoto").data("id", idBtn);
			$("#modalPhoto").modal("show");

			return navigator.mediaDevices.enumerateDevices();
		})
		.then(gotDevices)
		.catch((error) => {
			VIDEO_DATA = false;
		});
}

$("body").on("click", "#tomaFoto", function (event) {
	event.preventDefault();
	var idCanvas = $(this).data("canvas");
	var idInput = "#" + $(this).data("input");
	var idIMg = "#" + $(this).data("img");
	var idBtn = "#" + $(this).data("id");
	var $canvas = document.getElementById(idCanvas);

	//Obtener contexto del canvas y dibujar sobre él
	var contexto = $canvas.getContext("2d");
	$canvas.width = $video.videoWidth;
	$canvas.height = $video.videoHeight;
	contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);

	var foto = $canvas.toDataURL();
	$(idInput).val(foto);
	$(idIMg).hide();

	stream = $video.srcObject;
	tracks = stream.getTracks();
	tracks.forEach(function (track) {
		track.stop();
	});
	$canvas.style.display = "block";
	$("#modalPhoto").modal("hide");
	$(idBtn).html("Tomar otra foto");
	$(idBtn).removeClass("btn-primary");
	$(idBtn).addClass("btn-secondary");
});

function b64toBlob(dataURI) {
	var byteString = atob(dataURI.split(",")[1]);
	var ab = new ArrayBuffer(byteString.length);
	var ia = new Uint8Array(ab);

	for (var i = 0; i < byteString.length; i++) {
		ia[i] = byteString.charCodeAt(i);
	}
	return new Blob([ab], { type: "image/jpeg" });
}

$(".fotoBtn").click(function (event) {
	event.preventDefault();
	InitSetPhoto(
		$(this).data("canvas"),
		$(this).data("input"),
		$(this).data("img"),
		$(this).attr("id")
	);
});

navigator.mediaDevices.enumerateDevices().then(gotDevices);
