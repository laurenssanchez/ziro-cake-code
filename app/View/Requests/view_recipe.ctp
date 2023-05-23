<div class="">
	<button id="imprimeData" class="btn btn-primary ">Imprimir</button>
</div>
	<div class="container containerImprime" id="pdfPayment">
		<img style="width: 180px;" src="https://creditos.somosziro.com/img/email/mailNuevoCliente/Header.png" class="mb-2">
		<div class="datacomerce">
				<p><b>ZÍRO </b></p>
				<p><b>Av Santander 65 - 15 Local 115 </b></p>
				<p><b>Manizales, Colombia</b></p>
				<p><b>TELÉFONO: (+57) 3209860583</b></p>
			<hr>
			<p>RECIBO DE CAJA No. <b><?php echo str_pad($request["Request"]["id"], 6, "0", STR_PAD_LEFT);  ?></b> </p>
			<p>FECHA: <?php echo date("d-m-Y",strtotime($request["RequestsDetail"]["created"])); ?></p>
			<p>HORA: <?php echo date("H:i A",strtotime($request["RequestsDetail"]["created"])); ?></p>
			<p id="statuscreditp"></p>
			<hr>
		</div>
		<div class="datacliente">
			<p><b>CED/NIT:</b> <?php echo $request["Request"]["identification"]; ?></p>
			<p class="upper"><b>ALMACÉN:</b>
				<?php echo $request["ShopCommerce"]["Shop"]["social_reason"] ?> - <?php echo $request["ShopCommerce"]["name"] ?>
			</p>
			<p><b>TOTAL PAGADO:</b> $<?php echo number_format($request["Request"]["value"]) ?></p>
			<hr>
		</div>
	</div>

	<script type="text/javascript">
		if ($("#statushere").hasClass("Pagado")) {
		    $("#statuscreditp").html("<hr>CRÉDITO A PAZ Y SALVO");
		}
	</script>


<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<script>
    $("#imprimeData").click(function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#pdfPayment").printArea( options );
    });
</script>
