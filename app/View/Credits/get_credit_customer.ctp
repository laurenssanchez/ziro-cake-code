<div class="x_panel row justify-content-center">
	<div class="x_content col-12 col-md-8 col-lg-5">
		<form id="creditPaymentForm">
			<div class="row">
				<div class="title-tables mb-3 text-center col-md-12">
					<h4 class="d-inline pt-2 font-weight-bold">Datos del crédito</h4>
				</div>
			</div>
			<div class="row">
				<div class="card-credit datauser-credit text-left pb-3 col-md-12 active">
					<h4 class="pt-2 mb-0 text-capitalize"><b> <?php echo $creditsCliente["commerce"] ?></b></h4>
					<h5 class="mb-0 text-left"> <span class="card-credit--titulo">Fecha del crédito:</span> <span><?php echo date("d-m-Y",strtotime($creditInfo["Credit"]["created"])) ?></span></h5>
					<h5 class="mb-0 text-left"><span class="card-credit--titulo">Cuotas Pagadas:</span> <span><?php echo $cuotesPayment ?></span></h5>
				</div>
			</div>
			<hr>
			<h4 class="text-center pt-0 font-weight-bold"><b>¿Qué tipo de pago realizarás?</b></h4>
			<div class="type-payment">
				<div class="form-check borderb p-1">
				  <input class="form-check-input radioCheck alineac" type="radio" id="paymentTotal" name="typePayment" id="" value="1" data-value="<?php echo $creditsCliente["values"]["total"] ?>">
				  <label class="form-check-label ml-3">Pago total</label>
				  <span class="">$<?php echo number_format($creditsCliente["values"]["total"] <= 1 ? 0 : $creditsCliente["values"]["total"]) ?></span>
				</div>
				<div class="form-check borderb p-1">
				  <input class="form-check-input radioCheck alineac" type="radio" id="PaymentMin" name="typePayment" id="" value="2" data-value="<?php echo $creditsCliente["values"]["min_value"] ?>">
				  <label class="form-check-label ml-3">Pago Mínimo </label>
				  <span class="">$<?php echo number_format($creditsCliente["values"]["min_value"]) ?></span>
				</div>
				<div class="form-check p-1">
				  <input class="form-check-input radioCheck alineac" type="radio" name="typePayment" id="checkDataSelect" value="3">
				  <label class="form-check-label ml-3">Otro Valor</label>
				  	<div class="form-group mt-3">
				    <input type="number" class="form-control" id="otherValue" aria-describedby="emailHelp">
				    <small id="emailHelp" class="form-text">Valor entre $5.000 y (Pago total)</small>
				  </div>
				</div>
			</div>
			<div class="text-center mt-2">
				<a href="" class="btn btn-primary btn-lg mb-5 paymentBtnFast" data-id="<?php echo $this->Utilidades->encrypt($creditInfo["Credit"]["id"]) ?>">Realizar Pago</a>
			</div>
		</form>
	</div>
</div>
