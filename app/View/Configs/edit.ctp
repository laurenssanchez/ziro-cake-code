<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Editar').' '.__('Configuración'); ?></h3>
	</div>
</div>
<div class="clearfix"></div>

<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php echo $this->Form->create('Config', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
													<div class='item form-group' style="display:none;">
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('Config.comision',__('Comision por pago online %'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('comision', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
	            				<div class="ln_solid"></div>
									<div class="item form-group">
										<div class="col-md-6 col-sm-6 offset-md-4">
											<button type="submit" class="btn btn-primary">Guardar</button>
										</div>								
									</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
