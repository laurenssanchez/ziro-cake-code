<?php 
	$shopRoles = Configure::read("ROLES");
	unset($shopRoles["1"]);	
	unset($shopRoles["2"]);	
	unset($shopRoles["3"]);	
	unset($shopRoles["4"]);	
	unset($shopRoles["5"]);	
	unset($shopRoles["8"]);	
	unset($shopRoles["9"]);	
	unset($shopRoles["10"]);	
	unset($shopRoles["11"]);	
	unset($shopRoles["12"]);	
?>
<div class="page-title">
	<div class="title_left">
		<h3><?php echo __('Registrar usuarios en sucursales'); ?></h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content pt-3">
				<?php echo $this->Form->create('User', array('role' => 'form','autocomplete' => 'off','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
						<div class='item form-group'>
								<?php echo $this->Form->label('User.name',__('Nombre'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('name', array('class' => 'form-control border-input','autocomplete' => 'off','label'=>false,'placeholder'=>'Nombre del empleado','div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('User.email',__('Correo electrónico'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('email', array('class' => 'form-control border-input','label'=>false,'div'=>false,"data-parsley-remote"=> $this->Html->url(array("controller"=>"users","action"=>"verifyEmail")),"data-parsley-remote-message"=>"El email ya está registrado")); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('User.password',__('Contraseña'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('password', array('class' => 'form-control border-input','label'=>false,'div'=>false,'data-parsley-pattern'=>'(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$','data-parsley-pattern-message'=>'Debe contener al menos un número, una Mayúscula y una minúscula y tener una longitud mínima de 8 caracteres.','required' => true)); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('User.role',__('Rol del usuario'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('role', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" =>$shopRoles )); ?>
								</div>
						</div>
						<div class='item form-group'>
								<?php echo $this->Form->label('User.	',__('Sucursal'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
								<?php echo $this->Form->input('shop_commerce_id', array('class' => 'form-control border-input','label'=>false,'div'=>false,"options" =>$commerces )); ?>
								</div>
						</div>
						<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success pull-right">Guardar Registro de Empleado</button>
							</div>								
						</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>



