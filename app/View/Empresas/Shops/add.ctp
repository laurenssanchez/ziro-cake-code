<div class="page-title">
	<div class="row">
		<div class="col-md-6">
			<h3><?php echo __('Formulario de Registro de Proveedor'); ?></h3>
		</div>
		<div class="col-md-6 text-right">
			<a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
            	<i class="glyphicon glyphicon-th-list"></i>
            	<?php echo __('Ver proveedor registrados'); ?>
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
				<?php echo $this->Form->create('Shop', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left',"type"=>"file")); ?>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Información del proveedor</h3>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.nit',__('Nit'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('nit', array('class' => 'form-control border-input','placeholder'=>"Ingresa el NIT del proveedor",'label'=>false,'div'=>false,"type" => "text",'data-parsley-type'=>"number","data-parsley-minlength"=>"9","data-parsley-remote"=> $this->Html->url(array("controller"=>"shops","action"=>"verifyNit")),"data-parsley-remote-message"=>"El NIT ya está registrado" )); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.social_reason',__('Razón social'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('social_reason', array('class' => 'form-control border-input','placeholder'=>"Ingresa el nombre del Proveedor",'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.guild',__('Sector económico'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('guild', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" => Configure::read("GREMIOS"), "empty" => "Selecciona el gremio al que pertenece")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.department',__('Departamento'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('department', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.city',__('Ciudad'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('city', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.address',__('Dirección'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('address', array('class' => 'form-control border-input','placeholder'=>"Dirección completa del Proveedor",'label'=>false,'div'=>false)); ?>
								</div>
						</div>

						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.phone',__('Teléfono'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('phone', array('class' => 'form-control border-input','placeholder'=>"Teléfono del proveedor",'label'=>false,'div'=>false,"data-parsley-minlength"=>"7","data-parsley-type"=>"digits")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.chamber_commerce_file',__('Adjunte Cámara de proveedor'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('chamber_commerce_file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "file","data-parsley-fileextension"=>"3","required"=>true)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.rut_file',__('Adjunte RUT'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('rut_file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"file","data-parsley-fileextension"=>"3","required"=>true)); ?>
								</div>
						</div>
						<hr>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Información del Administrador</h3>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.identification_admin',__('Número de identificación del administrador'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('identification_admin', array('class' => 'form-control border-input','placeholder'=>"Ingrese la cédula",'label'=>false,'div'=>false,'data-parsley-type'=>"number","data-parsley-minlength"=>"5")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.name_admin',__('Nombre administrador'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('name_admin', array('class' => 'form-control border-input','placeholder'=>"Nombre completo del Administrador",'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.email',__('Correo electrónico'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('email', array('class' => 'form-control border-input','placeholder'=>"Email del Administrador del Proveedor",'label'=>false,'div'=>false,"data-parsley-remote"=> $this->Html->url(array("controller"=>"users","action"=>"verifyEmail")),"data-parsley-remote-message"=>"El email ya está registrado")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.cellpone_admin',__('Celular del administrador'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('cellpone_admin', array('class' => 'form-control border-input','label'=>false,'placeholder'=>"Ingresa el número de celular del Administrador",'div'=>false,'data-parsley-type'=>"number","data-parsley-minlength"=>"10")); ?>
								</div>
						</div>
						<!-- <div class='item form-group'>
								<?php echo $this->Form->label('Shop.image_admin',__('Foto del administrador'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('image_admin', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "file","data-parsley-imageextension"=>"3")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.identification_up_file',__('Foto cédula parte delantera'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('identification_up_file', array('class' => 'form-control border-input','label'=>false,'div'=>false,'type' => "file","data-parsley-imageextension"=>"3")); ?>

								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.identification_down_file',__('Foto cédula parte trasera'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
									<?php echo $this->Form->input('identification_down_file', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"file","data-parsley-imageextension"=>"3")); ?>
								</div>
						</div> -->

						<hr>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Información Bancaria</h3>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.identification_account',__('Número de identificación del titular de la cuenta'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('identification_account', array('class' => 'form-control border-input','placeholder'=>"Ingresa la cédula asociada a la cuenta bancaria",'label'=>false,'div'=>false,'data-parsley-type'=>"number","data-parsley-minlength"=>"5")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.account_type',__('Tipo de cuenta bancaria'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('account_type', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" => Configure::read("ACCOUNT_TYPES"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.account_bank',__('Banco al que pertenece la cuenta'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('account_bank', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" => Configure::read("BANCOS"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.account_number',__('Número de cuenta'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('account_number', array('class' => 'form-control border-input','placeholder'=>"Revisa y digita detenidamente cada número de la cuenta",'label'=>false,'div'=>false,'data-parsley-type'=>"number","data-parsley-minlength"=>"6")); ?>
								</div>
						</div>
						<!-- <hr>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Información adicional de la empresa</h3>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.services_list',__('Productos o servicios que posee'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('services_list', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type" => "text")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.products_lists',__('Cuenta con:'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('products_lists', array('class' => 'form-control border-input','label'=>false,'div'=>false,"multiple" => "true", "options" => Configure::read("PRODUCT_LIST"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.adviser',__('Asesor'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('adviser', array('class' => 'form-control border-input','placeholder'=>"Ingresa el nombre del Asesor que diligencia la Afiliación",'label'=>false,'div'=>false,"options" => $users)); ?>
								</div>
						</div>
						<hr> -->
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Información de Afiliación</h3>
						</div>
						<div class='item form-group'>
							<?php echo $this->Form->label('Shop.plan',__('Plan de la empresa'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
							<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('plan', array('class' => 'form-control border-input','label'=>false,'div'=>false, "options" => Configure::read("PLANES"),"data-plan-one"=>0,"data-plan-two"=>0 ,"data-cost-plan-one-min" => Configure::read("PLAN_COST_VALUES.1.min"), "data-cost-plan-one-max" => Configure::read("PLAN_COST_VALUES.1.max") ,"data-cost-plan-two-min" => Configure::read("PLAN_COST_VALUES.2.min"), "data-cost-plan-two-max" => Configure::read("PLAN_COST_VALUES.2.max")  )); ?>
							</div>
						</div>

						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.payment_type',__('Tipo de pago'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('payment_type', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" => Configure::read("PAYMENT_TYPE_SHOPS"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php $number_commerces = range(1, 15,1); ?>
								<?php echo $this->Form->label('Shop.number_commerces',__('Número de sucursales'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('number_commerces', array('class' => 'form-control border-input','label'=>false,'div'=>false, "options" =>array_combine($number_commerces,$number_commerces))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.cost_min',__('Comisión pago 1'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('cost_min', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>Configure::read("PLAN_COST_VALUES.1.min"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.cost_max',__('Comosión pago 2'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('cost_max', array('class' => 'form-control border-input','label'=>false,'div'=>false,"value"=>Configure::read("PLAN_COST_VALUES.1.max"))); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('Shop.payment_total',__('Pago total'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
									<?php echo $this->Form->input('payment_total', array('class' => 'form-control border-input','label'=>false,'div'=>false,"readonly","value"=>0)); ?>
								</div>
						</div>
						<hr>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Referencia 1</h3>
						</div>
						<div class='item form-group'>
							<?php echo $this->Form->label('ShopReference.1.name',__('Nombre referencia'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
							<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('ShopReference.1.name', array('class' => 'form-control border-input','placeholder'=>"Nombre de la referencia 1",'label'=>false,'div'=>false)); ?>
							</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopReference.1.phone',__('Teléfono'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('ShopReference.1.phone', array('class' => 'form-control border-input','placeholder'=>"Teléfono y/ celular de la referencia 1",'label'=>false,'div'=>false,"data-parsley-type"=>"integer","data-parsley-minlength"=>"10")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopReference.1.commerce',__('Proveedor que posee'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('ShopReference.1.commerce', array('class' => 'form-control border-input','placeholder'=>"Nombre del establecimiento que posee",'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<hr>
						<div class="title-tables text-center">
							<h3 class="upper text-primary d-inline">Referencia 2</h3>
						</div>
						<div class='item form-group'>
							<?php echo $this->Form->label('ShopReference.2.name',__('Nombre referencia'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
							<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('ShopReference.2.name', array('class' => 'form-control border-input','placeholder'=>"Nombre de la referencia 2",'label'=>false,'div'=>false)); ?>
							</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopReference.2.phone',__('Teléfono'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('ShopReference.2.phone', array('class' => 'form-control border-input','placeholder'=>"Teléfono y/o celular de la referencia 2",'label'=>false,'div'=>false,"data-parsley-type"=>"integer","data-parsley-minlength"=>"10")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('ShopReference.2.commerce',__('Proveedor que posee'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('ShopReference.2.commerce', array('class' => 'form-control border-input','placeholder'=>"Nombre del establecimiento que posee",'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success pull-right">Guardar registro</button>
							</div>
						</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element("take_photo") ?>

<?php

	echo $this->Html->script("ctrl/shops/add.js?".rand(), 						array('block' => 'AppScript'));
 ?>
