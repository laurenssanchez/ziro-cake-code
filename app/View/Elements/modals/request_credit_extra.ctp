<div class="modal fade" id="request_credit_extra" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Solicitar Aumento de Cupo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="">
				<form id="formCreditExtra" action="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "request_credit_extra"]) ?>" method="post">

					<div class="text-center">
						<p class="lead m-0">Actualmente tienes</p>
						<p class="h2 m-0"><b>$ <?php echo number_format($totalCustomerQuote, 2) ?></b></p>
					</div>
					<div class="form-group">
						<label for="shop_commerce_extra">¿A qué proveedor enviarás tu solicitud? </label>
						<select name="shop_commerce_extra" id="shop_commerce_extra" class="form-control" required="">
							<option value="">Seleccionar</option>
							<?php foreach ($list as $key => $value) : ?>
								<option value="<?php echo $key ?>"><?php echo $value ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Monto de dinero adicional a Solicitar</label>
						<input name="valueCredit" id="valueCredit" placeholder="¿Cuánto dinero adicional requieres?" class="form-control" value="100000" step="10000" min="100000" type="number" required="">
					</div>

                    <div class="form-group">
						<label for="frecuency">¿Con qué frecuencia de pago?</label>
						<select class="form-control" name="frecuency" id="frecuency" required="">
							<option value="1">Mensual</option>
                            <option value="3">45 días</option>
                            <option value="4">60 días</option>
							<!-- <option value="2">Quincenal</option> -->
						</select>
					</div>

					<div class="form-group">
						<label for="numberCuote">¿A cuántas cuotas?</label>
						<select class="form-control" id="numberCuote" name="numberCuote">
							<option value="1" data-quince="2">1</option>
							<option value="2" data-quince="4">2</option>
							<option value="3" data-quince="6">3</option>
							<option value="4" data-quince="8">4</option>
							<option value="5" data-quince="10">5</option>
							<option value="6" data-quince="12">6</option>
						</select>
					</div>

					<div class="form-group">
						<p class="text-center text-success" id="textCredit" data-actual="<?php echo $totalCustomerQuote ?>">Si tu solicitud de crédito es aprobada tendrás $<span id="newValueData"></span> de dinero disponible, tu cuota para este aumento será de $<span id="valueData"></span></p>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success d-block mx-auto" value="Solicitar aumento">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<?php $this->start("AppScript") ?>

<script type="text/javascript">

	var data = <?php echo $data ?>;

	var opciones = "";
	var valor = 0;
	var initial = 0;
	var final = 0;

	const solicitado = document.querySelector("#valueCredit");

	solicitado.addEventListener('onchage', (event) => {
		updateQuotes();
	});

	solicitado.addEventListener('blur', (event) => {
		updateQuotes();
	});

	function updateQuotes() {

		data = "";
		var data = <?php echo $data ?>;
		//document.getElementById('coutas-number').innerHTML="";
		opciones = "";
		document.getElementById('numberCuote').innerHTML = "";
		//coutas-number
		//document.getElementById("valueCredit").value;
		valor = Number(document.getElementById("valueCredit").value)
		// alert(typeof(valor));
		if (valor == "") {
			valor = <?php echo $valorMini ?>
		}

		initial = 0;

		final = 0;

		for (x of data) {
			//console.log(x.credits_lines_details.min_value);

			if ((valor >= x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value)) {

				if (initial == 0) {
					initial = x.credits_lines_details.month;

				} else if (initial > x.credits_lines_details.month) {
					initial = x.credits_lines_details.month;

				}

			}
		}

		for (x of data) {
			//console.log(x.credits_lines_details.min_value);

			if ((valor >= x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value)) {

				if (final == 0) {
					final = x.credits_lines_details.month;

				} else if (final < x.credits_lines_details.month) {
					final = x.credits_lines_details.month;

				}

			}
		}

		if (initial == 0 || final == 0) {
			console.log("raro");
		}

		for (var i = initial; i <= final; i++) {
			// console.log(i);
			opciones += "<option data-mes=" + i + " data-quince=" + i * 2 + " value=" + i + ">" + i + "</option>" + "\n";

		}

		document.getElementById('numberCuote').innerHTML = opciones
		document.getElementById('numberCuote').value = initial
	}

	document.onload = updateQuotes();
</script>

<?php $this->end() ?>
