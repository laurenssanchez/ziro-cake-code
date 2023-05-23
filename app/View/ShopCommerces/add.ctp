<div class="page-title">
	<div class="title_left">
		<h3>
			<?php echo __('Crear').' '.__('Sucursal para:'); ?>
			<?php if (AuthComponent::user("role") == 4): ?>
            <?php echo "<b>". AuthComponent::user("Shop.social_reason")."</b>" ?>
          <?php endif ?>
          	
        </h3>
	</div>

</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php echo $this->Form->create('ShopCommerce', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left',"type" => "file")); ?>

						<div class='item form-group'>
							<?php echo $this->Form->label('ShopCommerce.name',__('Nombre'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
							<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('name', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
							</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopCommerce.address',__('Dirección'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('address', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopCommerce.phone',__('Teléfono'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('phone', array('class' => 'form-control border-input','label'=>false,'div'=>false,"data-parsley-minlength"=>"7","data-parsley-type"=>"digits")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopCommerce.image',__('Foto sucursal'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('image', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "file")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('shop_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"hidden","value"=>AuthComponent::user("Shop.id"),"data-parsley-imageextension"=>"3" )); ?>
								</div>
						</div>
							<div class="ln_solid"></div>
							<div class="item form-group">
								<div class="col-md-12">
									<button type="submit" class="btn btn-success pull-right">Crear Sucursal </button>
								</div>								
							</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>



