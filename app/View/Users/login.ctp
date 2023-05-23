<?php echo $this->element("menu-landings"); ?>

<div class="container-fluid pr-0 pl-0 pt-3 bg-img">
	<div class="container-login">
		<div class="login-box-body">

			<center>
				<a class="navbar-brand" href="<?php echo Router::url("/", true) ?>"><img src="hola.hpg" class="img-fluid"></a>
			</center>

			<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'data-parsley-validate=""')); ?>
                <div class="form-group usersDataLog" style="display: <?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "none" : "block"; ?>">
                    <?php echo $this->Form->input('email', array('label' => "Correo", 'placeholder' => __('Correo electrónico'), "class" => "form-control")); ?>
                </div>

				<div class="bl-login--input" style="position: relative;">
					<div class="form-group has-feedback usersDataLog"
						style="display: <?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "none" : "block"; ?>">
						<?php echo $this->Form->input(
							'password', array(
								'label' => "Contraseña",
								'placeholder' => __('Contraseña'), "class" => "form-control",
								'before' => '<i class="fa fa-eye" id="showPassword" style="position: absolute; right: 0; top: 50%; cursor:pointer"></i>',
							)
							); ?>
					</div>
				</div>

                <div class="form-group has-feedback" id="codeGen" style="display:<?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "block" : "none"; ?>">
                    <?php echo $this->Form->input('code', array('label' => "Código enviado al celular", 'placeholder' => __('Código enviado'), "class" => "form-control cod-label")); ?>
                </div>

                <div class="text-center">
                    <button type="submit" id="sendBtn" class="btn btn-primary mt-4 home" style="display: <?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "none" : "block"; ?>">
                        <?php echo __('Ingresar') ?>
                    </button>
                    <button type="button" id="validateBtn" class="btn btn-primary mt-4" style="display: <?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "block" : "none"; ?>">
                        <?php echo __('Validar código') ?>
                    </button>
                    <button type="button" id="reenvioBtn" class="btn btn-warning mt-4" style="display: <?php echo AuthComponent::user("id") && AuthComponent::user("validate") == 0 ? "block" : "none"; ?>">
                        <?php echo __('Reenviar código') ?>
                    </button>

                    <div class="form-group mt-4 forgot">
                        <?php echo $this->Html->link(__('¿Olvidaste tu contraseña?'), array('controller' => 'users', 'action' => 'remember_password')) ?>
                    </div>

                    <!-- <div class="form-group">
                        <?php echo $this->Html->link(__('Quiero registrarme'), array('controller' => 'users', 'action' => 'add')) ?>
                    </div> -->
                </div>
			</form>

			<!---->
			<div class="ziro-estres" style="position:fixed; right:10px; bottom:10px; text-align:center; padding:3px">
				<a target="_BLANK" href="https://creditos.somosziro.com/">
					<img src="https://creditos.somosziro.com/img/ziro-estres.png" height="50"></a>
			</div>
		</div>
	</div>
</div>

<?php $this->start("AppScript") ?>

	<script type="text/javascript">
		$("#showPassword").on('click', function() {
			if ($('#UserPassword').prop('type')=='password') {
				$('#UserPassword').attr('type','text');
				$('#showPassword').removeClass('fa-eye').addClass('fa-eye-slash');
			} else {
				$('#UserPassword').attr('type','password');
				$('#showPassword').removeClass('fa-eye-slash').addClass('fa-eye');
			}
		});
	</script>

<?php $this->end() ?>

