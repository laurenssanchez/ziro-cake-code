<?php date_default_timezone_set('America/Bogota');  ?>


Crédito
<b><?php echo $creditInfo["CreditsRequest"]["code_pay"]; ?></b>
- Valor retirado $<?php echo number_format($creditInfo["Credit"]["value_request"]) ?>


<br>
<br>

<table class="table" cellpadding="0" cellspacing="0" >
	<thead class="text-primary">
		<tr>
			<th>Fecha Inicio</th>
			<th>Frecuencia</th>
			<th>Cuotas</th>
			<th>Tasa interés</th>
			<th>Valor Cuota</th>
			<th>Valor Restante</th>
			<th>Proveedor</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $creditInfo["CreditsRequest"]["date_disbursed"] ?></td>
			<td>
				<?php
					if ($creditInfo["CreditsRequest"]["request_type"] == 1)
						$tipoCredito= "Mensual";
					else if($creditInfo["CreditsRequest"]["request_type"] == 3)
						$tipoCredito= "45 días";
					else if($creditInfo["CreditsRequest"]["request_type"] == 4)
						$tipoCredito= "60 días";
					else
						$tipoCredito= "Quincenal";

					echo $tipoCredito;
				?>
				<!-- <?php echo $creditInfo["CreditsRequest"]["request_type"] == 1 ? "Mensual" : "Quincenal"; ?> -->
			</td>

			<td><?php echo $creditInfo["Credit"]["number_fee"] ?></td>
			<td><?php echo $creditInfo["Credit"]["interes_rate"] ?>%</td>
			<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>
			<td>$<?php echo number_format($creditInfo["Credit"]["quota_value"]*$creditInfo["Credit"]["number_fee"]) ?></td>
			<td><?php echo h($creditRequest['ShopCommerce']['name']." - ".$creditRequest['ShopCommerce']["Shop"]['social_reason']); ?></td>
		</tr>
	</tbody>
</table>

<br>
<br>


<h3>Plan de pagos</h3>
<table class="table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
        	<th>#</th>
	        <th>Valor a capital</th>
	        <th>Valor interes</th>
	        <th>Valor otros cargos</th>
	        <th>Total cuota</th>
	        <th>Fecha límite</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quotes as $key => $value): ?>
            <tr>
                <td><?php echo $value["CreditsPlan"]["number"] ?></td>
                <td>$ <?php echo number_format($value["CreditsPlan"]["capital_value"]) ?></td>
                <td>$ <?php echo number_format($value["CreditsPlan"]["interest_value"]) ?></td>
                <td>$ <?php echo number_format($value["CreditsPlan"]["others_value"]) ?></td>
                <td>
                    $ <?php echo number_format($creditInfo["Credit"]["quota_value"]) ?></td>
                <td>
                    <?php echo date("d-m-Y h:i A",strtotime($value['CreditsPlan']['deadline'])); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
