


<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Editar').' '.__('Customers Phone'); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-8 col-sm-8  form-group pull-right top_search">
			<a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo __('Listar'); ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'view',$this->Utilidades->encrypt($this->request->data['CustomersPhone']['id'])));?>">
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
				<?php echo $this->Form->create('CustomersPhone', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
													<div class='item form-group' style="display:none;">
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('CustomersPhone.customer_id',__('Customer'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('customer_id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('CustomersPhone.phone_type',__('Phone Type'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('phone_type', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('CustomersPhone.phone_number',__('Phone Number'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('phone_number', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
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
