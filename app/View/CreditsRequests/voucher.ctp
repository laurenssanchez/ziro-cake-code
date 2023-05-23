<div class="invoice">
	<header>
		<div class="row">
			<div class="col"><img class="" src="<?php echo $this->Html->url("/img/credishop.svg") ?>" /></div>
			<div class="col company-details">
				<h2><?php echo $creditRequest["ShopCommerce"]["Shop"]["social_reason"] ?></h2>
				<div><?php echo $creditRequest["ShopCommerce"]["Shop"]["nit"] ?></div>
				<div><?php echo $creditRequest["ShopCommerce"]["address"] ?></div>
				<div><?php echo $creditRequest["ShopCommerce"]["phone"] ?></div>
			</div>
		</div>
	</header>
	<main>
		<div class="row contacts">
			<div class="col invoice-to">
				<h2 class="m-0"><?php echo $creditRequest["Customer"]["name"] ?></h2>
				<h2 class="m-0"><?php echo $creditRequest["Customer"]["identification"] ?></h2>
				<h2 class="m-0"><?php echo $creditRequest["Customer"]["CustomersAddress"]["0"]["address"] ?></h2>
				<h2 class="m-0"><?php echo $creditRequest["Customer"]["CustomersPhone"]["0"]["phone_number"] ?></h2>
			</div>
			<div class="col invoice-details">
				<h1 class="invoice-id">Número de obligación: <?php echo str_pad($creditRequest["Credit"]["id"], 6, "0", STR_PAD_LEFT);  ?></h1>
				<div class="date">Fecha Aprobación: <?php echo $creditRequest["CreditsRequest"]["date_admin"] ?></div>
				<div class="date">Fecha Retiro de Productos: <?php echo $creditRequest["CreditsRequest"]["date_disbursed"] ?></div>
			</div>
		</div>
	
		<table class="table ">
			<thead class="thead-light">
				<tr>
					<th>CONCEPTO</th>
					<th>TASA INTERÉS</th>
					<th>#CUOTAS</th>
					<th>VALOR CUOTA</th>
					<th>TOTAL CRÉDITO</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="">Retiro de Productos por crédito Aprobado</td>
					<td class=""><?php echo $creditRequest["Credit"]["interes_rate"] ?>%</td>
					<td class=""><?php echo $creditRequest["Credit"]["number_fee"] ?></td>
					<td class="">$ <?php echo number_format($creditRequest["Credit"]["quota_value"]) ?></td>
					<td class="">$ <?php echo number_format($total) ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"></td>
					<td colspan="2">TOTAL</td>
					<td>$<?php echo number_format($total) ?></td>
				</tr>
			</tfoot>
		</table>
	</main>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal"> Cerrar</button>
</div>