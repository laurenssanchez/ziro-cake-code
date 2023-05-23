<?php date_default_timezone_set('America/Bogota');  ?>
<div class="page-title">
	<h3 class="">Recaudo de Créditos</h3>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="title-tables">
					<h2 class="number_credit text-primary m-0">
						Crédito
						<b><?php echo $creditInfo["CreditsRequest"]["code_pay"]; ?></b>
						- Valor retirado $<?php echo number_format($creditInfo["Credit"]["value_request"]) ?>
					</h2>
					<h3 class="client_credit text-primary mt-0"><b>Cliente: <span class="upper"><?php echo $creditInfo["Customer"]["name"] ?> CC <?php echo $creditInfo["Customer"]["identification"] ?></span></b></h3>
				</div>
				<div class="table-responsive mt-4">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="text-primary">
							<tr>
								<th><a href="">Fecha Inicio</a></th>
								<th><a href="">Frecuencia</a></th>
								<th><a href="">Cuotas</a></th>
								<th><a href="">Interés</a></th>
								<th><a href="">Valor Cuota</a></th>
								<th><a href="">Valor Total del Crédito</a></th>
								<th><a href="">Valor Total Restante</a></th>
								<th><a href="">Valor Capital Restante</a></th>
								<th><a href="">Proveedor</a></th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $creditInfo["CreditsRequest"]["date_disbursed"] ?></td>

								<!-- Frecuencia -->
								<?php if ($creditInfo["CreditsRequest"]["request_type"] == 1) : ?>
									<td>
										<?php echo "Mensual"; ?>
									</td>

								<?php elseif($creditInfo["CreditsRequest"]["request_type"] == 3) : ?>
									<td>
										<?php echo "45 días"; ?>
									</td>

								<?php elseif($creditInfo["CreditsRequest"]["request_type"] == 4) : ?>
									<td>
										<?php echo "60 días"; ?>
									</td>
								<?php else : ?>
									<td>
										<?php echo "Quincenal"; ?>
									</td>
								<?php endif ?>

								<!-- <td><?php echo $creditInfo["CreditsRequest"]["request_type"] != 1 || $creditInfo["Credit"]["number_fee"] >= 5 ? "Quincenal" : "Mensual"; ?></td> -->

								<td><?php echo $creditInfo["Credit"]["number_fee"] ?></td>
								<td><?php echo $creditInfo["Credit"]["interes_rate"] ?>%</td>
								<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>
								<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]*$creditInfo["Credit"]["number_fee"]) ?></td>
								<td>
									$<?php echo $totalCredit <= 50 ? 0 :  number_format($totalCredit) ?>
								</td>
								<td>$<?php echo number_format($creditInfo["Credit"]["value_pending"]) ?></td>
								<td><?php echo h($creditRequest['ShopCommerce']['name']." - ".$creditRequest['ShopCommerce']["Shop"]['social_reason']); ?></td>
								<td class="td-actions">
									<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest mb-1" data-customer="<?php echo $this->Utilidades->encrypt($creditRequest["Customer"]["id"]) ?>"  data-request="<?php echo $this->Utilidades->encrypt($creditRequest["CreditsRequest"]["id"]) ?>">
										<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
									</a>
									<!-- <a data-toggle="modal" class="card-link btn btn-secondary btn-xs text-white">
									    <i class="fa fa-dollar" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Plan de Pago"></i>
									</a> -->

								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-title">
	<h3 class="">Plan de Pagos</h3>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
			<div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
	                    <tr>
	                    	<th>
	                    		<?php if (!empty($quotes)): ?>
	                    			<input type="checkbox" class="selectAll" id="">
	                    		<?php endif ?>
	                    	</th>
	                    	<th>Valor a capital</th>
	                        <th>Valor interes</th>
	                        <th>Valor otros cargos</th>
	                        <th>Intereses de Mora</th>
	                        <th class="">Total a pagar cuota</th>
	                        <!-- <th>Total deuda</th> -->
	                        <th>Fecha límite</th>
	                        <th>Estado del pago</th>
	                    </tr>
	                </thead>
                    <tbody>
                    	<?php $pagoMinimo = 0; $ref = true; $totalPago = 0; $disabledRef = true; $capitalTotal = 0; $interesesPasados = 0; $interesesOther = 0; $cuotes = 1; ?>
                    	<?php $deudaTotal = $creditInfo["Credit"]["value_request"];
						      $firstDate = "";
							  $DateLast  = "";
							  $swich     = 0;
						?>


                    	<?php foreach ($quotes as $key => $value): ?>
                    		<?php
                                $capital = $value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"];
                                $interes = $value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"];
                                $others  = $value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"];


								//Calculo Interes corriente
								if ($firstDate==""){
									$firstDate = $creditInfo["CreditsRequest"]["date_disbursed"];
								 //  $DateLast = $value["CreditsPlan"]["deadline"];
								}else{
								   $firstDate = $DateLast;
								}

							   $secondDate =  ($value["CreditsPlan"]["date_payment"]== "")?$value["CreditsPlan"]["deadline"]:$value["CreditsPlan"]["date_payment"];   //$value["CreditsPlan"]["deadline"];
							   $DateLast   = $secondDate;


							   $fecha1 = new DateTime($firstDate);
							   $fecha2 = new DateTime($secondDate);
							   $resultado = $fecha1->diff($fecha2);
							   $days  = $resultado->format('%a');

							   if ($swich == 0) {
								   $swich  = 1;
								   $days = $days + 1;

							   }

							   if ( $days == 31){
							    	$days = 30;
							   }


							   $interesesT =  ((($creditInfo["Credit"]["value_pending"]* $creditInfo["Credit"]["interes_rate"])/100)/30)*$days ;
                               //Fin Interes corriente

							   //otros intereses
							   $interesesOT =  ((($creditInfo["Credit"]["value_pending"]* $creditInfo["Credit"]["others_rate"])/100)/30)*$days ;

                               //capital
							   $CapitalN  = $creditInfo["Credit"]["quota_value"] - $interesesOT  - $interesesT

							?>
                    		<?php if ($value["CreditsPlan"]["state"] == 0): ?>
                    			<?php
                    				if($ref){
                    					$pagoMinimo = $capital+$interes+$others+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"];
                    					$pagoMinimo = $CapitalN + $interesesT + $interesesOT + $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"];
										$ref 		= false;
                    				}
                    			?>
                    		<?php endif ?>
                            <tr>
                                <td>
                                	<?php if ($value["CreditsPlan"]["state"] == 0): ?>
                                		<input type="checkbox" <?php echo $disabledRef ? "checked" : "" ?> class="cuoteSelect cuotesData numQt_<?php echo $value["CreditsPlan"]["number"] ?>" <?php echo !$disabledRef ? "disabled" : ""; if($disabledRef){ $disabledRef = false;} ?> data-num="<?php echo $value["CreditsPlan"]["number"] ?>" data-cuote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" id="">
                                	<?php endif ?>
                                </td>
                                <td>$ <?php
								    if($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] > 0 ){
										echo number_format($CapitalN);

									}else {
										echo number_format($value["CreditsPlan"]["capital_value"]);
									}

								?></td>

							    <td>$ <?php


								if($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] > 0 ){
									echo  number_format($interesesT);

								}else {
									echo number_format($value["CreditsPlan"]["interest_value"]);
								}

								 ?>


								</td>
                                <td>$ <?php


								if($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] > 0 ){
									echo number_format($interesesOT);

								}else {
									echo number_format($value["CreditsPlan"]["others_value"]);
								}
								 ?>



								</td>

                                <td class="">
                                    <?php


									    echo number_format($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"])



									?>
                                </td>
                                <td>
                                    <b>
                                    	$ <?php // $cuotaNormal = $capital;


											if($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] > 0 ){
												echo number_format($CapitalN + $interesesT + $interesesOT + $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]);

											}else {
												$cuotaNormal = $capital;
                                    	        echo $value["CreditsPlan"]["state"] == 1 ? 0 : number_format($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]);
											}

                                    	//echo $value["CreditsPlan"]["state"] == 1 ? 0 : number_format($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"])
										 ?>
									</b>
                                </td>
                                <!-- <td>
                                	$<?php //$deudaTotal-=$cuotaNormal;
                                	//echo number_format($deudaTotal <= 0 || $deudaTotal <= 1  ? 0: $deudaTotal) ?>
                                </td> -->
                                <td><?php echo $value["CreditsPlan"]["deadline"] ?></td>
                                <td><?php echo $value["CreditsPlan"]["state"] == 0 ? "No pago" : "Pagado"; ?></td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
			</div>
			</div>
		</div>
	</div>
</div>
<?php if (!empty($quotes) && in_array( AuthComponent::user("role"),[6] ) ): ?>
<div class="container">

	<div class="page-title">
		<h3 class="">Seleccionar pago realizado</h3>
	</div>
	<div class="row">
		<div class="col-md-5" style="display:block; margin: 0 auto;">
			<div class="x_panel">
				<div class="x_content">
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="page-title">
								<div class="table-responsive">
									<table class="table">
										<tr>
											<th>
												Pago mínimo:
											</th>
											<td>
												<b><span class="moresize">$ <?php echo number_format($pagoMinimo) ?></span></b>
											</td>
										</tr>
										<tr>
											<th>
												Cuotas seleccionadas:
											</th>
											<td>
												<b><span class="numberCuote"><?php echo $cuotes ?></span></b>
											</td>
										</tr>
										<tr>
											<th>
											   <input type="radio" name="valorPagarData" value="1" checked>	Valor total a pagar
											</th>
											<td>
												<b><span class="pagoTotalData">$ <?php number_format($pagoMinimo)
																			 ?></span></b>
											</td>
										</tr>
										<tr>
											<th>
												<input type="radio" name="valorPagarData" value="2" id="othervalueRadio"> Otro Valor:
											</th>
											<td>
												<input class="form-control" type="number" id="otherValueNum" min="10000" placeholder="Ingresa el valor a pagar">
											</td>
										</tr>
										<tr>
											<td colspan="2" class="p-4">
												<a href="" id="paymentFinal" class="btn btn-success btn-block">
													Pagar
												</a>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<?php endif; ?>
<?php echo $this->element("/modals/request"); ?>

<?php

	echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/payments.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/admin.js?".rand(),           array('block' => 'AppScript'));

?>
