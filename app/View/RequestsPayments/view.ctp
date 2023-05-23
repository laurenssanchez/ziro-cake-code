<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('solicitud de pago - recaudos web'); ?></h3>
    </div>

    <div class="title_right">
    	<?php if (in_array($requestsPayment["RequestsPayment"]["state"], [0,2]) && AuthComponent::user("role") == 1 ): ?>
    		
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'change',$this->Utilidades->encrypt($requestsPayment['RequestsPayment']['id']))); ?>" class="btn btn-danger btn-sm paymentState">
                <?php if($requestsPayment['RequestsPayment']['state'] == 1): ?>                    
                	<i class="fa fa-times-circle-o"></i> No pagar
                <?php  else: ?> 
                    <i class="fa fa-check-circle"></i> Marcar como pagado
                 <?php endif;  ?>                                      
            </a>
        </div>

    	<?php endif ?>

    	<?php if ($requestsPayment["RequestsPayment"]["state"] == 0 && AuthComponent::user("role") == 1 ): ?>
	        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
	            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'pending',$this->Utilidades->encrypt($requestsPayment['RequestsPayment']['id']))); ?>" class="btn btn-warning btn-sm pendingState">
	               Marcar como pendiente                                 
	            </a>
	        </div>
    	<?php endif ?>
    </div>
</div>

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <tbody>
                            		<tr>
										<td><?php echo __('Comercio'); ?></td>
										<td>
											<?php echo h($requestsPayment['ShopCommerce']['name'])." ".$requestsPayment['ShopCommerce']['Shop']["social_reason"]; ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Porcentaje de comisión'); ?></td>
										<td>
											<?php echo h($requestsPayment['RequestsPayment']['comision_percentaje']); ?>%&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Valor solicitado'); ?></td>
										<td>
											$<?php echo number_format($requestsPayment['RequestsPayment']['value']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Valor comisión'); ?></td>
										<td>
											$<?php echo number_format($requestsPayment['RequestsPayment']['comision_value']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Valor total'); ?></td>
										<td>
											$<?php echo number_format($requestsPayment['RequestsPayment']['comision_value']+$requestsPayment['RequestsPayment']['value']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Fecha de pago'); ?></td>
										<td>
											<?php echo h($requestsPayment['RequestsPayment']['date_payment']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Nota de pago'); ?></td>
										<td>
											<?php echo h($requestsPayment['RequestsPayment']['note_payment']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Fecha de pendiente'); ?></td>
										<td>
											<?php echo h($requestsPayment['RequestsPayment']['date_pending']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Nota de pendiente'); ?></td>
										<td>
											<?php echo h($requestsPayment['RequestsPayment']['note']); ?>&nbsp;
										</td>
									</tr>
									<tr>
										<td><?php echo __('Estado'); ?></td>
								<td> 
									<?php 
										if ($requestsPayment['RequestsPayment']['state'] == 0) {
										echo "No pagado";
									}elseif ($requestsPayment['RequestsPayment']['state'] == 2) {
										echo "Pendiente";
									}else{
										echo "Pagado";
									}
									 ?> 
								</td>									
							</tr>

                        </tbody>
                    </table>
                    <hr>
                    <h5><b>Pagos recibidos</b></h5>
                    <table class="table table-striped table-hover">
						<tbody>
							<?php foreach ($requestsPayment["Request"] as $key => $value): ?>
								<tr>
									<td>
										<b>Cédula:  <?php echo $value["identification"]; ?></b><br>
										<b>Código:  <?php echo $value["code"]; ?></b><br>
										<b>Fecha pago:  <?php echo $value["date_payment"]; ?></b><br>
									</td>
									<td>
										<i class="fa fa-plus"></i> $<?php echo number_format($value["value"],"2",".",",") ?>
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

<?php echo $this->Html->script("requests/others.js?".rand(),array('block' => 'AppScript')); ?>