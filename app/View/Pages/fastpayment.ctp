<div class="container-fluid">
		<div class="container text-center mt-5">
		    <a class="" href="<?php echo Router::url("/",true) ?>"><img src="https://somosziro.com/wp-content/uploads/2022/10/logo-ziro-tonoClaro.png" class="img-fluid  px-5"></a>
		</div>
		<div class="container mt-4">
			<div class="row justify-content-center" >

				<div class="col-md-8 col-lg-4 pt-5 text-center bl-inicioCuotas " id="contentInitial">
					<form action="" method="POST" id="fastFormData" data-parsley-validate="">

						<h5 class="text-white text-justify">Puedes pagar las cuotas de tu crédito EN LÍNEA solo ingresa tu documento de identidad para empezar el proceso de pago.</h5>
						<div class="form-group mt-4 mb-4">
							<input type="text" class="form-control form-control-identificacion" id="identification" name="identification" placeholder="Documento de Identidad" required data-parsley-type="integer" data-parsley-minlength="5" data-parsley-maxlength="10">
						</div>
						<button type="submit" class="btn btn-primary btn-lg mb-5" >Iniciar pago</button>
					</form>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-10 px-0 text-center bl-seleccionCredito" id="resultPayments">
				</div>
			</div>
		</div>
</div>

<?php echo $this->Html->script("https://checkout.wompi.co/widget.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("fast_payment.js?".rand(),           array('block' => 'AppScript')); ?>
