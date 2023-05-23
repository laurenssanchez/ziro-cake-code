<div class="">
	<button id="imprimeData" class="btn btn-primary ">Imprimir</button>
</div>

<div id="pdfPayment" class="mt-1">
	<?php if (in_array(AuthComponent::user("role"), [1, 2, 3, 4, 5, 6, 7, 9, 11])) : ?>

		<div class="container">
			<img style="width: 180px;" src="https://creditos.somosziro.com/img/logo-ziro.png" class="mb-2">
			<div class="datacomerce">
				<p><b>Somos Zíro S.A.S</b></p>
				<p><b>DIRECCIÓN: Av. Santander # 65 - 15 LC 115</b></p>
				<p><b>Manizales, Caldas - Colombia</b></p>
				<p><b>TELÉFONO: 320 9860583</b></p>
				<hr>
				<p><b>DEUDOR (A)</b></p>
				<p class="upper"><?php echo $creditRequest["Customer"]["identification"] ?> <?php echo $creditRequest["Customer"]["name"] ?></p>
				<p><b>OBLIGACIÓN</b></p>
				<p><?php echo $creditRequest["CreditsRequest"]["code_pay"]; ?></p>
				<p><b>DIRECCIÓN CLIENTE</b></p>
				<p class="upper"><?php echo $creditRequest["Customer"]["CustomersAddress"]["0"]["address"] ?></p>
				<p><b>TELÉFONO CLIENTE</b></p>
				<p><?php echo $creditRequest["Customer"]["CustomersPhone"]["0"]["phone_number"] ?></p>
				<hr>
				<p><b>PROVEEDOR</b></p>
				<p><?php echo h($creditRequest['ShopCommerce']['name'] . " - " . $creditRequest['ShopCommerce']["Shop"]['social_reason']); ?></p>
				<p><b>VALOR CRÉDITO RETIRADO</b></p>
				<p>$<?php echo number_format($creditInfo["Credit"]["value_request"]) ?> * <?php echo $creditInfo["Credit"]["number_fee"] ?> cuotas</p>
				<p><b>FRECUENCIA DE PAGOS</b></p>
				<p>
					<!-- Frecuencia -->
					<?php
					if ($creditInfo["Credit"]["type"] == 1)
						$tipoCredito = "Mensual";
					else if ($creditInfo["Credit"]["type"] == 3)
						$tipoCredito = "45 días";
					else if ($creditInfo["Credit"]["type"] == 4)
						$tipoCredito = "60 días";
					else
						$tipoCredito = "Quincenal";
					?>

					<?php echo $tipoCredito; ?>
				</p>

				<p><b>FECHA INICIO CRÉDITO</b></p>
				<p><?php echo date("d-m-Y", strtotime($creditInfo['CreditsRequest']['date_disbursed'])); ?></p>
				<hr>
			</div>
		</div>
	<?php endif ?>


	<p class="upper"><b>Plan de pagos</b></p>
	<table class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>CUOTA</th>
				<th>VALOR</th>
				<th>FECHA</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($quotes as $key => $value) : ?>
				<tr>
					<td><?php echo $value["CreditsPlan"]["number"] ?></td>
					<td>
						$ <?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>
					<td>
						<?php echo date("d-m-Y", strtotime($value['CreditsPlan']['deadline'])); ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

</div>

<?php echo $this->Html->script("printArea.js?" . rand(), array('block' => 'jqueryApp')); ?>
<script>
	$("#imprimeData").click(function(event) {
		var mode = 'iframe';
		var close = mode == "popup";
		var options = {
			mode: mode,
			popClose: close
		};
		$("div#pdfPayment").printArea(options);
	});
</script>
