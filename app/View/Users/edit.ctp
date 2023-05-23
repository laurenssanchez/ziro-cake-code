<?php 
	$roles = Configure::read("ROLES");
	unset($roles[4]);
	unset($roles[5]);
	unset($roles[6]);
	unset($roles[7]);
?>

<div class="page-title">
    <div class="row">
        <div class="col-md-6">
            <h3>Editar Usuario</h3>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn btn-primary"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo __('Ver todos los usuarios'); ?>
		    </a>

		    <a class="btn btn-secondary"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
		        <i class="fa fa-plus-circle"></i>
		        <?php echo __('Registrar otro usuario'); ?>
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
				<?php echo $this->Form->create('User', array('role' => 'form','data-parsley-validate=""','class'=>'form-horizontal form-label-left')); ?>
													<div class='item form-group' style="display:none;">
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('id', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('User.name',__('Nombre'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('name', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php $readonly = AuthComponent::user("id") == $this->request->data["User"]["id"] ? "readonly" : "" ?>
							<?php $disabled = AuthComponent::user("id") == $this->request->data["User"]["id"] ? "disabled" : "" ?>
							<?php echo $this->Form->label('User.email',__('Correo electrónico'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('email', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,$readonly)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('User.password',__('Contraseña'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('password', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,"value" => "","required"=>false)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('User.phone',__('Número celular'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('phone', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,"type"=>"number","required"=>true)); ?>
								</div>
						</div>
						<div class='item form-group' >
							<?php echo $this->Form->label('User.role',__('Role'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>
								<div class='col-md-6 col-sm-6'>
							<?php echo $this->Form->input('role', array('class' => 'form-control border-input', 'label'=>false,'div'=>false,"options" => $roles,$disabled)); ?>
								</div>
						</div>
        				<div class="ln_solid"></div>
						<div class="item form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success pull-right">Guardar Edición</button>
							</div>								
						</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
