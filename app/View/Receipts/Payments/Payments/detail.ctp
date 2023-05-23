<div class="">
	<button id="imprimeData" class="btn btn-primary ">Imprimir</button>
</div>
	<?php $numberCuotes = [];  if (!empty($receipt["Payment"])): ?>
	<?php

			$totalByCuotes 	= [];
			$totalByCapital = [];
			$numberCuotes 	= [];
			$last 			= 0;
			foreach ($receipt["Payment"] as $key => $value) {
				if(!array_key_exists($value["credits_plan_id"], $totalByCuotes)){
					$totalByCuotes[$value["credits_plan_id"]] = $value["value"];
				}else{
					$totalByCuotes[$value["credits_plan_id"]] += $value["value"];
				}

				if(!array_key_exists($value["credits_plan_id"],$totalByCapital) && $value["type"] == 1){
					$totalByCapital[$value["credits_plan_id"]] = $value["value"];
				}

				$numberCuotes[$value["credits_plan_id"]] = $value["CreditsPlan"]["number"];
				$last = $value["credits_plan_id"];
			}
		 ?>
	 <?php endif ?>

	<div class="container containerImprime" id="pdfPayment">
		<img style="width: 180px;" src="https://credishop.co/img/credishop.png" class="mb-2">
		<div class="datacomerce">
	        <p><b>ZÍRO </b></p>
	        <p><b>Av Santander 65 - 15 Local 115 </b></p>
	        <p><b>Manizales, Colombia</b></p>
	        <p><b>TELÉFONO: (+57) 3209860583</b></p>
			<hr>
			<p>RECIBO DE CAJA No. <b><?php echo str_pad($receipt["Receipt"]["id"], 6, "0", STR_PAD_LEFT);  ?></b> </p>
			<p>FECHA: <?php echo date("d-m-Y",strtotime($dataPayment["Payment"]["created"])); ?></p>
			<p>HORA: <?php echo date("H:i A",strtotime($dataPayment["Payment"]["created"])); ?></p>
			<p id="statuscreditp"></p>
			<hr>
		</div>
		<div class="datacliente">
			<p><b>CED/NIT:</b> <?php echo $credit["Customer"]["identification"]; ?></p>
			<p class="upper"><b>NOMBRE:</b> <?php echo $credit["Customer"]["name"]; ?></p>
			<p class="upper"><b>ALMACÉN:</b>
				<?php if (!empty($dataPayment["ShopCommerce"])): ?>
					<?php echo $dataPayment["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $dataPayment["ShopCommerce"]["name"] ?>
				<?php else: ?>
					PAGO WEB
				<?php endif ?>
			</p>
			<p><b>OBLIGACIÓN:</b> <?php echo $credit["Credit"]["CreditsRequest"]["code_pay"] ?></p>
			<p><b>VALOR CUOTA:</b>  <?php echo number_format($credit["Credit"]["quota_value"]) ?></p>
			<p><b>TOTAL PAGADO:</b> $<?php echo number_format($receipt["Receipt"]["value"]) ?></p>
			<p><b>CUOTAS CANCELADAS:</b> <b><?php echo count($numberCuotes) ?></b> </p>
			<hr>
			<p><b>DISCRIMINADO</b></p>
			<?php if (!empty($receipt["Payment"])): ?>
				<?php $valor = $credit["Credit"]["value_pending"]; ?>
				<?php foreach ($totalByCuotes as $key => $value): ?>
					<p class="upper">No. Cuota: <b><?php echo $numberCuotes[$key]; ?></b></p>
					<p class="upper">Valor Pagado: <b>$<?php echo number_format($value); ?></b></p>
					<?php if (count($totalByCuotes) == 1): ?>
						<!-- <p class="upper">Saldo Crédito: <b>$<?php echo number_format($totalCredit); ?></b></p> -->
					<?php else: ?>
						<?php if ($key == $last): ?>
							<!-- <p class="upper">Saldo Crédito: <b>$<?php //echo number_format($totalCredit < 1 ? 0 : $totalCredit); ?></b></p> -->
						<?php else: ?>
							<?php $valor+=$totalByCapital[$key] ?>
							<?php $valorData = $this->Utilidades->calcularDeudaCuota($key,$credit["Credit"]["id"]); ?>
							<!-- <p class="upper">Saldo Crédito: <b>$<?php //echo number_format($valorData < 1 ? 0 : $valorData); ?></b></p> -->
						<?php endif ?>
					<?php endif ?>
					<hr>
				<?php endforeach ?>
			<?php endif ?>

			<p>NUEVO SALDO: <b>$<?php echo number_format($receipt["Receipt"]["saldo"] < 1 ? 0 : $receipt["Receipt"]["saldo"]) ?></b></p>
			<p id="statushere" class="upper <?php echo $credit["Credit"]["state"] == 1 ? "Pagado" : "Saldos pendientes" ?>">ESTADO CRÉDITO: <b><?php echo $credit["Credit"]["state"] == 1 ? "Pagado" : "En Curso" ?></b></p>
			<p>CUPO DISPONIBLE: <b>$<?php echo number_format($receipt["Receipt"]["disponible"]) ?></b></p>
		</div>
	</div>

	<script type="text/javascript">
		if ($("#statushere").hasClass("Pagado")) {
		    $("#statuscreditp").html("<hr>CRÉDITO A PAZ Y SALVO");
		}
	</script>
