


<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Editar').' '.__('Simulador'); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-8 col-sm-8  form-group pull-right top_search">
			<a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo __('Listar'); ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'view',$this->Utilidades->encrypt($this->request->data['Simulator']['id'])));?>">
		        <i class="fa fa-eye"></i>
		        <?php echo __('Ver'); ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
		        <i class="fa fa-plus-circle"></i>
		        <?php echo __('Adicionar'); ?>
		    </a>

		</div>
	</div>
</div>
<div class="clearfix"></div>

<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php echo $this->Form->create('Simulator', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
													<div class='item form-group' style="display:none;">
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('Simulator.name',__('Nombre'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('name', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Simulator.commerce_code',__('Código de proveedor'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('commerce_code', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Simulator.color_code',__('Código de color'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('color_code', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"color")); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('Simulator.credits_line_id',__('Línea de crédito'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('credits_line_id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('Simulator.website',__('Website'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('website', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
	            				<div class="ln_solid"></div>
									<div class="item form-group">
										<div class="col-md-6 col-sm-6 offset-md-4">
											<button type="submit" class="btn btn-success">Guardar</button>
										</div>
									</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>