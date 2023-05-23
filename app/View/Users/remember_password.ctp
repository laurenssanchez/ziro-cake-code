<?php echo $this->element("menu-landings"); ?>

<div class="container-fluid pr-0 pl-0 pt-5 bg-img">
	<div class="container-login">
		<div class="login-box-body">

    <center>
    <a class="navbar-brand" href="<?php echo Router::url("/",true) ?>"><img src="hola.hpg" class="img-fluid"></a></center>

			<div class="form-group">
				<?php echo $this->Html->tag('p',__('Ingresa tu correo electrónico para recordar tu contraseña'), array('class' => 'info- text-center info-remenber-password')); ?>
				<?php echo $this->Form->create('User'); ?>
			</div>
			<div class="">
				<?php echo $this->Form->input('email', array('label' => false, 'placeholder' => __('Correo electrónico'), 'label' => false, 'class' => 'email-field form-control')); ?>
			</div>
			<div class="mt-4 text-center">
				<div class="form-group mr-2 home">
					<?php echo $this->Form->end(__('Siguiente'), array('class' => 'btn btn-login btn-primary home')); ?>
				</div>
				<div class="form-group ">
					<a href="<?php echo $this->Html->url("/") ?>" class="btn btn-secondary home"><?php echo __('Volver') ?></a>
				</div>
			</div>

<!---->
<div class="ziro-estres" style="position:fixed; right:10px; bottom:10px; text-align:center; padding:3px">
<a target="_BLANK" href="https://creditos.somosziro.com/">
<img src="https://creditos.somosziro.com/img/ziro-estres.png" height="50"></a>
</div>


		</div>
	</div>





</div>


<script>
	var button_input = document.querySelector(".submit > input");
	button_input.classList.add("btn");
	button_input.classList.add("btn-primary");
</script>
