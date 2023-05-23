<div class="page-title">
    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <h3><?php echo __('Detalle de la Línea de credito'); ?></h3>
        </div>
        <div class="col-xl-6 col-lg-12 text-right">
            <a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Ver líneas de crédito'); ?>
            </a>

            <a class="btn btn-success"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Crear otra línea'); ?>
            </a>
            <a class="btn btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($creditsLine['CreditsLine']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar esta linea'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($creditsLine['CreditsLine']['id']))); ?>" class="btn btn-danger changeState">
                <?php if($creditsLine['CreditsLine']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
                <?php  else: ?>
                    <i class="fa fa-check-circle"></i> Habilitar
                 <?php endif;  ?>
            </a>
        </div>
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
								<td><?php echo __('Nombre'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['name']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Descripción'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['description']); ?>&nbsp;
								</td>
							</tr>

							<!--
							<tr>
								<td><?php echo __('Min_value'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['min_value']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Max_value'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['max_value']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Min_month'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['min_month']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('max_month'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['max_month']); ?>&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Tasa de interes'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['interest_rate']); ?>%&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Otros cargos'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['others_rate']); ?>%&nbsp;
								</td>
							</tr>

							<tr>
								<td><?php echo __('Tasa de Mora'); ?></td>
								<td>
									<?php echo h($creditsLine['CreditsLine']['debt_rate']); ?>%&nbsp;
								</td>
							</tr>
									-->

							<tr>
								<td><?php echo __('Estado'); ?></td>
						        <td> <?php echo $creditsLine['CreditsLine']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>
                            </tr>

							<tr>
								<table class="table table-striped custab" id="table1">
									<thead>
										<tr>
											<th class="text-center" >monto minimo</th>
											<th class="text-center" >monto maximo</th>
											<th class="text-center" >Tiempo (Mes)</th>
											<th class="text-center" >Tasa de interes</th>
											<th class="text-center" >Otros cargos</th>
											<th class="text-center" >Tasa de Mora</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($creditLineDetail)): ?>
											<td class="text-center">
												No hay registro
											</td>
										<?php else:
												?>
											<?php foreach ($creditLineDetail as $key => $value): ?>
												<tr>
													<?php if ($value["credits_lines_details"]["count"] == '1'): ?>
														<td class="text-center" rowspan= <?php echo $value["credits_lines_details"]["max_month"] ?> >
															<b><?php echo $value["credits_lines_details"]["min_value"] ?></b>
														</td>
														<td class="text-center" rowspan= <?php echo $value["credits_lines_details"]["max_month"] ?>>
															<b><?php echo $value["credits_lines_details"]["max_value"] ?></b>
														</td>
													<?php endif ?>
													<td class="text-center">
														<b><?php echo $value["credits_lines_details"]["month"] ?></b>
													</td>													
													<td class="text-center">
														<b><?php echo $value["credits_lines_details"]["interest_rate"] ?></b>
													</td>
													<td class="text-center">
														<b><?php echo $value["credits_lines_details"]["others_rate"] ?></b>
													</td>
													<td class="text-center">
														<b><?php echo $value["credits_lines_details"]["debt_rate"] ?></b>
													</td>
												</tr>
											<?php endforeach ?>
										<?php endif ?>																				
									</tbody>
								</table>
							</tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
