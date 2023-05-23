<?php

$pago1 = $total * ($shopCommerce["Shop"]["cost_max"] / 100);
$pago2 = $total * ($shopCommerce["Shop"]["cost_min"] / 100);

$ivaPago1 = ($pago1 * 0.19) + ($debtsTotal * 0.19);
$ivaPago2 = ($pago2 * 0.19) + ($debtsTotal * 0.19);

$totalPago1 = $total - $pago1 - $ivaPago1;
$totalPago2 = $total - $pago2 - $ivaPago2;
/*echo '<pre>';
var_dump($disbursments2);*/

?>

<?php
$whitelist = array(
		'127.0.0.1',
		'::1'
);

?>

<div class="page-title">
	<div class="row">
		<div class="col-md-9">
			<h3><?php echo __('Cobro de desembolsos y saldo a favor, para la tienda: '); ?>
				<b><?php echo $shopCommerce["Shop"]["social_reason"] . " - " . $shopCommerce["ShopCommerce"]["name"] ?></b>
			</h3>
		</div>
	</div>
</div>
<?php echo $this->Form->create('ShopPaymentRequest', array('role' => 'form', 'data-parsley-validate=""', 'class' => 'form-horizontal form-label-left')); ?>
<h5><b>¿Realmente quieres solicitar este pago ahora?</b></h5>
<div class="col-md-12 mb-4">

	<div class="row">
		<?php if ($disbursments1) { ?>
			<div class="col-md-6">
				<p class="mt-4">Recuerda que si solicitas el pago en <b>PAGO1</b> tu dinero estará en tu cuenta en 24
					horas
					hábiles con una tasa de comisión del <?php echo $shopCommerce["Shop"]["cost_max"] ?>%</p>
			</div>
		<?php }

		if ($disbursments2) { ?>
			<div class="col-md-6">
				<p class="mt-4">Si solicitas el pago en <b>PAGO2</b> tu dinero estará en tu cuenta en 5 días hábiles con
					una
					tasa de comisión del <?php echo $shopCommerce["Shop"]["cost_min"] ?>%</p>
			</div>

		<?php } ?>

		<div class="btn-group btn-group-justified w-100" data-toggle="buttons">

			<?php if ($disbursments1) { ?>
				<label class="btn btn-success active mr-4 requestBtn" data-payment="1"><i class="fa fa-check"></i>Si,
					solicitar pago ahora descontando
					<b>$ <?php echo number_format(($pago1 + $ivaPago1 + $debtsTotal), 2, ".", ",") ?></b>
				</label>
			<?php }
			if ($disbursments2) { ?>
				<label class="btn btn-secondary requestBtn" data-payment="2">
					<i class="fa fa-check"></i> Esperar 5 días hábiles descontando
					<b>$ <?php echo number_format(($pago2 + $ivaPago2 + $debtsTotal), 2, ".", ",") ?></b>
				</label>
			<?php } ?>

		</div>

	</div>
	<br>

	<div class="row">

		<?php if ($disbursments1) { ?>
			<div class="col-md-6">
				<h5><b>Saldos a favor</b></h5>
				<table class="table table-striped table-hover">
					<tbody>
					<?php foreach ($disbursments1 as $key => $value): ?>
						<tr>
							<td>
								<b>Desembolso al crédito
									# <?php echo $value["Credit"]["code_pay"]; ?></b>
							</td>
							<td>
								<i class="fa fa-plus"></i>
								$<?php echo number_format($value["Disbursement"]["value"], "2", ".", ",") ?>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
				<hr>
				<h5><b>Adicionalmente, te descontaremos estos conceptos</b></h5>
				<table class="table table-striped table-hover">
					<tbody>
					<?php foreach ($debts1 as $key => $value): ?>
						<tr>
							<td><b><?php echo $value["ShopsDebt"]["reason"] ?></b></td>
							<td><i class="fa fa-minus"></i>
								<b>$<?php echo number_format($value["ShopsDebt"]["value"], "2", ".", ",") ?></b></td>
						</tr>
					<?php endforeach ?>
					<tr>
						<td>
							<b>IVA 19%</b>
						</td>
						<td>
							<i class="fa fa-minus"></i>
							<b>
								Pago : <span
										class="pago1Iva pago1">$<?php echo number_format($ivaPago1, 2, ".", ",") ?></span>
							</b>
						</td>
					</tr>
					<tr>
						<td><b>Comisión <span class="pago1">Por PAGO </span> <span class="pago2" style="display: none">Por PAGO </span>
							</b></td>
						<td>
							<i class="fa fa-minus"></i>
							<b>
								Pago: <span
										class="pago1Iva pago1">$<?php echo number_format($pago1, 2, ".", ",") ?></span>
							</b>
						</td>
					</tr>
					<tr>
						<td><b><h2>TOTAL SALDO A PAGAR PAGO</b></h2></td>
						<td><h2><b class="btn btn-success btn-xs text-white d-blocks requestBtn"
								   data-payment="1">$<?php echo number_format($totalPago1, 2, ".", ",") ?></b></h2></td>
					</tr>
					</tbody>
				</table>

			</div>
		<?php }
		if ($disbursments2) { ?>

			<div class="col-md-6">

				<h5><b>Saldos a favor</b></h5>
				<table class="table table-striped table-hover">
					<tbody>
					<?php foreach ($disbursments2 as $key => $value): ?>
						<tr>
							<td>
								<b>Desembolso al crédito
									# <?php echo $value["Credit"]["code_pay"]; ?></b>
							</td>
							<td>
								<i class="fa fa-plus"></i>
								$<?php echo number_format($value["Disbursement"]["value"], "2", ".", ",") ?>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
				<hr>
				<h5><b>Adicionalmente, te descontaremos estos conceptos</b></h5>
				<table class="table table-striped table-hover">
					<tbody>
					<?php foreach ($debts as $key => $value): ?>
						<tr>
							<td><b><?php echo $value["ShopsDebt"]["reason"] ?></b></td>
							<td><i class="fa fa-minus"></i>
								<b>$<?php echo number_format($value["ShopsDebt"]["value"], "2", ".", ",") ?></b></td>
						</tr>
					<?php endforeach ?>
					<tr>
						<td>
							<b>IVA 19%</b>
						</td>
						<td>
							<i class="fa fa-minus"></i>
							<b>
								Pago: <span
										class="pago2Iva pago2">$<?php echo number_format($ivaPago2, 2, ".", ",") ?></span>
							</b>
						</td>
					</tr>
					<tr>
						<td><b>Comisión <span class="pago1">Por PAGO </span> <span class="pago2" style="display: none">Por PAGO </span>
							</b></td>
						<td>
							<i class="fa fa-minus"></i>
							<b>
								Pago : <span
										class="pago2Iva pago2">$<?php echo number_format($pago2, 2, ".", ",") ?></span>
							</b>
						</td>
					</tr>
					<tr>
						<td><b><h2>TOTAL SALDO A PAGAR PAGO</b></h2></td>
						<td><h2><b class="btn btn-secondary btn-xs text-white d-blocks requestBtn"
								   data-payment="2">$<?php echo number_format($totalPago2, 2, ".", ",") ?></b></h2></td>
					</tr>
					</tbody>
				</table>

			</div>
		<?php } ?>
	</div>

</div>
<!--<hr>
<h5><b>Saldos a favor</b></h5>
<table class="table table-striped table-hover">
	<tbody>
	<?php /*foreach ($disbursments as $key => $value):  */ ?>
		<tr>
			<td>
				<b>Desembolso al crédito
					# <?php /*echo $value["Credit"]["code_pay"];  */ ?></b>
			</td>
			<td>
				<i class="fa fa-plus"></i> $<?php /*echo number_format($value["Disbursement"]["value"], "2", ".", ",") */ ?>
			</td>
		</tr>
	<?php /*endforeach  */ ?>
	</tbody>
</table>
<hr>
<h5><b>Adicionalmente, te descontaremos estos conceptos</b></h5>
<table class="table table-striped table-hover">
	<tbody>
	<?php /*foreach ($debts as $key => $value):  */ ?>
		<tr>
			<td><b><?php /*echo $value["ShopsDebt"]["reason"]  */ ?></b></td>
			<td><i class="fa fa-minus"></i>
				<b>$<?php /*echo number_format($value["ShopsDebt"]["value"], "2", ".", ",")  */ ?></b></td>
		</tr>
	<?php /*endforeach  */ ?>
	<tr>
		<td>
			<b>IVA 19%</b>
		</td>
		<td>
			<i class="fa fa-minus"></i>
			<b>
				Pago 1: <span class="pago1Iva pago1">$<?php /*echo number_format($ivaPago1, 2, ".", ",")  */ ?></span> /
				Pago 2: <span class="pago2Iva pago2">$<?php /*echo number_format($ivaPago2, 2, ".", ",")  */ ?></span>
			</b>
		</td>
	</tr>
	<tr>
		<td><b>Comisión <span class="pago1">Por PAGO 1</span> <span class="pago2" style="display: none">Por PAGO </span>
			</b></td>
		<td>
			<i class="fa fa-minus"></i>
			<b>
				Pago 1: <span class="pago1Iva pago1">$<?php /*echo number_format($pago1, 2, ".", ",")  */ ?></span> /
				Pago 2: <span class="pago2Iva pago2">$<?php /*echo number_format($pago2, 2, ".", ",")  */ ?></span>
			</b>
		</td>
	</tr>
	<tr>
		<td><b><h2>TOTAL SALDO A PAGAR PAGO 1</b></h2></td>
		<td><h2><b class="btn btn-success btn-xs text-white d-blocks requestBtn"
				   data-payment="1">$<?php /*echo number_format($totalPago1, 2, ".", ",")  */ ?></b></h2></td>
	</tr>
	<tr>
		<td><b><h2>TOTAL SALDO A PAGAR PAGO 2</b></h2></td>
		<td><h2><b class="btn btn-secondary btn-xs text-white d-blocks requestBtn"
				   data-payment="2">$<?php /*echo number_format($totalPago2, 2, ".", ",")  */ ?></b></h2></td>
	</tr>
	<tr>
		<td class="text-center text-danger" colspan="2">
			<small>
				****Nota****:
				Este valor final puede variar al momento del pago, si existen recaudos o más deudas entre la solicitud y
				el pago
			</small>
		</td>
	</tr>
	</tbody>
</table>-->

<div class="md-12 text-center text-danger">
	<small>
		****Nota****:
		Este valor final puede variar al momento del pago, si existen recaudos o más deudas entre la solicitud y
		el pago
	</small>
</div>

<script>
	var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
	var actual_url = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here, true) : $this->here ?>?";
</script>

<?php echo $this->Html->script("shops/payments.js?" . rand(), array('block' => 'AppScript')); ?>
