<div class="container-fluid">
		<div class="container text-center mt-2">
		    <a class="" href="<?php echo Router::url("/",true) ?>"><img src="https://creditos.somosziro.com/img/email/mailNuevoCliente/Header.png" class="img-fluid w-50 px-5"></a>
		</div>
		<div class="container mt-4">
			<div class="col-md-8 offset-md-2 bg-white p-5 text-center" id="contentInitial">
				<form action="" method="POST" id="commerceFormData" data-parsley-validate="">
					<h1 class="pt-4 mb-3"><b>Transacciones</b></h1>
					<h5>Realiza el pago de las compras en los almacenes afiliados a Zíro</h5>
					<div class="form-group mt-4 mb-4">
					    <label for="codigo">Código del pago</label>
					    <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ejemplo: 92120123" required data-parsley-type="integer" data-parsley-minlength="5" data-parsley-maxlength="10">
					</div>
					<button type="submit" class="btn btn-primary btn-lg mb-5" >Iniciar pago</button>
				</form>
			</div>
			<div class="col-md-8 offset-md-2 bg-white px-5 text-center" id="resultPayments">
			</div>
		</div>
</div>

<?php echo $this->Html->script("https://checkout.epayco.co/checkout.js?".rand(),           array('block' => 'AppScript')); ?>
<?php echo $this->Html->script("commerce_payment.js?".rand(),           array('block' => 'AppScript')); ?>
