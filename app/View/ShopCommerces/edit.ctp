


<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Editar').' '.__('Sucursal	'); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-8 col-sm-8  form-group pull-right top_search">
			<a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo __('Listar'); ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'view',$this->Utilidades->encrypt($this->request->data['ShopCommerce']['id'])));?>">
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
				<?php echo $this->Form->create('ShopCommerce', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left',"type" => "file")); ?>
						<div class='item form-group' style="display:none;">
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('ShopCommerce.name',__('Nombre'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('name', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('ShopCommerce.address',__('Dirección'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('address', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('ShopCommerce.phone',__('Teléfono'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('phone', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopCommerce.image',__('Foto sucursal'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('image', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "file")); ?>
								</div>
						</div>
						<div class='item form-group' >
							<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('user_id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,"type"=>"hidden")); ?>
							</div>
						</div>
						<div class='item form-group' >
							<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('shop_id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,"type"=>"hidden")); ?>
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
