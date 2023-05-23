<?php  date_default_timezone_set('America/Bogota');?>
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

						<!--<?php echo json_encode($fecmin[0][0]["fechamin"]); ?>-->
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
								<th><a href="">Valor Total del Crédito Original</a></th>
								<th><a href="">Valor Total Restante</a></th>
								<th><a href="">Valor Total Anticipado</a></th>
								<th><a href="">Valor Capital Restante</a></th>
								<th><a href="">Proveedor</a></th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo date("Y-m-d",strtotime($creditInfo["CreditsRequest"]["date_disbursed"])) ?></td>
                                <!-- echo $creditInfo["CreditsRequest"]["request_type"] != 1 ||---->

								<!-- Frecuencia -->
								<?php if ($creditInfo["Credit"]["type"] == 1) : ?>
									<td>
										<?php echo "Mensual"; ?>
									</td>

								<?php elseif($creditInfo["Credit"]["type"] == 3) : ?>
									<td>
										<?php echo "45 días"; ?>
									</td>

								<?php elseif($creditInfo["Credit"]["type"] == 4) : ?>
									<td>
										<?php echo "60 días"; ?>
									</td>
								<?php else : ?>
									<td>
										<?php echo "Quincenal"; ?>
									</td>
								<?php endif ?>

								<!-- <td><?php echo ($creditInfo["Credit"]["type"] != 1) ? "Quincenal" : "Mensual"; ?></td> -->


								<td><?php echo $creditInfo["Credit"]["number_fee"] ?></td>

								<td><?php echo $creditInfo["Credit"]["interes_rate"] ?>%</td>


								<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>

								<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]*$creditInfo["Credit"]["number_fee"]) ?></td>
								<td>
									$<?php echo number_format($totalCreditFinal <= 1 || $creditInfo["Credit"]["state"] == 1 ? 0 : $totalCreditFinal); /*<= 50 ? 0 :  number_format($totalCredit)*/?>
								</td>
								<td>
									$<?php echo number_format($totalCredit <= 1 || $creditInfo["Credit"]["state"] == 1 ? 0 : $totalCredit); /*<= 50 ? 0 :  number_format($totalCredit)*/?>
								</td>
								<td>$<?php echo number_format($creditInfo["Credit"]["value_pending"] <= 1 ? 0 : $creditInfo["Credit"]["value_pending"]) ?></td>

								<td><?php echo h($creditRequest['ShopCommerce']['name']." - ".$creditRequest['ShopCommerce']["Shop"]['social_reason']); ?></td>
								<td class="td-actions">
									<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest mb-1" data-customer="<?php echo $this->Utilidades->encrypt($creditRequest["Customer"]["id"]) ?>"  data-request="<?php echo $this->Utilidades->encrypt($creditRequest["CreditsRequest"]["id"]) ?>">
										<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
									</a>
									<!-- <a data-toggle="modal" class="card-link btn btn-secondary btn-xs text-white">
									    <i class="fa fa-dollar" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Plan de Pago"></i>
									</a> -->

									<?php if ($creditRequest['CreditsRequest']['state']==5): ?>
										<button
											class="btn btn-outline-secondary btn-sm editarValueCreditData"
											data-creditId="<?php echo $creditInfo["Credit"]["id"]; ?>"
											data-valueCredit="<?php echo $creditRequest["CreditsRequest"]["value_disbursed"] ?>"
											data-valorFormateado="<?php echo number_format($creditRequest["CreditsRequest"]["value_disbursed"]) ?>"
											data-codePay="<?php echo$creditInfo["Credit"]["code_pay"] ?>"
											data-dateIni="<?php echo$creditInfo["Credit"]["created"] ?>"
											data-toggle="tooltip"
											data-placement="top"
											title=""
											data-original-title="Editar valores de crédito">
											<i class="fa fa-money"></i>
										</button>
									<?php endif; ?>

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
							<th>Total Abonado</th>
	                        <th class="">Total a pagar cuota</th>
	                        <!-- <th>Total deuda</th> -->
	                        <th>Fecha límite</th>
	                        <th>Estado del pago</th>
							<th>Fecha pago</th>
							<th>Dias en Mora</th>
							<th></th>
	                    </tr>
	                </thead>
                    <tbody>
                    	<?php $pagoMinimo = 0; $ref = true; $totalPago = 0; $disabledRef = true; $capitalTotal = 0; $interesesPasados = 0; $interesesOther = 0; $cuotes = 1; ?>
                    	<?php $deudaTotal = $creditInfo["Credit"]["value_request"]; ?>

                    	<?php foreach ($quotes as $key => $value): ?>
                    		<?php
                                $capital = $value["CreditsPlan"]["capital_value"]; //-$value["CreditsPlan"]["capital_payment"];
                                $interes = $value["CreditsPlan"]["interest_value"];//-$value["CreditsPlan"]["interest_payment"];
                                $others  = $value["CreditsPlan"]["others_value"];//-$value["CreditsPlan"]["others_payment"];
								$TotalAbonado = $value["CreditsPlan"]["capital_payment"] + $value["CreditsPlan"]["interest_payment"] + $value["CreditsPlan"]["others_payment"];

                                $MyFechaQuota = new DateTime(date("Y-m-d",strtotime($value["CreditsPlan"]["deadline"])));

								$MyFechaPago  = new DateTime(date("Y-m-d",strtotime($value["CreditsPlan"]["date_payment"])));

								$MyfechaActual =  new DateTime(date("Y-m-d"));

								$FechaComparar = $value["CreditsPlan"]["state"] == 0 ? $MyfechaActual:$MyFechaPago;

								$dias = 0;

								if ($MyFechaQuota <= $FechaComparar) {

									$deadline = $MyFechaQuota;

									$nowDate =  $FechaComparar;//new DateTime(date("Y-m-d"));
									$difference = $deadline->diff($nowDate);
									$days = $difference->days;
							        $dias = $days;
								}else{
									$dias = 0;
								}

							?>
                    		<?php if ($value["CreditsPlan"]["state"] == 0): ?>
                    			<?php
                    				if($ref){
										/*echo $capital;
										echo $interes;
										echo $others;
										echo $value["CreditsPlan"]["debt_value"];
										echo $value["CreditsPlan"]["debt_honor"];
										echo $value["CreditsPlan"]["TotalAbo"];*/

                    					$pagoMinimo = $capital+$interes+$others+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] - (is_null($value["CreditsPlan"]["TotalAbo"])?0:$value["CreditsPlan"]["TotalAbo"]);
                    					$ref 		= false;
                    				}
                    			?>
                    		<?php endif ?>
                    		<?php if ($pagoMinimo <= 0): ?>
                    			<?php $pagoMinimo = 0; ?>
                    		<?php endif ?>
                            <tr class="<?php echo $value["CreditsPlan"]["credit_old"] == 10 ? "cuotadicional"  : "" ?>">
                                <td>
                                	<?php if ($value["CreditsPlan"]["state"] == 0): ?>
                                		<input type="checkbox" <?php echo $disabledRef ? "checked" : "" ?> class="cuoteSelect cuotesData numQt_<?php echo $value["CreditsPlan"]["number"] ?>" <?php echo !$disabledRef ? "disabled" : ""; if($disabledRef){ $disabledRef = false;} ?> data-num="<?php echo $value["CreditsPlan"]["number"] ?>" data-cuote="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["id"]) ?>" data-credit="<?php echo $this->Utilidades->encrypt($value["CreditsPlan"]["credit_id"]) ?>" id="">
                                	<?php endif ?>
                                </td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["capital_value"]) ?> </td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["interest_value"]) ?></td>
                                <td>$ <?php echo number_format($value["CreditsPlan"]["others_value"]) ?></td>
								<!-- interes mora -->
                                <td class="">
                                    <?php echo $dias==0?0:number_format($value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) ?>
                                </td>
								<!-- total abonado -->

								<td>$<?php  echo  is_null($value["CreditsPlan"]["TotalAbo"])?0:number_format($value["CreditsPlan"]["TotalAbo"]) ?></td>
								<!-- total pagar -->
                                <td>
                                    <b>
                                    	$ <?php $cuotaNormal = $capital;

                                    	$totalCuota = ($cuotaNormal+$others+$interes+$value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]) - (is_null($value["CreditsPlan"]["TotalAbo"])?0:$value["CreditsPlan"]["TotalAbo"]);

                                    	if ($totalCuota <= 0) {
                                    		$totalCuota = 0;
                                    	}
                                    	echo $value["CreditsPlan"]["state"] == 1 ? 0 :number_format($totalCuota) ?>
									</b>
                                </td>
                                <!-- <td>
                                	$<?php $deudaTotal-=$cuotaNormal;
                                	echo number_format($deudaTotal <= 0 || $deudaTotal <= 1  ? 0: $deudaTotal) ?>
                                </td> -->
                                <td><?php echo $value["CreditsPlan"]["deadline"] ?></td>
                                <td><?php echo $value["CreditsPlan"]["state"] == 0 ? "No pago" : "Pagado"; ?></td>
								<td><?php echo $value["CreditsPlan"]["state"] == 0 ? "" :$value["CreditsPlan"]["date_payment"] ; //$value["CreditsPlan"]["date_payment"] ?></td>
								 <?php $value["CreditsPlan"]["days"] = $value["CreditsPlan"]["days"] < 0 ? 0 : $value["CreditsPlan"]["days"]; ?>
									<td><?php echo $value["CreditsPlan"]["state"] == 0 ? $dias : $dias ?></td>
									<td>
									<button
										class="btn btn-outline-secondary btn-sm editCreditDateValue"
										data-creditId="<?php echo $creditInfo["Credit"]["id"]; ?>"
										data-valueCredit="<?php echo $creditInfo["CreditsRequest"]["value_disbursed"] ?>"
										data-valorFormateado="<?php echo number_format($creditInfo["CreditsRequest"]["value_disbursed"]) ?>"
										data-codePay="<?php echo $creditInfo["Credit"]["code_pay"] ?>"
										data-date-pay="<?php echo $value["CreditsPlan"]["deadline"] ?>"
										data-creditPlanId="<?php echo $value["CreditsPlan"]["id"] ?>">
										<i class="fa fa-edit"></i>
									</button>
								</td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
			</div>
			</div>
		</div>
	</div>
</div>

<div class="page-title">
	<h3 class="">Pagos realizados al crédito</h3>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
			<div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
	                    <tr>
							<th>Tipo</th>
	                    	<th>Valor</th>
	                        <th>Usuario</th>
	                        <th>Fecha</th>
	                    </tr>
	                </thead>
                    <tbody>
						<?php if (!empty($payments)) : ?>
							<?php foreach ($payments as $key => $value): ?>
								<tr>
									<td><?php  echo is_null($value['User']['name']) ? 'Web' : 'Manual' ?></td>
									<td>$<?php  echo number_format($value['Payment']['value']) ?></td>
									<td><?php  echo !is_null($value['User']['name']) ? $value['User']['name'] : '---' ?></td>
									<td><?php  echo $value['Payment']['created'] ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td class="text-center" colspan="8">
									No hay información
								</td>
							</tr>
						<?php endif ?>
                    </tbody>
                </table>
			</div>
			</div>
		</div>
	</div>
</div>


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
												<b><span class="pagoTotalData">$ <?php echo $pagoMinimo ?></span></b>
												<input class="form-control"  type="hidden" id="pagototal"  value="<?php echo $pagoMinimo ?>">
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
										<?php if (!empty($quotes) && in_array( AuthComponent::user("role"),[6] )  || !empty($quotes) && AuthComponent::user("id") == 10123 ) : ?>
										<tr>
											<td colspan="2" class="p-4">
												<a href="" id="paymentFinal" class="btn btn-success btn-block">
													Pagar
												</a>
											</td>
										</tr>
										<?php endif; ?>
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

<?php echo $this->element("/modals/request"); ?>

<?php

	echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/payments.js?".rand(),           array('block' => 'AppScript'));
	echo $this->Html->script("requests/admin.js?".rand(),           array('block' => 'AppScript'));

?>

<script>
	$('.editCreditDateValue').on('click', function() {
		creditId= $(this).attr('data-creditId');
		creditPlanId= $(this).attr('data-creditPlanId');
		valueCredit= $(this).attr('data-valueCredit');
		code_pay= $(this).attr('data-codePay');
		valorFormateado = $(this).attr('data-valorFormateado');
		fechaPago= $(this).attr('data-date-pay');
		$("#title-edit-credit").text('');
		$('#new_value').val('');
		$('#motivo_edicion').val('');
		$('#credit_id').val('');
		$('#credit_plan_id').val('');

		//nuevos valores)
		$('#new_value').val(fechaPago);
		$('#credit_id').val(creditId);
		$('#credit_plan_id').val(creditPlanId);
		$('#previous_value').val(fechaPago);
		$("#title-edit-credit").text(`${code_pay} por valor de ${valorFormateado}`);
		$('#editarValueCredit').modal('show');
	})

	$('.editarValueCreditData').on('click', function() {
		creditId= $(this).attr('data-creditId');
		valueCredit= $(this).attr('data-valueCredit');
		code_pay= $(this).attr('data-codePay');
		valorFormateado = $(this).attr('data-valorFormateado');
		dateStart= $(this).attr('data-dateIni').split(' ');
		dateStart= dateStart[0];
		$("#title-edit-credit-data").text('');

		$('#value_credit_data').val('');
		$('#motivo_edicion_data').val('');
		$('#credit_id_data').val('');

		//nuevos valores)
		$('#value_credit_data').val(valueCredit);
		$('#credit_id_data').val(creditId);
		$('#previous_value_data').val(valueCredit);
		$('#date_start_data').val(dateStart);
		$("#title-edit-credit-data").text(`${code_pay} por valor de ${valorFormateado}`);
		$('#editarValueCreditData').modal('show');
	})
</script>


<!-- Modal -->
<form  action="<?php echo $this->Html->url(["controller" => "credits", "action" => "editCreditDate"]) ?>" method="post">
	<div class="modal fade" id="editarValueCredit"
		tabindex="-1" role="dialog" aria-labelledby="editarValueCredit" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editarValueCredit">
						Editar fecha de crédito obligación #  <span id="title-edit-credit"></span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="new_value">Nueva fecha de pago</label>
								<input class="form-control"
									name="new_value"
									id="new_value"
									type="date"
									required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="motivo_edicion">Razón de la edición</label>
								<textarea class="form-control"
								name="motivo_edicion"
								cols="60"
								rows="20"
								required
								id="motivo_edicion"></textarea>
							</div>
						</div>

						<input class="form-control"
							name="previous_value"
							id="previous_value"
							type="hidden" value="">

						<input class="form-control"
							name="credit_id"
							id="credit_id"
							type="text" value="">

						<input class="form-control"
							name="credit_plan_id"
							id="credit_plan_id"
							type="hidden" value="">

					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Guardar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</form>


<!-- Modal -->
<form  action="<?php echo $this->Html->url(["controller" => "credits", "action" => "editCreditValue"]) ?>" method="post">
	<div class="modal fade" id="editarValueCreditData"
		tabindex="-1" role="dialog" aria-labelledby="editarValueCreditData" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editarValueCreditData">
						Editar valor de crédito obligación #  <span id="title-edit-credit-data"></span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="value_credit_data">Nuevo valor del crédito</label>
								<input class="form-control"
									name="value_credit"
									id="value_credit_data"
									type="number"
									value=""
									required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="date_start_data">Inicio del crédito</label>
								<input class="form-control"
									name="date_start"
									id="date_start_data"
									type="date"
									required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="motivo_edicion_data">Razón de la edición</label>
								<textarea class="form-control" name="motivo_edicion" cols="60" rows="20" required id="motivo_edicion_data"></textarea>
							</div>
						</div>

						<input class="form-control"
							name="previous_value"
							id="previous_value"
							type="hidden" value="">

						<input class="form-control"
							name="credit_id"
							id="credit_id_data"
							type="hidden" value="">

					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Guardar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</form>
