<?php
$paymentsData = 0;
$otherPaymentTotal = 0;
$finalValue = $shopPaymentRequest["ShopPaymentRequest"]["request_value"];
$othersValues = 0;
$othersIva = 0;
$othersPaymentIva = 0;
$finalIva = 0;
$finalRetefuente = 0;

if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0,2]) ) {

	if (!empty($otherDepts)) {
		foreach ($otherDepts as $key => $value) {
			$finalValue -= $value["ShopsDebt"]["value"];
			$othersValues += $value["ShopsDebt"]["value"];
		}
	}

	if ($othersValues > 0) {

		$othersIva = $othersValues * 0.19;
		$finalIva  += $othersIva;
		$finalValue -= $othersIva;
	}

	//iva
	$finalIva += $shopPaymentRequest["ShopPaymentRequest"]["iva"];
	//retefuente
	$finalRetefuente += $shopPaymentRequest["ShopPaymentRequest"]["retefuente"];

	//valor final
	$finalValue -= $finalIva;
	$finalValue += $finalRetefuente;


	foreach ($shopPaymentRequest["ShopsDebt"] as $key => $value){
		$finalValue -= $value["value"];
	}
}

?>
<div class="page-title">
	<div class="row">
		<div class="col-md-9">
			<h3 class="controlstate"><?php echo __('Solicitud de pago. '); ?>
				<b>Estado:
					<?php
					switch ($shopPaymentRequest['ShopPaymentRequest']['state']) {
						case '0':
							echo "Solicitado";
							break;
						case '1':
							echo "Pagado";
							break;
						case '2':
							echo "Pendiente";
							break;
					}

					?>
				</b>
			</h3>
			<b><?php
				/*echo '<pre>';
				echo json_encode($shop);
				echo '</pre>';*/

				echo $shop["Shop"]["social_reason"] ?>
				<?php if (in_array(AuthComponent::user("role"), [1, 2])): ?>
					- Código: <?php echo $shop["ShopCommerce"][0]["code"]; ?>
				<?php endif ?>
			</b> </h3>
		</div>
	</div>
</div>
<hr>

<?php if ($shopPaymentRequest["ShopPaymentRequest"]["state"] == 2): ?>
	<div class="x_panel">
		<p class="m-0">
			<b>Nota de estado pendiente:</b> <?php echo $shopPaymentRequest["ShopPaymentRequest"]["notes"] ?>
		</p>
		<p class="m-0">
			<b>Fecha pendiente: </b><?php echo @$shopPaymentRequest["ShopPaymentRequest"]["date_pending"] ?>
		</p>
	</div>
<?php endif ?>


<div class="x_panel">

	<h5><b>DETALLE DE PAGO SOLICITADO:
		<!-- <?php echo "PAGO" . $shopPaymentRequest["ShopPaymentRequest"]["payment_type"] ?>  -->
		</b> <br>
		<?php if ($shopPaymentRequest["ShopPaymentRequest"]["state"] == 1): ?>
			<b>
				Fecha de
				pago: <?php echo date("Y-m-d", strtotime($shopPaymentRequest["ShopPaymentRequest"]["final_date"])) ?>
			</b>
			<br>
			<b>
				Referencia de pago: <?php
				/*echo '<pre>';
				var_dump($shopPaymentRequest);exit();*/
				echo $shopPaymentRequest["ShopCommerce"]["Shop"]["id"] ?>
			</b>
		<?php endif ?>

	</h5>
	<hr>
	<h5><b>Saldos a favor</b></h5>
	<table class="table table-striped table-hover">
		<tbody>
		<?php foreach ($disbursements as $key => $value): ?>
			<tr>
				<td>
					<b>Desembolso al crédito # <?php echo $value['credits']['code_pay']; ?>, CC
						Cliente: <?php echo $value["customers"]["identification"] ?></b>
				</td>
				<td>
					<i class="fa fa-plus"></i> $<?php echo number_format($value["disbursements"]["value"], "2", ".", ",") ?>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
</div>

<div class="x_panel">
	<h5><b>Se descontarán estos conceptos:</b></h5>
	<!-- <h6 class="nota-aclaratoria"><span
				style="background-color: red; color: white; padding: 2px 5px; font-size: 11px; font-weight: bold">IMPORTANTE</span>
		<span style="font-style: italic;">(El porcentaje de la comisión se calcula de acuerdo al valor retirado menos el costo por estudio de crédito)</span>
	</h6> -->
	<table class="table table-striped table-hover">
		<tbody>

		<?php foreach ($shopPaymentRequest["ShopsDebt"] as $key => $value): ?>
			<tr>
				<td><?php echo $value["reason"] ?></td>
				<td><i class="fa fa-minus"></i> <b>$<?php echo number_format($value["value"], "2", ".", ",") ?></b></td>
			</tr>
		<?php endforeach ?>


		<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0]) && (!empty($otherDepts) || !empty($otherPayments))): ?>

			<tr>
				<td colspan="2">
					Adicionales a cobrar no incluidos
				</td>
			</tr>

			<?php foreach ($otherDepts as $key => $value): ?>
				<tr>
					<td><?php echo $value["ShopsDebt"]["reason"] ?></td>
					<td><i class="fa fa-minus"></i>
						<b>$<?php echo number_format($value["ShopsDebt"]["value"], "2", ".", ",") ?></b></td>
				</tr>
			<?php endforeach ?>

		<?php endif ?>

		<tr>
			<td>
				IVA 19%
			</td>
			<td>
				<i class="fa fa-minus"></i>
				<b>
					$<?php echo in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) ? number_format($shopPaymentRequest["ShopPaymentRequest"]["iva"]) : number_format($shopPaymentRequest["ShopPaymentRequest"]["iva_final"]) ?>
				</b>
			</td>
		</tr>

		<!-- retefuente -->

		<tr>
			<td>
				Retefuente 11%
			</td>
			<td>
				<i class="fa fa-plus"></i>
				<b>
					$<?php echo in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) ? number_format($shopPaymentRequest["ShopPaymentRequest"]["retefuente"]) : number_format($shopPaymentRequest["ShopPaymentRequest"]["retefuente_final"]) ?>
				</b>
			</td>
		</tr>

		<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0]) && !empty($otherDepts)): ?>
			<tr>
				<td>
					IVA 19% por deudas adicionales
				</td>
				<td>
					<i class="fa fa-minus"></i> <b><?php echo number_format($othersIva, "2", ".", ",") ?></b>
				</td>
			</tr>
			<?php foreach ($otherDepts as $key => $value): ?>
				<tr>
					<td><?php echo $value["ShopsDebt"]["reason"] ?></td>
					<td><i class="fa fa-minus"></i>
						<b>$<?php echo number_format($value["ShopsDebt"]["value"], "2", ".", ",") ?></b></td>
				</tr>
			<?php endforeach ?>

		<?php endif ?>


		<tr>

			<td><b><h2>
						TOTAL <?php echo in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) ? "" : "PAGADO" ?>
						:</b></h2></td>
			<td>
	      	<span>
	      		<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) && (!empty($otherDepts) || !empty($otherPayments))): ?>
					<p class="m-0">Solicitado: <b>$<?php echo number_format($shopPaymentRequest["ShopPaymentRequest"]["request_value"], 2, ".", ",") ?></b></p>
					<p class="m-0">Valor adicional descontado: <b>$<?php echo number_format($othersValues + $otherPaymentTotal + $othersPaymentIva + $othersIva, 2, ".", ",") ?></b></p>
					<p class="m-0">Valor final: <b>$<?php echo number_format($finalValue, 2, ".", ",") ?></b></p>
				<?php else: ?>
					<b class="btn btn-success btn-xs text-white d-blocks">
			      		<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2])): ?>
			      			$<?php echo number_format($finalValue, 2, ".", ",") ?>

						<?php else: ?>
							$<?php echo number_format($shopPaymentRequest["ShopPaymentRequest"]["final_value"], 2, ".", ",") ?>
						<?php endif ?>
			      	</b>
				<?php endif ?>
	      	</span>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>

			</td>
		</tr>
		</tbody>
	</table>
	<div class="row">
		<div class="col-md-8">
			<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) && in_array(AuthComponent::user("role"), [1, 2])): ?>
				<?php echo $this->Form->create("ShopPaymentRequest", array('role' => 'form', 'data-parsley-validate', 'class' => 'form-horizontal form-label-left')); ?>
				<?php if (in_array($shopPaymentRequest["ShopPaymentRequest"]["state"], [0, 2]) ): ?>
					<?php echo $this->Form->input("iva_final", ["type" => "hidden", "value" => $shopPaymentRequest["ShopPaymentRequest"]["iva"] + $othersIva]) ?>
					<?php echo $this->Form->input("retefuente_final", ["type" => "hidden", "value" => $shopPaymentRequest["ShopPaymentRequest"]["retefuente"]]) ?>
					<?php echo $this->Form->input("final_value", ["type" => "hidden", "value" => $finalValue]) ?>
				<?php else: ?>
					<?php echo $this->Form->input("iva_final", ["type" => "hidden", "value" => $shopPaymentRequest["ShopPaymentRequest"]["iva"]]) ?>
					<?php echo $this->Form->input("retefuente_final", ["type" => "hidden", "value" => $shopPaymentRequest["ShopPaymentRequest"]["retefuente"]]) ?>
					<?php echo $this->Form->input("final_value", ["type" => "hidden", "value" => $shopPaymentRequest["ShopPaymentRequest"]["request_value"]]) ?>
				<?php endif ?>

				<div class="form-group">
					<label for="">Valor a pagar</label>
					<input id="valorPago"
						class="form-control"
						required="required"
						value="<?php echo number_format($finalValue, "0", ".", ",")?>"
						type="text"
						onkeyup="formatoCosto(this)">

						<input id="valorPagoSinDecimal"
						name="valorPagoSinDecimal"
						class="form-control"
						value="<?php echo $finalValue; ?>"
						type="hidden">
				</div>

				<div class="form-group">
					<label for="">Fecha de pago</label>
					<?php echo $this->Form->text("final_date", ["type" => "date", "value" => date("Y-m-d"), "class" => "form-control", "required"]) ?>
				</div>
				<div class="form-group">
					<label for="">Notas adicionales</label>
					<?php echo $this->Form->input("notes", ["class" => "form-control", "required" => false, "label" => false]) ?>
				</div>
				<div class="form-group">
					<label for="">Referencia de pago</label>
					<?php echo $this->Form->input("reference", ["class" => "form-control", "required", "label" => false]) ?>
				</div>

				<h5 class="d-none mensajeDiferencia">
					El valor ingresado a pagar contra el valor calculado a pagar por el sistema tiene una diferencia de <b><span class="valorDiferencia"></span></b>
				</h5>

				<input type="submit" class="btn btn-success mt-4" value="Marcar como pagado">
				<?php if ($shopPaymentRequest["ShopPaymentRequest"]["state"] == 0): ?>
					<a href="<?php echo $this->Html->url(array('action' => 'pending', $this->Utilidades->encrypt($shopPaymentRequest['ShopPaymentRequest']['id']))); ?>"
					   class="btn btn-warning mt-4 btn-xs pendingRequest">
						Marcar como pendiente <i class="fa fa-pencil"></i>
					</a>
				<?php endif ?>
				<?php echo $this->Form->end(); ?>
			<?php endif ?>
		</div>
	</div>
</div>


<?php echo $this->Html->script("payments/requests.js?" . rand(), array('block' => 'AppScript')); ?>


<?php $this->start("AppScript") ?>
	<script>
		function formatoCosto(el) {
			valorIngresado=el.value.replace(/,/g, "");
			var v = el.value;
			v = v.replace(/[^0-9]/g,"");
			$(el).val(v);
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(v)) { v = v.replace(rgx, '$1,$2'); }
			$(el).val(v);

			//calcular diferencia
			valorCalculado=$('#ShopPaymentRequestFinalValue').val();
			$('#valorPagoSinDecimal').val('');
			$('.valorDiferencia').text('');

			if (valorIngresado<valorCalculado) {
				diferencia = (valorCalculado - valorIngresado);
				$('.mensajeDiferencia').removeClass('d-none').addClass('d-block');
				$('.valorDiferencia').text(diferencia);
			} else {
				$('.mensajeDiferencia').removeClass('d-block').addClass('d-none');
			}
			$('#valorPagoSinDecimal').val(valorIngresado);
		}

	</script>
<?php $this->end() ?>
