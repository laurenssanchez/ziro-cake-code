<form action="" method="POST" id="fastFormDataSelectCredit" data-parsley-validate="">

	<div class="x_panel">
		<?php if ($customer): ?>
			<div class="title-tables mb-3 text-center col-12">
				<h4 class="mb-0 text-capitalize font-weight-bold">Hola, <b><?php echo $customer["Customer"]["name"]; ?> <?php echo $customer["Customer"]["last_name"]; ?></b></h4>
				<h4 class="font-weight-bold">CC <?php echo $customer["Customer"]["identification"]; ?></h4>
				<h5 class="font-weight-bold d-inline pt-2">Selecciona el crédito que quieres pagar</h5>
			</div>
		<?php endif ?>
		<div class="x_content">
			<div class="row">
				<?php if (empty($customer)): ?>
					<div class="col-12">
						<h5 class="text-center text-danger mb-4">
							El documento que intentas consultar no registra en nuestra Base de Datos, por favor comunícate con soporte técnico
						</h5>
						<button onClick="window.location.reload();" class="btn btn-primary btn-lg mb-2">
							Ingresar otra cédula
						</button>
					</div>
				<?php else: ?>
					<div class="col-12 text-center">
						<img src="https://somosziro.com/wp-content/uploads/2022/10/Arrow-down.png" class="img-fluid my-2" alt="">
					</div>
					<?php if (!empty($creditsCliente)): ?>
						<?php foreach ($creditsCliente as $key => $value): ?>
							<div class="col-md-6">
								<div class="card-credit d-flex justify-content-start align-items-center d-block">
									<div class="form-check alineatop">
										<input class="form-check-input" type="radio" name="creditPayment" id="" required value="<?php echo $key ?>">
									</div>
									<div class="py-2 text-left bl-detalleCuota">
										<h4 class="m-0 text-capitalize"><b><?php echo $value["commerce"] ?></b></h4>
										<span class="card-credit--titulo">Obligación:</span><span>  <?php echo $value["numero"] ?> </span><br>
										<span class="card-credit--titulo">Fecha de Crédito:</span> <span><?php echo $value["fecha"] ?> </span><br>
										<span class="card-credit--titulo">Pago mínimo:</span> <span>$<?php echo number_format($value["values"]["min_value"]) ?> </span>
									</div>
								</div>
							</div>
						<?php endforeach ?>
					<?php else: ?>
						<h3 class="text-center">
							No hay créditos pendientes
						</h3>
					<?php endif ?>
				<?php endif ?>
			</div>

		</div>
		<?php if (!empty($creditsCliente)): ?>
			<div class="text-center mt-4">
				<button type="submit" class="btn btn-primary btn-lg mb-2">
					Cargar deuda
				</button>
			</div>
		<?php endif ?>
	</div>

</form>
