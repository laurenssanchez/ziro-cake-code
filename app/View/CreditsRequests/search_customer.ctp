<?php if (empty($customer)): ?>
	<div class="w-100">
		<h3 class="text-center text-info">
			El cliente aún no está en el sistema
		</h3>
	</div>
<?php else: ?>
	<table class="table-hovered table removeelement">
		<tr>
			<th>
				Nombre Cliente:
			</th>
			<td class="capt">
				<?php echo $customer["Customer"]["name"] ?>  - <?php echo $customer["Customer"]["id"] ?>
			</td>
		</tr>
		<tr>
			<th>
				Solicitudes en proceso:
			</th>
			<td>
				En la tienda: <?php echo $totalProcesoTienda ?> / Otras tiendas: <?php echo $totalProcesoOtros ?>
			</td>
		</tr>
		<tr>
			<th>
				Solicitudes rechazadas:
			</th>
			<td>
				En la tienda: <?php echo $totalRechazoTienda ?> / Otras tiendas: <?php echo $totalRechazoOtros ?>
			</td>
		</tr>
		<tr>
			<th>
				Solicitudes aprobadas sin desembolso:
			</th>
			<td>
				En la tienda: <?php echo $totalAprobadoSinDesembolsoTienda ?> / Otras tiendas: <?php echo $totalAprobadoSinDesembolsoOtros ?>
			</td>
		</tr>
		<tr>
			<th>
				Creditos activos:
			</th>
			<td>
				En la tienda: <?php echo $totalActivosTieda ?> / Otras tiendas: <?php echo $totalActivosOtros ?>
			</td>
		</tr>
		<?php if ( (isset($customer["User"]["0"]) &&  $customer["User"]["0"]["state"] == 0) || $juridico > 0 ): ?>
			<tr>
				<td colspan="2" class="text-center text-danger">
					<h2>
						Actualmente se encuentra en cobro jurídico
					</h2>
				</td>
			</tr>
		<?php else: ?>
			<tr>
				<td colspan="2" class="text-center">
					<h2>
						Total cupo aprobado
						$<?php echo number_format($totalCupoAprobado); ?>
					</h2>
					<h2>
						Total cupo disponible para compras
						$<?php echo number_format($totalPreaprovved); ?>
					</h2>
					<?php if(isset($mora) && $mora=='true') : ?>
						<h6 class="font-weight-bold text-danger">Cupo bloqueado por mora</h6>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan="<?php echo AuthComponent::user("role") == 4 ? "2" : "2" ?>" class="px-3">
					<?php if ($totalPreAprovvedTienda > 0): ?>
						<span class="text-primary">
							Se tiene 1 o más solicitudes por gestionar de tu tienda
						</span>
					<?php elseif($totalPreaprovved > 0): ?>
						<div class="row">
							<?php if (AuthComponent::user("role") == 4): ?>
								<select name="shop_commerce_id" id="shop_commerce_id" class="form-control col-md ml-2 mr-2">
									<?php foreach ($shopCommerces as $key => $value): ?>
										<option value="<?php echo $key ?>"><?php echo $value ?></option>
									<?php endforeach ?>
								</select>
							<?php else: ?>
								<input type="hidden" id="shop_commerce_id" value="<?php echo AuthComponent::user("shop_commerce_id"); ?>">
							<?php endif ?>
							<a href="" class="btn btn-primary applyCreditNew colmd" data-customer="<?php echo $this->Utilidades->encrypt($customer["Customer"]["id"]) ?>" data-value="<?php echo $totalPreaprovved ?>" data-numberq="<?php echo 4 ?>" data-type="<?php echo empty($customer["Customer"]["email"]) ? 0 : 1 ?>">
								Solicitar retiro de crédito
							</a>

						</div>
					<?php endif ?>

					<?php if (!empty($creditsCliente) &&  in_array(AuthComponent::user("role"), [6])  && $juridico == 0   ||
						!empty($creditsCliente) &&  AuthComponent::user("id") == 10123  && $juridico == 0 ): ?>
						<div class="row">
							<span class="btn btn-info" id="creditspayments">
								Pago de cuotas
							</span>
						</div>

					<?php endif ?>


				</td>

			</tr>


		<?php endif ?>
	</table>


<?php endif ?>
	<hr>
	<div class="col-md-12 d-none tablecredits mt-4">
		<h2><b>Pago de cuotas de créditos vigentes</b></h2>
		<h4>Selecciona el crédito a pagar</h4>
		<table class="table-hovered table " >
		  <thead>
		    <tr>
		      <th>Crédito</th>
		      <!-- <th>Deuda actual</th> -->
		      <th>Acciones</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php if (!empty($creditsCliente) && in_array(AuthComponent::user("role"), [6]) ||  !empty($creditsCliente) && AuthComponent::user("id") == 10123 )  : ?>
		  		<?php foreach ($creditsCliente as $key => $value): ?>
		  			<tr>
			  			<td><?php echo str_pad($value["CreditsRequest"]["id"], 6, "0", STR_PAD_LEFT); ?></td>
					    <!-- <td>$ <?php echo number_format($value["Credit"]["value_pending"]) ?></td> -->
					    <td><a class="btn btn-info btn-sm" href="<?php echo $this->Html->url(["controller"=>"credits","action"=>"payment_detail",$this->Utilidades->encrypt($value["CreditsRequest"]["id"])]) ?>"><i class="fa fa-check"></i> Ir a pagar </a></td>
			  		</tr>
		  		<?php endforeach ?>
		  	<?php else: ?>
		  		<tr>
		  			<td colspan="3">
		  				No hay información
		  			</td>
		  		</tr>
		  	<?php endif ?>

		  </tbody>
		</table>
	</div>
