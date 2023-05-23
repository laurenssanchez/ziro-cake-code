<div class="">
	<a class="btn btn-success" href="<?php echo $this->Html->url(["controller"=>"credits_requests","action"=>"index"]) ?>"> REGRESAR</a>
	<button id="imprimeData" class="btn btn-primary ">Imprimir</button>
</div>

<div id="pdfPayment" class="mt-1">
	<div class="container">
		<img style="width: 180px;" src="https://creditos.somosziro.com/img/logo-ziro.png" class="mb-2">
		<div class="datacomerce">
	        <p><b>ZÍRO</b></p>
	        <p><b>DIRECCIÓN: Av Santander 65 - 15 Local 115, </b></p>
	        <p><b>Manizales, Colombia</b></p>
	        <p><b>CELULAR: 3209860583</b></p>
			<hr>
			<p>RECIBO DE CAJA No. <b> <?php echo str_pad($cuotaData["Payment"]["receipt_id"], 6, "0", STR_PAD_LEFT);  ?></b> </p>
			<p>FECHA: <?php echo date("d-m-Y") ?></p>
			<p>HORA: <?php echo date("H:i A") ?></p>
			<hr>
		</div>
			<?php
				//$totalData = 0;
				/*if (!empty($quotes)) {
					foreach ($quotes as $key => $value) {
						$totalData+=$value;
					}
				}*/

				$totalData = $totalpago;
			?>
		<div class="datacliente">
			<p><b>CED/NIT:</b> <?php echo $credit["Customer"]["identification"]; ?></p>
			<p class="upper"><b>NOMBRE:</b> <?php echo $credit["Customer"]["name"]; ?></p>
			<p class="upper"><b>ALMACÉN:</b> <?php echo $shop_commerce["Shop"]["social_reason"]." - ". $shop_commerce["ShopCommerce"]["name"] ?></p>
			<p><b>OBLIGACIÓN:</b> <?php echo $credit["Credit"]["code_pay"] ?></p>
			<p><b>VALOR CUOTA:</b>  <?php echo number_format($credit["Credit"]["quota_value"]) ?></p>
			<!-- <p><b>VALOR MORA:</b> 0</p> -->
			<p><b>TOTAL PAGADO:</b> $<?php echo number_format($receipt["Receipt"]["total_payments"]) ?></p>
			<p><b>CUOTAS CANCELADAS:</b> <?php echo count($quotes) ?></p>
			<hr>
			<p><b>DISCRIMINADO</b></p>
			<?php if (!empty($quotes)): ?>
				<?php foreach ($quotes as $key => $value): ?>
					<p class="upper">No. Cuota: <b><?php echo $key; ?></b></p>
					<p class="upper">Valor Pagado: <b>$<?php echo number_format($value); ?></b></p>
					<!-- <p class="upper">Valor Deuda: <b>$<?php //echo number_format($this->Utilidades->calcularDeudaCuota($cuotasId[$key],$credit["Credit"]["id"])); ?></b></p> -->
					<hr>
				<?php endforeach ?>
			<?php endif ?>
			<p>NUEVO SALDO: <b>$<?php echo $cuotaData["Receipt"]["saldo"] <= 50 ? 0 :  number_format($cuotaData["Receipt"]["saldo"]) ?></b></p>
	      	<p>CUPO DISPONIBLE: <b>$<?php echo number_format($cuotaData["Receipt"]["disponible"]) ?></b></p>
			<p class="upper">ESTADO CRÉDITO:
				<b>
					<?php if (is_null($receipt["Receipt"]["state_credit"])): ?>
						<?php echo $credit["Credit"]["state"] == 1 ? "Pagado" : "En Curso" ?>
					<?php else: ?>
						<?php echo $receipt["Receipt"]["state_credit"] == 1 ? "Pagado" : "En Curso" ?>
					<?php endif ?>
				</b>
			</p>
		</div>
	</div>
</div>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<?php echo $this->element("/modals/request"); ?>

<?php

	echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/payments.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/admin.js?".rand(),           array('block' => 'AppScript'));

?>

<script>
    $("#imprimeData").click(function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#pdfPayment").printArea( options );
    });
</script>
