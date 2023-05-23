<?php

$whitelist = array(
	'127.0.0.1',
	'::1'
);
?>
<div class="page-title">
	<div class="row">
		<div class="col-md-9">
			<h3><?php echo __('Panel de informes - Cupos'); ?></h3>

		</div>

		<?php if (in_array(AuthComponent::user("role"), [1])) : ?>
			<div class="col-md-12">
				<div class="form-group topsearch">
					<?php echo $this->Form->create('', array('role' => 'form', 'type' => 'GET', 'class' => '')); ?>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">
									Cedula cliente
								</label>
								<?php echo $this->Form->input('cedula',
									array('placeholder' => __('Cedula de cliente'),
									'class' => 'form-control',
									'label' => false,
									'div' => false,
									'value' => isset($datos['cedula']) ? $datos['cedula'] : "")) ?>
							</div>
						</div>

						<div class="col pt-4">
							<button class="btn btn-success" type="submit" id="busca" name="accion" value="buscar">
								<i class="fa fa-search"></i>
							</button>

							<button class="btn btn-danger" type="submit" id="busca" name="accion" value="exportar">
								<i class="fa fa-file"></i> Exportar excel
							</button>
							<a href="<?php echo $this->Html->url(["action" => "cupos"]) ?>"
								class="btn btn-info">
								<i class="fas fa-sync-alt"></i> Refrescar
							</a>
						</div>

					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>


<div class="clearfix"></div>

<div class="row" style="display: block;">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_title">
				<h2 class="h3 text-info text-center float-none">
					<!-- <b>Total Cupos: $ <?php echo number_format($totalReceipt) ?></b> -->
				</h2>
			</div>
			<div class="x_content">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
						<thead class="">
							<tr>
								<th><?php echo __('Proveedor'); ?></th>
								<th><?php echo __('Comercio'); ?></th>
								<th><?php echo __('Código comercio'); ?></th>
								<th><?php echo __('Cédula'); ?></th>
								<th><?php echo __('Nombre'); ?></th>
								<th><?php echo __('Teléfono'); ?></th>
								<th><?php echo __('Valor total'); ?></th>
								<th><?php echo __('Valor gastado'); ?></th>
								<th><?php echo __('Valor restante'); ?></th>
								<th><?php echo __('Mora'); ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($clientes)) : ?>
								<?php foreach ($clientes as $key => $value) : ?>
									<?php
										$infoCliente= $this->Utilidades->getInfoCreditCliente($value);

									?>
									<tr>
										<td>
											<?php echo $infoCliente["shop"] ?>
										</td>
										<td>
											<?php echo $infoCliente["commerce"]  ?>
										</td>
										<td>
											<?php echo $infoCliente["commerceCode"]  ?>
										</td>

										<td><?php echo $value["Customer"]["identification"] ?></td>

										<td>
											<?php echo ucfirst($value["Customer"]["name"])." ".ucfirst($value["Customer"]["last_name"]) ?>
										</td>

										<td><?php echo $value["Customer"]["celular"] ?></td>

										<td>
											$<?php echo number_format($infoCliente["cupoTotal"]) ?>
										</td>

										<td>
											$<?php echo number_format($infoCliente["valorGastado"]) ?>
										</td>

										<td>
											$<?php echo number_format($infoCliente['valorLibre']) ?>
										</td>
										<td>
											<?php echo $infoCliente['mora']=='true' ? 'SI' : '---' ?>
										</td>
										<td>
											<button class="btn btn-sm btn-success openModal"
												type="button"
												data-url="<?php echo $this->Html->url(array('controller' => 'credits', 'action' => 'actualizarCupoCliente', $value['Customer']['id'])); ?>"
												data-cupo="<?php echo number_format($infoCliente["cupoTotal"]) ?>"
												data-toggle="modal" data-target="#myModal<?php echo $value["Customer"]["id"] ?>">
												Editar
											</button>
										</td>
									</tr>

								<?php endforeach ?>
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
				<p class="pagination-out">
					<?php

					if (!empty($current) && !empty($count)) {
						echo $this->Paginator->counter(array(
							'format' => __('Página {:page} de {:pages}, {:current} registros de {:count} en total')
						));
					}
					?>
				</p>

				<ul class="pagination pagination-info">
					<?php
					echo $this->Paginator->prev('< ' . __('Ant'), array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
					echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'tag' => 'li', 'currentClass' => 'disabled'));
					echo $this->Paginator->next(__('Sig') . ' >', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
					?>
				</ul>

				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Actualizar valor de cupo</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div>
									<form action="/creditos/1" id="formCupo" method="GET">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="valor_cupo">Valor de cupo:</label>
													<input type="text" class="form-control" id="valor_cupo" name="valor_cupo" value="<?php echo number_format($infoCliente["cupoTotal"]) ?>">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="button" class="btn btn-md btn-success" value="Actualizar" id="guardar">
													<input type="hidden" value="" id="rutaAjax">

												</div>
											</div>
										</div>
									</form>
								</div>

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('.openModal').on('click', function() {
		valor_cupo = $(this).attr('data-cupo').replace(/[,.]/g, "");

		$('#valor_cupo').val(valor_cupo);
		$('#myModal').modal('show');
		$("#rutaAjax").val($(this).attr('data-url'))
	})

	$('#guardar').on('click', function() {
		$("#preloader").show();
		var url= $('#rutaAjax').val();
		valor_cupo = $('#valor_cupo').val();

		$.ajax({
			url:url,
			type: "post",
			data: {
				valor_cupo: valor_cupo,
			},
			success: function(response) {
				if (response==1) {
					$("#preloader").hide();
					showMessage("Datos Guardados Correctamente");
				}else{
					$("#preloader").hide();
					showMessage("Error al actualizar el cupo");
				}
				location.reload();
			},
			error: function(xhr, status, error) {
				$("#preloader").hide();
				showMessage("Error al actualizar el cupo");
				location.reload();
			}
		});
	})
</script>



<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php echo $this->element("fechas"); ?>

<?php echo $this->Html->script("reports/exports.js?" . rand(),           array('block' => 'AppScript')); ?>
