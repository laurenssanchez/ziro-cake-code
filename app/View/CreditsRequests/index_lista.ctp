<?php

$whitelist = array(
            '127.0.0.1',
            '::1'
        );
?>
<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Panel de solicitudes'); ?></h3>
		</div>

		<?php if (in_array(AuthComponent::user("role"), [4,6])): ?>
			<div class="col-md-3">
				<a href="" class="btn btn-primary pull-right" id="btnSearch">
					Buscar cliente <i class="fa fa-search vtc">	</i>
				</a>
			</div>
		<?php endif ?>

		<?php if (in_array(AuthComponent::user("role"),[1,2,3])): ?>
		<div class="col-md-12">
			<div class="form-group topsearch controlmb">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
				<div class="row">
					<div class="col-md-3 mb-2">
						<?php echo $this->Form->input('state', array('label'=>__('Estado'), 'class'=>'form-control','div'=>false,"options" => [
							0=>"Solicitud",
							1=>"Estudio",
							2=>"Estudio",
							3=>"Aprobado sin desembolsar",
							4=>"Rechazado",
							5=>"Aprobado con desembolso",
						],'value'=> isset($state) ? $state : "" )) ?>
					</div>
					<div class="col-md-3">
						<div class="form-group mb-0">
							<?php echo $this->Form->input('ccCustomer', array('label'=>__('Cliente por cédula'),'placeholder'=>__('Ingresa la cédula'), 'class'=>'form-control','div'=>false,'value'=> isset($ccCustomer) ? $ccCustomer : "" )) ?>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group mb-0">
							<?php echo $this->Form->input('idrequest', array('label'=>__('Solicitud por Código'),'placeholder'=>__('Ingresa el código'), 'class'=>'form-control','div'=>false,'value'=> isset($idrequest) ? $idrequest : "" )) ?>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="">
								No. obligación
							</label>
							<?php echo $this->Form->input('n_obligacion', array('placeholder'=>__('No. obligación'), 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($n_obligacion) ? $n_obligacion : "" )) ?>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group mb-0">
							<?php echo $this->Form->input('commerce', array('label'=>__('Por Código Proveedor'),'placeholder'=>__('Ingresa el código'), 'class'=>'form-control','div'=>false,'value'=> isset($commerce) ? $commerce : "" )) ?>
						</div>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>
							<div class="rangofechas input-group mb-0">
								 <label for="">Rango de fechas</label>
								<input type="date" name="ini" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
								<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								<input type="date" name="end" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="col-md-3">
						<span class="input-group-btn ">
							<button class="btn btn-primary" type="submit" id="busca">
								<?php echo __('Buscar '); ?> <i class="fa fa-search"></i>
							</button>
						<?php if (isset($ccCustomer) || isset($commerce) || isset($fechas) || isset($idrequest) ): ?>
							<a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index","?"=>["usoFecha" => "1","ccCustomer" => "","commerce" => "","dateIni" => date("Y-m-d",strtotime("-1 month")),"dateEnd" => date("Y-m-d") ]]) ?>" class="btn btn-warning">
				          		Eliminar filtro
				          	</a>
				          	<?php if ( in_array(AuthComponent::user("role"), [1,2]) ): ?>
				          		<input type="submit" name="excel_data" value="Descargar excel" class="btn btn-info">
				          	<?php endif ?>
						<?php endif ?>
						</span>
					</div>
				</div>
				<?php echo $this->Form->end(); ?>

			</div>
		</div>
		<?php endif ?>


	</div>
</div>



<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_content">
				<div class="table-responsive">
				<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
					<thead class="text-primary">
						<tr>
							<th><?php echo $this->Paginator->sort('created', __('Fecha solicitud')); ?></th>
							<th><?php echo $this->Paginator->sort('created', __('Codigo')); ?></th>
							<th><?php echo $this->Paginator->sort('created', __('# Obligación')); ?></th>
							<th><?php echo $this->Paginator->sort('name', __('Cliente')); ?></th>
							<th><?php echo $this->Paginator->sort('identification', __('CC')); ?></th>
							<th><?php echo $this->Paginator->sort('phone_number', __('Celular')); ?></th>
							<th><?php echo $this->Paginator->sort('name', __('Analista')); ?></th>
							<th><?php echo $this->Paginator->sort('shop_commerce_id', __('Proveedor')); ?></th>
							<th><?php echo $this->Paginator->sort('request_value', __('Valor Solicitado / Valor Aprobado')); ?></th>
							<th><?php echo $this->Paginator->sort('value_approve', __('Valor Retirado / Valor Disponible')); ?></th>
							<th><?php echo $this->Paginator->sort('state', __('Estado')); ?></th>
							<th><?php echo $this->Paginator->sort('state', __('Pago')); ?></th>
							<th><?php echo __('Acciones'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($creditsRequest)): ?>
							<?php foreach ($creditsRequest as $key => $value): ?>
								<tr>
									<td><?php echo date("d-m-Y",strtotime($value['CreditsRequest']['created'])); ?>&nbsp;</td>

									<td>
										<?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?>&nbsp;
										<?php if (empty($value["Customer"]["email"])): ?>
									    	<span class="badge badge-warning">TRADICIONAL</span>
									    <?php endif ?>
									</td>

									<td>
										<?php echo $value['CreditsRequest']['state']==5 ?  $value['CreditsRequest']['code_pay'] : ''; ?>&nbsp;
									</td>


									<td><?php echo $value["Customer"]["name"] ?> <?php echo $value["Customer"]["last_name"] ?>&nbsp;</td>
									<td><?php echo $value["Customer"]["identification"] ?>&nbsp;</td>
									<td><?php echo $value["Customer"]["celular"] ?>&nbsp;</td>
									<td><span class="badge badge-secondary"><?php $pieces = explode(" ", $value['User']['name']); echo $pieces[0];?></span></td>
									<td>
										<?php if ( !empty($value["CreditsRequest"]["empresa_id"]) ): ?>
											<b>Empresa</b>: <?php echo $value["Empresa"]["social_reason"] ?>
										<?php else: ?>
											<?php echo $value["ShopCommerce"]["shop_name"] ?> - <?php echo $value["ShopCommerce"]["name"] ?>&nbsp;
										<?php endif ?>

									</td>
									<td>
										<?php if (empty($value["Empresa"]["id"])): ?>
											$<?php echo number_format($value["CreditsRequest"]["request_value"]) ?>  x <?php echo $value["CreditsRequest"]["request_number"] ?> cuotas&nbsp; /
										<?php else: ?>
											<b class="text-info">
												Solicitud empresas /
											</b>
										<?php endif ?>
										$<?php echo number_format($value["CreditsRequest"]["value_approve"]) ?>  x <?php echo $value["CreditsRequest"]["number_approve"] ?> cuotas&nbsp;
									</td>
									<td>
										<b>$ <?php echo number_format($value["CreditsRequest"]["value_disbursed"]) ?> </b>/
										<b>$ <?php echo number_format($value["CreditsRequest"]["value_approve"]-$value["CreditsRequest"]["value_disbursed"]) ?></b>
									</td>
									<td>
										<?php switch ($value['CreditsRequest']['state']) {
											case '0':
												echo '<span class="badge badge-primary">Solicitud</span>';
												break;
											case '1':
											case '2':
												echo '<span class="badge badge-info">Estudio</span>';
												break;
											case '3':
												if (!empty($value["CreditsRequest"]["empresa_id"])) {
													echo '<span class="badge badge-morado">Decidido empresa</span>';
												}else{
													echo '<span class="badge badge-warning">Aprobado sin desembolsar</span>';
												}

												break;
											case '4':
												if (!empty($value["CreditsRequest"]["empresa_id"])) {

													echo '<span class="badge badge-morado">Decidido empresa</span>';
												}else{
													echo '<span class="badge badge-danger">Rechazado</span>';
												}
												break;
											case '5':
												echo '<span class="badge badge-success">Aprobado con desembolso</span>';
												break;
											case '7':
												echo '<span class="badge badge-primary">Cancelado por solicitud nueva</span>';
												break;
										} ?>
										<span class="badge badge-danger"><?php echo $value["CreditsRequest"]["reason_reject"] ?></span>
									</td>
									<td>
									    <?php if($value['CreditsRequest']['state']==5 || $value['CreditsRequest']['state']==7 && isset($value["Credit"]["state"])): ?>
									   		<?php echo $value["Credit"]["state"] ==0 ? 'Pendiente' : 'Pagado' ?>
									   <?php endif ?>
									</td>

									<td class="td-actions">

										<a href="#" class="card-link btn btn-outline-secondary btn-sm viewCustomerRequest" data-customer="<?php echo $this->Utilidades->encrypt($value["Customer"]["id"]) ?>" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>" >
										  <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver solicitud"></i>
										</a>
										<?php if (empty($value["CreditsRequest"]["empresa_id"])): ?>

										    <div class="dropdown d-inline">
											    <a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="#" role="button" id="img-credit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												    <i class="fa fa-address-card-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver documento"></i>
												</a>
												<div class="dropdown-menu" aria-labelledby="img-credit">
													<?php
													$url_empresa = '';
													if ($value["CreditsRequest"]["shop_commerce_id"] == 35 ){
														$url_empresa = Configure::read("URL_CREDIVENTAS");
													}
													?>
													<a class="dropdown-item photoUp" data-url="<?php echo $this->Html->url($url_empresa."/files/customers/".$value["Customer"]["document_file_up"],true ) ?>" data-toggle="modal" data-target="#photoid-modal-ccfront">Cédula Frontal</a>
													<a class="dropdown-item photoDown" data-url="<?php echo $this->Html->url($url_empresa."/files/customers/".$value["Customer"]["document_file_down"],true ) ?>" data-toggle="modal" data-target="#photoid-modal-ccpost">Cédula Posterior</a>
													<a class="dropdown-item photoUser" data-toggle="modal" data-target="#photoid-modal-selfie" data-url="<?php echo $this->Html->url($url_empresa."/files/customers/".$value["Customer"]["image_file"],true ) ?>">Selfie</a>
												</div>
											</div>
										<?php endif ?>

									    <!-- <a href="https://2cs.co/mas/webcall.php?ext=101&telefono=<?php echo $value["Customer"]["celular"] ?>&cliente=mascreditos" class="card-link btn btn-outline-secondary btn-sm">
									    	<i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="" data-original-title="Llamar"></i>
									    </a> -->

									    <a class="card-link btn btn-outline-secondary btn-sm viewComments" data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>">
									    	<i class="fa fa-commenting-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Observaciones"></i> <?php echo count($value["CreditsRequestsComment"]) ?>
									    </a>

										<?php if($value['CreditsRequest']['state'] != 5 && $value['CreditsRequest']['state'] != 7) { ?>
											<a href="" class="btn btn-secondary rejectPrev btn-sm " data-request="<?php echo $this->Utilidades->encrypt($value["CreditsRequest"]["id"]) ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Regresar a estudio">
												<i class="fa fa-chevron-left" ></i>
											</a>
										<?php } ?>

										<?php if ($value['CreditsRequest']['state']==5): ?>
											<button
												class="btn btn-outline-secondary btn-sm editCreditValue"
												data-creditId="<?php echo $value["Credit"]["id"]; ?>"
												data-valueCredit="<?php echo $value["CreditsRequest"]["value_disbursed"] ?>"
												data-valorFormateado="<?php echo number_format($value["CreditsRequest"]["value_disbursed"]) ?>"
												data-codePay="<?php echo $value["Credit"]["code_pay"] ?>"
												data-dateIni="<?php echo $value["Credit"]["created"] ?>"
												data-toggle="tooltip"
												data-placement="top"
												title=""
												data-original-title="Editar valores de crédito">
												<i class="fa fa-money"></i>
											</button>

											<a class="btn btn-outline-secondary btn-sm"
												target="blank"
												href="<?php echo $this->Html->url([
													"controller"=>"credits",
													"action"=>"payment_detail",$this->Utilidades->encrypt($value["CreditsRequest"]["id"])
												]) ?>"
												data-toggle="tooltip"
												data-placement="top"
												title=""
												data-original-title="Ver detalle pago">
												<i class="fa fa-check"></i>
											</a>
										<?php endif; ?>
										<br>

										<a class="btn btn-danger btn-sm"
											onclick="return confirm('¿Está seguro de eliminar este registro?')"
											target="blank"
											href="<?php echo $this->Html->url([
												"controller"=>"credits",
												"action"=>"deleteCredit",$value["CreditsRequest"]["id"]
											]) ?>"
											data-toggle="tooltip"
											data-placement="top"
											title=""
											data-original-title="Eliminar crédito">
											<i class="fa fa-trash"></i>
										</a>

									</td>
								</tr>

								<?php endforeach; ?>
								<?php else: ?>
									<tr><td class='text-center' colspan='<?php echo 9; ?>'>No existen resultados</td><tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						<p class="pagination-out">

							<?php echo $this->Paginator->counter(array(
								'format' => __('Página {:page} de {:pages}, {:current} registros de {:count} en total')
							));	?>

						</p>

						<ul class="pagination pagination-info">
							<?php
							echo $this->Paginator->prev('< ' . __('Ant'), array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
							echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'tag' => 'li', 'currentClass' => 'disabled'));
							echo $this->Paginator->next(__('Sig') . ' >', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
							?>
						</ul>
				</div>
			</div>
		</div>
	</div>


<?php echo $this->element("/modals/request"); ?>
<?php echo $this->element("/modals/photoid"); ?>
<?php echo $this->element("/modals/comments"); ?>
<?php echo $this->element("/modals/decision"); ?>
<?php echo $this->element("/modals/credit_applied"); ?>
<?php echo $this->element("/modals/voucher"); ?>
<?php echo $this->element("/modals/credit_detail"); ?>
<?php echo $this->element("/modals/history_payments"); ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php

echo $this->Html->script("home.js?".rand(),           array('block' => 'AppScript'));
echo $this->Html->script("requests/admin.js?".rand(),           array('block' => 'AppScript'));

?>

<?php if (in_array(AuthComponent::user("role"), [1,2,3,4,6,7])): ?>
<script>

var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
var actual_url = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";

function URLToArray(url) {
	var request = {};

	var pairs = url.substring(url.indexOf('?') + 1).split('&');
	if(pairs.length == 1){
		return request;
	}
	console.log(pairs.length)
	for (var i = 0; i < pairs.length; i++) {
		if(!pairs[i])
			continue;
		var pair = pairs[i].split('=');

		if(actual_url != decodeURIComponent(pair[0])+"?" && actual_url != decodeURIComponent(pair[0])){
			request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
		}
	}
	return request;

}


$('#fechasInicioFin').daterangepicker({
	"showDropdowns": false,
	"opens": "center",
	ranges: {
		'Hoy': [moment(), moment()],
		'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
		'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
		'Este mes': [moment().startOf('month'), moment()],
		'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	},
	"locale": {
		"format": "YYYY-MM-DD",
		"separator": " - ",
		"applyLabel": "Aplicar",
		"cancelLabel": "Cancelar",
		"fromLabel": "Desde",
		"toLabel": "Hasta",
		"customRangeLabel": "Definir rango",
		"weekLabel": "W",
		"daysOfWeek": [
			"Do",
			"Lu",
			"Ma",
			"Mi",
			"Ju",
			"Vi",
			"Sa"
		],
		"monthNames": [
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre"
		],
		"firstDay": 1
	},
	"alwaysShowCalendars": true,
	 "startDate": "<?php echo isset($fechaInicioReporte) ? $fechaInicioReporte : date("Y-m-d"); ?>",
	 "endDate": "<?php echo isset($fechaFinReporte) ? $fechaFinReporte : date("Y-m-d"); ?>",
	"maxDate": "<?php echo date("Y-m-d") ?>"
}, function(start, end, label) {


	$("#input_date_inicio,#input_date_inicio_empresa").val(start.format('YYYY-MM-DD'));
	$("#input_date_fin,#input_date_fin_empresa").val(end.format('YYYY-MM-DD'));

	if($("#btn_find_adviser").length){
		$("#btn_find_adviser").trigger('click')
	}
});

$('.editCreditValue').on('click', function() {
	creditId= $(this).attr('data-creditId');
	valueCredit= $(this).attr('data-valueCredit');
	code_pay= $(this).attr('data-codePay');
	valorFormateado = $(this).attr('data-valorFormateado');
	dateStart= $(this).attr('data-dateIni').split(' ');
	dateStart= dateStart[0];
	$("#title-edit-credit").text('');

	$('#value_credit').val('');
	$('#motivo_edicion').val('');
	$('#credit_id').val('');

	//nuevos valores)
	$('#value_credit').val(valueCredit);
	$('#credit_id').val(creditId);
	$('#previous_value').val(valueCredit);
	$('#date_start').val(dateStart);


	$("#title-edit-credit").text(`${code_pay} por valor de ${valorFormateado}`);



	$('#editarValueCredit').modal('show');
})

</script>

<?php endif ?>

<?php if (in_array(AuthComponent::user("role"), [4,6,7])): ?>
	<div class="modal fade" id="searchCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
	  	<div class="modal-dialog  modal-dialog-scrollable modal-lg">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <h5 class="modal-title" id="">
				          <div class="content-tittles">
					            <div class="line-tittles">|</div>
					            <div>
						            <h1>BUSCAR</h1>
						            <h2>CLIENTES EN EL SISTEMA</h2>
					            </div>
				          </div>
			        </h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
		      	<div class="modal-body" id="searchCustomerBody">
					<div class="row">
						<div class="col-md-3 pt-2">
							<label for="#ccCustomer">Cédula del cliente</label>
						</div>
						<div class="col-md-6">
							<input type="number" id="ccCustomer" class="form-control">
						</div>
						<div class="col-md-3">
							<a href="" class="btn btn-search btn-primary" id="btnCustomerSearch">
								<i class="fa fa-search btc"></i>
							</a>
						</div>
					</div>
					<div class="row" id="dataCustomerDataPayment"></div>
		      	</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
			    </div>
		    </div>
	  	</div>
	</div>
<?php endif ?>

<div class="modal fade " id="panelPayments" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">
          <div class="content-tittles">
            <div class="line-tittles">|</div>
            <div>
              <h1>PLAN</h1>
              <h2>DE PAGOS</h2>
            </div>
          </div>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="planPaymentBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="assignValue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar ejecutivo al credito</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form action="" id="formAsign">
        	<div class="row">
        		<div class="col-md-12">
        			<div class="form-group">
        				<label for="userSelect">Seleccionar ejecutivo que deseas asignar</label>
        				<select name="userSelect" id="userSelect" class="form-control" required="">
        					<option value="">Seleccionar ejecutivo</option>
        					<?php foreach ($users as $key => $value): ?>
        						<option value="<?php echo $key ?>"><?php echo $value ?></option>
        					<?php endforeach ?>
        				</select>
        				<input type="hidden" id="requestId">
        			</div>
        			<div class="form-group">
        				<input type="submit" class="btn btn-info" value="Asignar">
        			</div>
        		</div>
        	</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<form  action="<?php echo $this->Html->url(["controller" => "credits", "action" => "editCreditValue"]) ?>" method="post">
	<div class="modal fade" id="editarValueCredit"
		tabindex="-1" role="dialog" aria-labelledby="editarValueCredit" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editarValueCredit">
						Editar valor de crédito obligación #  <span id="title-edit-credit"></span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="value_credit">Nuevo valor del crédito</label>
								<input class="form-control"
									name="value_credit"
									id="value_credit"
									type="number" value=""
									required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="date_start">Inicio del crédito</label>
								<input class="form-control"
									name="date_start"
									id="date_start"
									type="date"
									required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="motivo_edicion">Razón de la edición</label>
								<textarea class="form-control" name="motivo_edicion" cols="60" rows="20" required id="motivo_edicion"></textarea>
							</div>
						</div>

						<input class="form-control"
							name="previous_value"
							id="previous_value"
							type="hidden" value="">

						<input class="form-control"
							name="credit_id"
							id="credit_id"
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

